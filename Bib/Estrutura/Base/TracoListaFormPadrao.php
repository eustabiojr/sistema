<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Dialogo\Pergunta;
use Estrutura\Controle\Acao;
use Exception;

/**
 * Standard Form List Trait
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
trait TracoListaFormPadrao
{
    use TracoColecaoPadrao;
    
    /**
     * metodo defLimite()
     * Define the record limit
     */
    public function defLimite($limite)
    {
        $this->limite = $limite;
    }
    
    /**
     * Define the default order
     * @param $ordem The order field
     * @param $direcaot the order direction (asc, desc)
     */
    public function defOrdemPadrao($ordem, $direcao = 'asc')
    {
        $this->ordem = $ordem;
        $this->direcao = $direcao;
    }
    
    /**
     * metodo defCriterio()
     * Define the criterio
     */
    public function defCriterio($criterio)
    {
        $this->criterio = $criterio;
    }

    /**
     * Define a callback metodo to transform objects
     * before load them into datagrid
     */
    public function defTransformador($callback)
    {
        $this->callbackTransforma = $callback;
    }
    
    /**
     * metodo aoRecarregar()
     * Load the datagrid with the database objects
     */
    public function aoRecarregar($param = NULL)
    {
        try
        {
            // open a transaction with database
            Transacao::abre($this->bancodados);
            
            // instancia um repositório
            $repositorio = new Repositorio($this->registroAtivo);
            $limite = isset($this->limite) ? ( $this->limite > 0 ? $this->limite : NULL) : 10;
            // creates a criterio
            $criterio = isset($this->criterio) ? clone $this->criterio : new Criterio;
            if ($this->ordem)
            {
                $criterio->defPropriedade('order',     $this->ordem);
                $criterio->defPropriedade('direction', $this->direcao);
            }
            $criterio->setProperties($param); // order, offset
            $criterio->defPropriedade('limit', $limite);
            
            // load the objects according to criterio
            $objetos = $repositorio->carrega($criterio, FALSE);
            
            if (is_callable($this->callbackTransforma))
            {
                call_user_func($this->callbackTransforma, $objetos);
            }
            
            $this->gradedados->limpa();
            if ($objetos)
            {
                // iterate the collection of active records
                foreach ($objetos as $objeto)
                {
                    // add the object inside the datagrid
                    $this->gradedados->adicItem($objeto);
                }
            }
            
            // reset the criterio for record count
            $criterio->redefinePropriedade();
            $conta = $repositorio->conta($criterio);
            
            if (isset($this->NavigacaoPagina))
            {
                $this->NavigacaoPagina->defContador($conta); // count of records
                $this->NavigacaoPagina->defPropriedades($param); // order, page
                $this->NavigacaoPagina->defLimite($limite); // limit
            }
            
            // close the transaction
            Transacao::fecha();
            $this->carregado = true;
        }
        catch (Exception $e) // in case of exception
        {
            // exibes the exception error message
            new Mensagem('erro', $e->getMessage());
            // undo all pending operations
            Transacao::desfaz();
        }
    }
    
    /**
     * metodo aoSalvar()
     * Executed whenever the user clicks at the save button
     */
    public function aoSalvar()
    {
        try
        {
            // open a transaction with database
            Transacao::abre($this->bancodados);
            
            // get the form data
            $objeto = $this->form->obtDados($this->registroAtivo);
            
            // validate data
            $this->form->valida();
            
            // stores the object
            $objeto->grava();
            
            // fill the form with the active record data
            $this->form->defDados($objeto);
            
            // close the transaction
            Transacao::fecha();
            
            // exibes the success message
            new Mensagem('info', 'Registro salvo');
            
            // reload the listing
            $this->aoRecarregar();
            
            return $objeto;
        }
        catch (Exception $e) // in case of exception
        {
            // get the form data
            $objeto = $this->form->obtDados($this->registroAtivo);
            
            // fill the form with the active record data
            $this->form->defDados($objeto);
            
            // exibes the exception error message
            new Mensagem('erro', $e->getMessage());
            
            // undo all pending operations
            Transacao::desfaz();
        }
    }
    
    /**
     * metodo aoApagar()
     * executed whenever the user clicks at the delete button
     * Ask if the user really wants to delete the record
     */
    public function aoApagar($param)
    {
        // define the delete action
        $acao = new Acao(array($this, 'Apaga'));
        $acao->defParametros($param); // pass the key parameter ahead
        
        // exibes a dialog to the user
        new Pergunta('Você quer realmente apagar?', $acao);
    }
    
    /**
     * metodo Apaga()
     * Apaga a record
     */
    public function Apaga($param)
    {
        try
        {
            // get the parameter $chave
            $chave = $param['key'];
            // open a transaction with database
            Transacao::abre($this->bancodados);
            
            $classe = $this->registroAtivo;
            
            // instantiates object
            $objeto = new $classe($chave);
            
            // deletes the object from the database
            $objeto->delete();
            
            // close the transaction
            Transacao::fecha();
            
            // reload the listing
            $this->aoRecarregar( $param );
            // exibes the success message
            new Mensagem('info', 'Registro apagado');
        }
        catch (Exception $e) // in case of exception
        {
            // exibes the exception error message
            new Mensagem('erro', $e->getMessage());
            // undo all pending operations
            Transacao::desfaz();
        }
    }
    
    /**
     * Clear form
     */
    public function aoLimpar($param)
    {
        $this->form->limpa();
    }
    
    /**
     * metodo aoEditar()
     * Executed whenever the user clicks at the edit button da datagrid
     */ 
    public function aoEditar($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $chave
                $chave = new$param['key'];
                
                // open a transaction with database
                Transacao::abre($this->bancodados);
                
                $classe = $this->registroAtivo;
                
                // instantiates object
                $objeto = new $classe($chave);
                
                // fill the form with the active record data
                $this->form->defDados($objeto);
                
                // close the transaction
                Transacao::fecha();
                
                $this->aoRecarregar( $param );
                
                return $objeto;
            }
            else
            {
                $this->form->limpa();
            }
        }
        catch (Exception $e) // in case of exception
        {
            // exibes the exception error message
            new Mensagem('erro', $e->getMessage());
            // undo all pending operations
            Transacao::desfaz();
        }
    }
    
    /**
     * Shows the page
     */
    public function exibe()
    {
        // check if the datagrid is already loaded
        if (!$this->carregado AND (!isset($_GET['metodo']) OR $_GET['metodo'] !== 'aoRecarregar') )
        {
            $this->aoRecarregar( func_get_arg(0) );
        }
        parent::exibe();
    }
}
