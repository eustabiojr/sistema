<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;


use Exception;
use DomDocument;
use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Expressao;
use Estrutura\BancoDados\Filtro;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Dialogo\Pergunta;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\NucleoAplicativo;
use Estrutura\Registro\Sessao;

/**
 * Standard Collection Trait
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
trait TracoColecaoPadrao
{
    protected $camposFiltro;
    protected $filtrosForm;
    protected $TransformadoresFiltro;
    protected $carregado;
    protected $limite;
    protected $operadores;
    protected $operadores_logico;
    protected $ordem;
    protected $direcao;
    protected $criterio;
    protected $transformCallback;
    protected $aposCarregarCallback;
    protected $ordemComandos;
    
    use TracoColecaoPadrao;
    
    /**
     * metodo defLimite()
     * Define the record limite
     */
    public function defLimite($limite)
    {
        $this->limite = $limite;
    }
    
    /**
     * Set list widget
     */
    public function defColecaoObjeto($objeto) 
    {
        $this->gradedados = $objeto;
    }
    
    /**
     * Set order command
     */
    public function defOrdemComando($ordem_coluna, $ordem_comando)
    {
        if (empty($this->ordemComandos))
        {
            $this->ordemComandos = [];
        }
        
        $this->ordemComandos[$ordem_coluna] = $ordem_comando;
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
     * metodo defCampoFiltro()
     * Define wich field will be used for filtering
     * PS: Just for Backwards compatibility
     */
    public function defCampoFiltro($campoFiltro) 
    {
        $this->adicCampoFiltro($campoFiltro);
    }
    
    /**
     * metodo defOperador()
     * Define the filtering operator
     * PS: Just for Backwards compatibility
     */
    public function defOperador($operador)
    {
        $this->operadores[] = $operador;
    }
    
    /**
     * metodo adicCampoFiltro()
     * Add a field that will be used for filtering
     * @param $campoFiltro Field name
     * @param $operador Comparison operator
     */
    public function adicCampoFiltro($campoFiltro, $operador = 'like', $filtroForm = NULL, $transformadorFiltro = NULL, $logic_operator = Expressao::OPERATOR_E)
    {
        $this->camposFiltro[] = $campoFiltro;
        $this->operadores[] = $operador;
        $this->operadores_logico[] = $logic_operator;
        $this->filtrosForm[] = isset($filtroForm) ? $filtroForm : $campoFiltro;
        $this->TransformadoresFiltro[] = $transformadorFiltro;
    }
    
    /**
     * metodo defCriterio()
     * Define the criteria
     */
    public function defCriterio($criterio)
    {
        $this->criteria = $criterio;
    }

    /**
     * Define a callback metodo to transform objects
     * before load them into datagrid
     */
    public function defTransformador($callback)
    {
        $this->transformCallback = $callback;
    }
    
    /**
     * Define a callback metodo to transform objects
     * before load them into datagrid
     */
    public function defAposCarregarCallback($callback)
    {
        $this->aposCarregarCallback = $callback;
    }
    
    /**
     * Register the filter in the session
     */
    public function aoBuscar( $param = null )
    {
        // get the search form data
        $dados = $this->form->obtDados();
        
        if ($this->filtrosForm)
        {
            foreach ($this->filtrosForm as $chaveFiltro => $filtroForm)
            {
                $operador       = isset($this->operadores[$chaveFiltro]) ? $this->operadores[$chaveFiltro] : 'like';
                $campoFiltro    = isset($this->camposFiltro[$chaveFiltro]) ? $this->camposFiltro[$chaveFiltro] : $filtroForm;
                $funcaoFiltro   = isset($this->TransformadoresFiltro[$chaveFiltro]) ? $this->TransformadoresFiltro[$chaveFiltro] : null;
                
                // check if the user has filled the form
                if (!empty($dados->{$filtroForm}) OR (isset($dados->{$filtroForm}) AND $dados->{$filtroForm} == '0'))
                {
                    // $this->TransformadoresFiltro
                    if ($funcaoFiltro  )
                    {
                        $fieldData = $funcaoFiltro  ($dados->{$filtroForm});
                    }
                    else
                    {
                        $fieldData = $dados->{$filtroForm};
                    }
                    
                    // creates a filter using what the user has typed
                    if (stristr($operador, 'like'))
                    {
                        $filter = new Filtro($campoFiltro, $operador, "%{$fieldData}%");
                    }
                    else
                    {
                        $filter = new Filtro($campoFiltro, $operador, $fieldData);
                    }
                    
                    // stores the filter in the session
                    Sessao::defValor($this->registroAtivo.'_filter', $filter); // BC compatibility
                    Sessao::defValor($this->registroAtivo.'_filter_'.$filtroForm, $filter);
                    Sessao::defValor($this->registroAtivo.'_'.$filtroForm, $dados->{$filtroForm});
                }
                else
                {
                    Sessao::defValor($this->registroAtivo.'_filter', NULL); // BC compatibility
                    Sessao::defValor($this->registroAtivo.'_filter_'.$filtroForm, NULL);
                    Sessao::defValor($this->registroAtivo.'_'.$filtroForm, '');
                }
            }
        }
        
        Sessao::defValor($this->registroAtivo.'_filter_data', $dados);
        Sessao::defValor(get_class($this).'_filter_data', $dados);
        
        // fill the form with data again
        $this->form->defDados($dados);
        
        if (isset($param['estatico']) && ($param['estatico'] == '1') )
        {
            NucleoAplicativo::carregaPagina(get_class($this), 'aoCarregar', ['offset'=> 0, 'first_page'=> 1] );
        }
        else
        {
            $this->aoCarregar( ['offset'=>0, 'first_page'=>1] );
        }
    }
    
    /**
     * limpa Filters
     */
    public function limpaFiltros()
    {
        Sessao::defValor($this->registroAtivo.'_filter_data', null);
        Sessao::defValor(get_class($this).'_filter_data', null);
        $this->form->limpa();
        
        if ($this->filtrosForm)
        {
            foreach ($this->filtrosForm as $chaveFiltro => $filtroForm)
            {
                Sessao::defValor($this->registroAtivo.'_filter', NULL); // BC compatibility
                Sessao::defValor($this->registroAtivo.'_filter_'.$filtroForm, NULL);
                Sessao::defValor($this->registroAtivo.'_'.$filtroForm, '');
            }
        }
    }
    
    /**
     * Load the datagrid with the database objects
     */
    public function aoCarregar($param = NULL)
    {
        if (!isset($this->gradedados))
        {
            return;
        }
        
        try
        {
            if (empty($this->bancodados))
            {
                throw new Exception('O Banco de dados não foi definido. Você deve chamar defBancodados() no Construtor');
            }
            
            if (empty($this->registroAtivo))
            {
                throw new Exception('O Registro ativo não foi definido. Você deve chamar defRegistroAtivo() no Construtor');
            }
            
            // open a transaction with database
            Transacao::abre($this->bancodados);
            
            // instancia um repositório
            $repositorio = new Repositorio($this->registroAtivo);
            $limite = isset($this->limite) ? ( $this->limite > 0 ? $this->limite : NULL) : 10;
            
            // creates a criteria
            $criterio = isset($this->criteria) ? clone $this->criteria : new Criterio;
            if ($this->ordem)
            {
                $criterio->defPropriedade('order',     $this->ordem);
                $criterio->defPropriedade('direction', $this->direcao);
            }
            

            if (is_array($this->ordemComandos) && !empty($param['order']) && !empty($this->ordemComandos[$param['order']]))
            {
                $param['order'] = $this->ordemComandos[$param['order']];
            }
            
            $criterio->defPropriedades($param); // order, offset
            $criterio->defPropriedade('limite', $limite);
            
            if ($this->filtrosForm)
            {
                foreach ($this->filtrosForm as $chaveFiltro => $campoFiltro)
                {
                    $logic_operator = isset($this->operadores_logico[$chaveFiltro]) ? $this->operadores_logico[$chaveFiltro] : Expressao::OPERATOR_E;
                    
                    if (Sessao::obtValor($this->registroAtivo.'_filter_'.$campoFiltro))
                    {
                        // add the filter stored in the session to the criteria
                        $criterio->adic(Sessao::obtValor($this->registroAtivo.'_filter_'.$campoFiltro), $logic_operator);
                    }
                }
            }
            
            // load the objects according to criteria
            $objetos = $repositorio->carrega($criterio, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objetos, $param);
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
            
            // reset the criteria for record count
            $criterio->redefPropriedades();
            $count = $repositorio->conta($criterio);
            
            if (isset($this->pageNavigation))
            {
                $this->pageNavigation->defContador($count); // count of records
                $this->pageNavigation->defPropriedades($param); // order, page
                $this->pageNavigation->defLimite($limite); // limite
            }
            
            if (is_callable($this->aposCarregarCallback))
            {
                $informacao = ['count' => $count];
                call_user_func($this->aposCarregarCallback, $this->gradedados, $informacao);
            }
            
            // close the transaction
            Transacao::fecha();
            $this->carregado = true;
            
            return $objetos;
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
     * Ask before deletion
     */
    public function aoApagar($param)
    {
        // define the delete action
        $acao = new Acao(array($this, 'Apaga'));
        $acao->defParametros($param); // pass the key parameter ahead
        
        // exibes a dialog to the user
        new Pergunta('Você deseja realmente apagar ?', $acao);
    }
    
    /**
     * Apaga a record
     */
    public function Apaga($param)
    {
        try
        {
            // get the parameter $chave
            $chave=$param['key'];
            // open a transaction with database
            Transacao::abre($this->bancodados);
            
            $classe = $this->registroAtivo;
            
            // instantiates object
            $objeto = new $classe($chave, FALSE);
            
            // deletes the object from the database
            $objeto->apaga();
            
            // close the transaction
            Transacao::fecha();
            
            // reload the listing
            $this->aoCarregar( $param );
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
     * metodo exibe()
     * Shows the page
     */
    public function exibe()
    {
        // check if the datagrid is already carregado
        if (!$this->carregado AND (!isset($_GET['metodo']) OR !(in_array($_GET['metodo'],  array('aoCarregar', 'aoBuscar')))) )
        {
            if (func_num_args() > 0)
            {
                $this->aoCarregar( func_get_arg(0) );
            }
            else
            {
                $this->aoCarregar();
            }
        }
        parent::exibe();
    }
}
