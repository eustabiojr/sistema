<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Filtro;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
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
use Estrutura\Registro\Sessao;
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
    private $gradedados;  // listing
    private $navegacaoPagina;
    private $paiForm;
    private $carregado;
    private $itens;
    
    /**
     * Constructor Method
     * Creates the page, the search form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        parent::defTitulo( 'Busca registro');
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
        $campo_exibe= new Entrada('campo_exibe');
        $campo_exibe->defTamanho('90%');
        
        // keeps the field's value
        $campo_exibe->defValor( Sessao::obtValor('tstandardseek_exibe_value') );
        
        // create the action button
        $botao_localiza = new Botao('busca');
        // define the button action
        $acao_localiza = new Acao(array($this, 'aoBuscar'));
        $acao_localiza->defParametro('register_state', 'false');
        $botao_localiza->defAcao($acao_localiza, 'Search');
        $botao_localiza->defImagem('fa:search blue');
        
        // add a row for the filter field
        $tabela->adicGrupoLinha( new Rotulo(_t('Busca').': '), $campo_exibe, $botao_localiza);
        
        // define wich are the form fields
        $this->form->defCampos(array($campo_exibe, $botao_localiza));
        
        // creates a new gradedados
        $this->gradedados = new EmbrulhoBootstrapGradedados(new Gradedados);
        $this->gradedados->{'style'} = 'width: 100%';
        
        // creates the paginator
        $this->navegacaoPagina = new NavegacaoPagina;
        $this->navegacaoPagina->defAcao(new Acao(array($this, 'aoRecarregar')));
        $this->navegacaoPagina->setWidth($this->gradedados->getWidth());
        
        $painel = new TPanelGroup($this->form);
        $painel->{'style'} = 'width: 100%;margin-bottom:0;border-radius:0';
        $painel->adic($this->gradedados);
        $painel->adicRodape($this->navegacaoPagina);
        
        // add the container to the page
        parent::adic($painel);
    }
    
    /**
     * Render gradedados
     */
    public function renderiza()
    {
        // create two gradedados columns
        $id    = new ColunaGradedados('id',            'Id',    'center', '50');
        $exibe = new ColunaGradedados('campo_exibe', Sessao::obtValor('standard_seek_label'), 'left');
        
        // add the columns to the gradedados
        $this->gradedados->adicColuna($id);
        $this->gradedados->adicColuna($exibe);
        
        // order by PK
        $id_ordem = new Acao( [$this, 'aoRecarregar'] );
        $id_ordem->defParametro('order', 'id');
        $id->defAcao($id_ordem);
        
        // order by Display field
        $order_exibe = new Acao( [$this, 'aoRecarregar'] );
        $order_exibe->defParametro('order', 'campo_exibe');
        $exibe->defAcao($order_exibe);
        
        // create a gradedados action
        $acao1 = new GradeDadosAcao(array($this, 'onSelect'));
        $acao1->defRotulo('');
        $acao1->defImagem('far:hand-pointer green');
        $acao1->defUsaBotao(TRUE);
        $acao1->defClasseBotao('nopadding');
        $acao1->defCampo('id');
        
        // add the actions to the gradedados
        $this->gradedados->adicAcao($acao1);
        
        // create the gradedados model
        $this->gradedados->criaModelo();
    }
    
    /**
     * Fill gradedados
     */
    public function preenche()
    {
        $this->gradedados->limpa();
        if ($this->itens)
        {
            foreach ($this->itens as $item)
            {
                $this->gradedados->adicItem($item);
            }
        }
    }
    
    /**
     * Search gradedados
     */
    public function aoBuscar()
    {
        // get the form data
        $dados = $this->form->obtDados();
        
        // check if the user has filled the form
        if (isset($dados->campo_exibe) AND ($dados->campo_exibe))
        {
            $operador = Sessao::obtValor('standard_seek_operator');
            
            // creates a filter using the form content
            $campo_exibe = Sessao::obtValor('standard_seek_campo_exibe');
            $filtro = new Filtro($campo_exibe, $operador, "%{$dados->campo_exibe}%");
            
            // store the filter in section
            Sessao::defValor('tstandardseek_filter',        $filtro);
            Sessao::defValor('tstandardseek_exibe_value', $dados->campo_exibe);
        }
        else
        {
            Sessao::defValor('tstandardseek_filter',        NULL);
            Sessao::defValor('tstandardseek_exibe_value', '');
        }
        
        Sessao::defValor('tstandardseek_filter_data', $dados);
        
        // set the data back to the form
        $this->form->defDados($dados);
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->aoRecarregar($param);
    }
    
    /**
     * Load the gradedados with objects
     */
    public function aoRecarregar($param = NULL)
    {
        try
        {
            $modelo      = Sessao::obtValor('standard_seek_model');
            $bancodados  = Sessao::obtValor('standard_seek_database');
            $campo_exibe = Sessao::obtValor('standard_seek_campo_exibe');
            
            $pk   = constant("{$modelo}::PRIMARYKEY");
            
            // begins the transaction with database
            Transacao::abre($bancodados);
            
            // creates a repository for the model
            $repositorio = new Repositorio($modelo);
            $limite = 10;
            
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
            
            if (!empty($param['order']) AND $param['order'] == 'campo_exibe')
            {
                $param['order'] = $campo_exibe;
            }
            
            $criterio->defPropriedades($param); // order, offset
            $criterio->defPropriedade('limit', $limite);
            
            if (Sessao::obtValor('tstandardseek_filter'))
            {
                // add the filter to the criteria
                $criterio->adic(Sessao::obtValor('tstandardseek_filter'));
            }
            
            // load all objects according with the criteria
            $objetos = $repositorio->carrega($criterio, FALSE);
            if ($objetos)
            {
                foreach ($objetos as $objeto)
                {
                    $item = $objeto;
                    $item->{'id'} = $objeto->$pk;
                    
                    if (!empty(Sessao::obtValor('standard_busca_mascara')))
                    {
                        $item->{'campo_exibe'} = $objeto->render(Sessao::obtValor('standard_busca_mascara'));
                    }
                    else
                    {
                        $item->{'campo_exibe'} = $objeto->$campo_exibe;
                    }
                    
                    $this->itens[] = $item;
                }
            }
            
            // clear the crieteria to count the records
            $criterio->redefPropriedades();
            $contador = $repositorio->conta($criterio);
            
            $this->navegacaoPagina->defContador($contador); // count of records
            $this->navegacaoPagina->defPropriedades($param); // order, page
            $this->navegacaoPagina->defLimite($limite); // limit
            
            // closes the transaction
            Transacao::fecha();
            $this->carregado = true;
        }
        catch (Exception $e) // in case of exception
        {
            // exibes the exception genearated message
            new Mensagem('erro', $e->getMessage());
            // desfaz all the database operations 
            Transacao::desfaz();
        }
    }
    
    /**
     * Setup seek parameters
     */
    public function onSetup($param=NULL)
    {
        $ini  = ConfigAplicativo::obt();
        $seed = NOME_APLICATIVO . ( !empty($ini['geral']['semente']) ? $ini['geral']['semente'] : 's8dkld83kf73kf094' );
        
        if (isset($param['hash']) AND $param['hash'] == md5($seed.$param['bancodados'].$param['modelo'].$param['campo_exibe']))
        {
            // store the parameters in the section
            Sessao::defValor('tstandardseek_filter', NULL);
            Sessao::defValor('tstandardseek_exibe_value', NULL);
            Sessao::defValor('standard_seek_chave_receptor',   $param['chave_receptor']);
            Sessao::defValor('standard_seek_campo_receptor', $param['campo_receptor']);
            Sessao::defValor('standard_seek_campo_exibe', $param['campo_exibe']);
            Sessao::defValor('standard_seek_model',         $param['modelo']);
            Sessao::defValor('standard_seek_database',      $param['bancodados']);
            Sessao::defValor('standard_seek_parent',        $param['pai']);
            Sessao::defValor('standard_seek_operator',      ($param['operator'] ?? null) );
            Sessao::defValor('standard_busca_mascara',          ($param['mascara']  ?? null) );
            Sessao::defValor('standard_seek_label',         ($param['rotulo']  ?? null) );
            
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
    public static function aoSelecionar($param)
    {
        $chave = $param['key'];
        $bancodados      = isset($param['bancodados'])      ? $param['bancodados']      : Sessao::obtValor('standard_seek_database');
        $chave_receptor   = isset($param['chave_receptor'])   ? $param['chave_receptor']   : Sessao::obtValor('standard_seek_chave_receptor');
        $campo_receptor = isset($param['campo_receptor']) ? $param['campo_receptor'] : Sessao::obtValor('standard_seek_campo_receptor');
        $campo_exibe = isset($param['campo_exibe']) ? $param['campo_exibe'] : Sessao::obtValor('standard_seek_campo_exibe');
        $pai        = isset($param['pai'])        ? $param['pai']        : Sessao::obtValor('standard_seek_parent');
        $busca_mascara     = isset($param['mascara'])          ? $param['mascara']          : Sessao::obtValor('standard_busca_mascara');
        
        try
        {
            Transacao::abre($bancodados);
            
            // load the active record
            $modelo = isset($param['modelo']) ? $param['modelo'] : Sessao::obtValor('standard_seek_model');
            $pk = constant("{$modelo}::PRIMARYKEY");
            
            // creates a criteria
            if (Sessao::obtValor('standard_seek_criteria'))
            {
                $criterio = clone Sessao::obtValor('standard_seek_criteria');
            }
            else
            {
                $criterio = new Criterio;
            }
            
            $criterio->adic(new Filtro( $pk, '=', $chave));
            $criterio->defPropriedade('limit', 1);
            $repositorio = new Repositorio($modelo);
            $objetos = $repositorio->carrega($criterio);
            
            if ($objetos)
            {
                $activeRecord = $objetos[0];
            
                $objeto = new StdClass;
                $objeto->$chave_receptor   = isset($activeRecord->$pk) ? $activeRecord->$pk : '';
                
                if (!empty($busca_mascara))
                {
                    $objeto->$campo_receptor = $activeRecord->render($busca_mascara);
                }
                else
                {
                    $objeto->$campo_receptor = isset($activeRecord->$campo_exibe) ? $activeRecord->$campo_exibe : '';
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
            $objeto->$chave_receptor   = '';
            $objeto->$campo_receptor = '';
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
        parent::defEstaEmbalado(true);
        $this->executa();
        $this->renderiza();
        $this->preenche();
        parent::exibe();
    }
}
