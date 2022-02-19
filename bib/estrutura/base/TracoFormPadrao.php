<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Nucleo\NucleoAplicativo;
use Exception;

/**
 * Standard Form Trait
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
trait TracoFormPadrao
{
    protected $aposAcaoSalvar; 
    protected $usaMesagens;
    
    use TracoControlePadrao;
    
    /**
     * method defAposAcaoSalvar()
     * Define after save action
     */
    public function defAposAcaoSalvar($acao)
    {
        $this->aposAcaoSalvar = $acao;
    }
    
    /**
     * Define if will use messages after operations
     */
    public function defUsaMensagens($bool)
    {
        $this->useMesagens = $bool;
    }
    
    /**
     * method aoSalvar()
     * Executed whenever the user clicks at the save button
     */
    public function aoSalvar()
    {
        try
        {
            if (empty($this->bancodados))
            {
                throw new Exception("Banco de dados não foi definido. Você deve chamar defBancodados no Construtor");
            }
            
            if (empty($this->registroAtivo))
            {
                throw new Exception("Registro Ativo não definido. Você deve chamar defRegistroAtivo no Construtor");
            }
            
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
            
            // shows the success message
            if (isset($this->useMesagens) AND $this->useMesagens === false)
            {
                NucleoAplicativo::carregaURLPagina( $this->aposAcaoSalvar->serializa() );
            }
            else
            {
                new Mensagem('info', 'Registro salvo', $this->aposAcaoSalvar);
            }
            
            return $objeto;
        }
        catch (Exception $e) // in case of exception
        {
            // get the form data
            $objeto = $this->form->obtDados();
            
            // fill the form with the active record data
            $this->form->defDados($objeto);
            
            // shows the exception error message
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
        $this->form->limpa( true );
    }
    
    /**
     * method aoEditar()
     * Executed whenever the user clicks at the edit button da datagrid
     * @param  $param An array containing the GET ($_GET) parameters
     */
    public function aoEditar($param)
    {
        try
        {
            if (empty($this->bancodados))
            {
                throw new Exception("Banco de dados não foi definido. Você deve chamar defBancodados no Construtor");
            }
            
            if (empty($this->registroAtivo))
            {
                throw new Exception("Registro Ativo não definido. Você deve chamar defRegistroAtivo no Construtor");
            }
            
            if (isset($param['key']))
            {
                // get the parameter $chave
                $chave = $param['key'];
                
                // open a transaction with database
                Transacao::abre($this->bancodados);
                
                $class = $this->registroAtivo;
                
                // instantiates object
                $objeto = new $class($chave);
                
                // fill the form with the active record data
                $this->form->defDados($objeto);
                
                // close the transaction
                Transacao::fecha();
                
                return $objeto;
            }
            else
            {
                $this->form->limpa(true);
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new Mensagem('erro', $e->getMessage());
            // undo all pending operations
            Transacao::desfaz();
        }
    }
}
