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
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Bugigangas\Gradedados\GradeDadosAcao;
use Estrutura\Bugigangas\Recipiente\Tabela;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Janela;
use Estrutura\Embrulho\EmbrulhoBootstrapGradedados;
use Estrutura\Nucleo\ConfigAplicativo;
use Estrutura\Sessao\Sessao;
use Exception;
use StdClass;

/**
 * Standard Page controller for Seek buttons
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BuscaPadrao extends Janela
{
    private $form;      // search form
    private $datagrid;  // listing
    private $pageNavigation;
    private $paiForm;
    private $loaded;
    private $items;
    
    /**
     * Constructor Method
     * Creates the page, the search form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        parent::defTitulo( 'Search record');
        parent::defTamanho(0.7, null);
        parent::removeEspacamento();
        
        // creates a new form
        $this->form = new Form('form_standard_seek');
        // creates a new table
        $tabela = new Tabela;
        $tabela->{'width'} = '100%';
        // adds the table into the form
        $this->form->adic($tabela);
        
        // create the form fields
        $display_field= new Entrada('display_field');
        $display_field->setSize('90%');
        
        // keeps the field's value
        $display_field->setValue( Sessao::obtValor('tstandardseek_display_value') );
        
        // create the action button
        $find_button = new Botao('busca');
        // define the button action
        $find_action = new Acao(array($this, 'onSearch'));
        $find_action->defParametro('register_state', 'false');
        $find_button->defAcao($find_action, 'Search');
        $find_button->setImage('fa:search blue');
        
        // add a row for the filter field
        $tabela->adicRowSet( new Rotulo(_t('Search').': '), $display_field, $find_button);
        
        // define wich are the form fields
        $this->form->setFields(array($display_field, $find_button));
        
        // creates a new datagrid
        $this->gradedados = new EmbrulhoBootstrapGradedados(new Gradedados);
        $this->gradedados->{'style'} = 'width: 100%';
        
        // creates the paginator
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->defAcao(new Acao(array($this, 'aoRecarregar')));
        $this->pageNavigation->setWidth($this->gradedados->getWidth());
        
        $painel = new TPanelGroup($this->form);
        $painel->{'style'} = 'width: 100%;margin-bottom:0;border-radius:0';
        $painel->adic($this->gradedados);
        $painel->adicFooter($this->pageNavigation);
        
        // add the container to the page
        parent::add($painel);
    }
    
    /**
     * Render datagrid
     */
    public function render()
    {
        // create two datagrid columns
        $id      = new ColunaGradedados('id',            'Id',    'center', '50');
        $display = new ColunaGradedados('display_field', Sessao::obtValor('standard_seek_label'), 'left');
        
        // add the columns to the datagrid
        $this->gradedados->adicColumn($id);
        $this->gradedados->adicColumn($display);
        
        // order by PK
        $order_id = new Acao( [$this, 'aoRecarregar'] );
        $order_id->defParametro('order', 'id');
        $id->defAcao($order_id);
        
        // order by Display field
        $order_display = new Acao( [$this, 'aoRecarregar'] );
        $order_display->defParametro('order', 'display_field');
        $display->defAcao($order_display);
        
        // create a datagrid action
        $acao1 = new GradeDadosAcao(array($this, 'onSelect'));
        $acao1->defRotulo('');
        $acao1->defImagem('far:hand-pointer green');
        $acao1->defUsaBotao(TRUE);
        $acao1->defClasseBotao('nopadding');
        $acao1->defCampo('id');
        
        // add the actions to the datagrid
        $this->gradedados->adicAction($acao1);
        
        // create the datagrid model
        $this->gradedados->createModel();
    }
    
    /**
     * Fill datagrid
     */
    public function fill()
    {
        $this->gradedados->clear();
        if ($this->items)
        {
            foreach ($this->items as $item)
            {
                $this->gradedados->adicItem($item);
            }
        }
    }
    
    /**
     * Search datagrid
     */
    public function onSearch()
    {
        // get the form data
        $data = $this->form->obtDados();
        
        // check if the user has filled the form
        if (isset($data-> display_field) AND ($data-> display_field))
        {
            $operator = Sessao::obtValor('standard_seek_operator');
            
            // creates a filter using the form content
            $display_field = Sessao::obtValor('standard_seek_display_field');
            $filter = new TFilter($display_field, $operator, "%{$data-> display_field}%");
            
            // store the filter in section
            Sessao::defValor('tstandardseek_filter',        $filter);
            Sessao::defValor('tstandardseek_display_value', $data-> display_field);
        }
        else
        {
            Sessao::defValor('tstandardseek_filter',        NULL);
            Sessao::defValor('tstandardseek_display_value', '');
        }
        
        Sessao::defValor('tstandardseek_filter_data', $data);
        
        // set the data back to the form
        $this->form->defDados($data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->aoRecarregar($param);
    }
    
    /**
     * Load the datagrid with objects
     */
    public function aoRecarregar($param = NULL)
    {
        try
        {
            $model    = Sessao::obtValor('standard_seek_model');
            $bancodados = Sessao::obtValor('standard_seek_database');
            $display_field = Sessao::obtValor('standard_seek_display_field');
            
            $pk   = constant("{$model}::PRIMARYKEY");
            
            // begins the transaction with database
            Transacao::abre($bancodados);
            
            // creates a repository for the model
            $repositorio = new Repositorio($model);
            $limit = 10;
            
            // creates a criteria
            if (Sessao::obtValor('standard_seek_criteria'))
            {
                $criterio = clone Sessao::obtValor('standard_seek_criteria');
            }
            else
            {
                $criterio = new Criterio;
                
                // default order
                if (empty($param['order']))
                {
                    $param['order'] = $pk;
                    $param['direction'] = 'asc';
                }
            }
            
            if (!empty($param['order']) AND $param['order'] == 'display_field')
            {
                $param['order'] = $display_field;
            }
            
            $criterio->setProperties($param); // order, offset
            $criterio->setProperty('limit', $limit);
            
            if (Sessao::obtValor('tstandardseek_filter'))
            {
                // add the filter to the criteria
                $criterio->adic(Sessao::obtValor('tstandardseek_filter'));
            }
            
            // load all objects according with the criteria
            $objetos = $repositorio->load($criterio, FALSE);
            if ($objetos)
            {
                foreach ($objetos as $objeto)
                {
                    $item = $objeto;
                    $item->{'id'} = $objeto->$pk;
                    
                    if (!empty(Sessao::obtValor('standard_seek_mask')))
                    {
                        $item->{'display_field'} = $objeto->render(Sessao::obtValor('standard_seek_mask'));
                    }
                    else
                    {
                        $item->{'display_field'} = $objeto->$display_field;
                    }
                    
                    $this->items[] = $item;
                }
            }
            
            // clear the crieteria to count the records
            $criterio->resetProperties();
            $count= $repositorio->count($criterio);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // closes the transaction
            Transacao::fecha();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // exibes the exception genearated message
            new TMessage('error', $e->getMessage());
            // rollback all the database operations 
            Transacao::rollback();
        }
    }
    
    /**
     * Setup seek parameters
     */
    public function onSetup($param=NULL)
    {
        $ini  = ConfigAplicativo::obt();
        $seed = NOME_APLICATIVO . ( !empty($ini['general']['seed']) ? $ini['general']['seed'] : 's8dkld83kf73kf094' );
        
        if (isset($param['hash']) AND $param['hash'] == md5($seed.$param['database'].$param['model'].$param['display_field']))
        {
            // store the parameters in the section
            Sessao::defValor('tstandardseek_filter', NULL);
            Sessao::defValor('tstandardseek_display_value', NULL);
            Sessao::defValor('standard_seek_receive_key',   $param['receive_key']);
            Sessao::defValor('standard_seek_receive_field', $param['receive_field']);
            Sessao::defValor('standard_seek_display_field', $param['display_field']);
            Sessao::defValor('standard_seek_model',         $param['model']);
            Sessao::defValor('standard_seek_database',      $param['database']);
            Sessao::defValor('standard_seek_parent',        $param['parent']);
            Sessao::defValor('standard_seek_operator',      ($param['operator'] ?? null) );
            Sessao::defValor('standard_seek_mask',          ($param['mask']  ?? null) );
            Sessao::defValor('standard_seek_label',         ($param['label']  ?? null) );
            
            if (isset($param['criteria']) AND $param['criteria'])
            {
                Sessao::defValor('standard_seek_criteria',  unserialize(base64_decode($param['criteria'])));
            }
            $this->aoRecarregar();
        }
    }
    
    /**
     * Send the selected register to parent form
     */
    public static function onSelect($param)
    {
        $key = $param['key'];
        $bancodados      = isset($param['database'])      ? $param['database']      : Sessao::obtValor('standard_seek_database');
        $receive_key   = isset($param['receive_key'])   ? $param['receive_key']   : Sessao::obtValor('standard_seek_receive_key');
        $receive_field = isset($param['receive_field']) ? $param['receive_field'] : Sessao::obtValor('standard_seek_receive_field');
        $display_field = isset($param['display_field']) ? $param['display_field'] : Sessao::obtValor('standard_seek_display_field');
        $pai        = isset($param['parent'])        ? $param['parent']        : Sessao::obtValor('standard_seek_parent');
        $seek_mask     = isset($param['mask'])          ? $param['mask']          : Sessao::obtValor('standard_seek_mask');
        
        try
        {
            Transacao::abre($bancodados);
            
            // load the active record
            $model = isset($param['model']) ? $param['model'] : Sessao::obtValor('standard_seek_model');
            $pk = constant("{$model}::PRIMARYKEY");
            
            // creates a criteria
            if (Sessao::obtValor('standard_seek_criteria'))
            {
                $criterio = clone Sessao::obtValor('standard_seek_criteria');
            }
            else
            {
                $criterio = new Criterio;
            }
            
            $criterio->adic(new Filtro( $pk, '=', $key));
            $criterio->setProperty('limit', 1);
            $repositorio = new Repositorio($model);
            $objetos = $repositorio->carrega($criterio);
            
            if ($objetos)
            {
                $activeRecord = $objetos[0];
            
                $objeto = new StdClass;
                $objeto->$receive_key   = isset($activeRecord->$pk) ? $activeRecord->$pk : '';
                
                if (!empty($seek_mask))
                {
                    $objeto->$receive_field = $activeRecord->render($seek_mask);
                }
                else
                {
                    $objeto->$receive_field = isset($activeRecord->$display_field) ? $activeRecord->$display_field : '';
                }
                
                Transacao::fecha();
                
                Form::enviaDados($pai, $objeto);
                parent::fechaJanela(); // closes the window
            }
            else
            {
                throw new Exception;
            }
        }
        catch (Exception $e) // in case of exception
        {
            // clear fields
            $objeto = new StdClass;
            $objeto->$receive_key   = '';
            $objeto->$receive_field = '';
            Form::enviaDados($pai, $objeto);
            
            // undo all pending operations
            Transacao::desfaz();
        }
    }
    
    /**
     * Show page
     */
    public function exibe()
    {
        parent::defEstaEmbrulado(true);
        $this->executa();
        $this->renderiza();
        $this->fill();
        parent::exibe();
    }
}
