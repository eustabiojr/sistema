<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Form\MultiBusca;
use Estrutura\Nucleo\ConfigAplicativo;
use Exception;

/**
 * Database Multisearch Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage wrapper
 * @author     Pablo Dall'Oglio
 * @author     Matheus Agnes Dias
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiBuscaBD extends MultiBusca
{
    protected $id;
    protected $initialItems;
    protected $itens;
    protected $tamanho;
    protected $altura;
    protected $tamnhoMin;
    protected $tamanhoMax;
    protected $bancodados;
    protected $modelo;
    protected $chave;
    protected $coluna;
    protected $operador;
    protected $ordemColuna;
    protected $criterio;
    protected $mascara;
    protected $servico;
    protected $seed;
    protected $editavel;
    protected $mudaFuncao;
    protected $idBusca;
    protected $idTextoBusca;
    
    /**
     * Class Constructor
     * @param  $nome     widget's name
     * @param  $bancodados bancodados name
     * @param  $modelo    model class name
     * @param  $chave      table field to be used as key in the combo
     * @param  $valor    table field to be listed in the combo
     * @param  $ordercoluna coluna to order the fields (optional)
     * @param  $criterio criterio (TCriteria object) to filter the model (optional)
     */
    public function __construct($nome, $bancodados, $modelo, $chave, $valor, $ordemColuna = NULL, Criterio $criterio = NULL)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        $this->id   = 'tdbmultisearch_'.mt_rand(1000000000, 1999999999);
        
        $chave   = trim($chave);
        $valor = trim($valor);
        
        if (empty($bancodados))
        {
            throw new Exception("O parâmetro (bancodados) {__CLASS__} é obrigatório");
        }
        
        if (empty($modelo))
        {
            throw new Exception("O parâmetro (bancodados) {__CLASS__} é obrigatório");
        }
        
        if (empty($chave))
        {
            throw new Exception("O parâmetro (bancodados) {__CLASS__} é obrigatório");
        }
        
        if (empty($valor))
        {
            throw new Exception("O parâmetro (bancodados) {__CLASS__} é obrigatório");
        }
        
        $ini = ConfigAplicativo::obt();
        
        $this->bancodados = $bancodados;
        $this->modelo = $modelo;
        $this->chave = $chave;
        $this->coluna = $valor;
        $this->operador = null;
        $this->ordemColuna = isset($ordemColuna) ? $ordemColuna : NULL;
        $this->criterio = $criterio;
        
        if (strpos($valor,',') !== false)
        {
            $colunas = explode(',', $valor);
            $this->mascara = '{'.$colunas[0].'}';
        }
        else
        {
            $this->mascara = '{'.$valor.'}';
        }
        
        $this->servico = 'AdiantiMultiSearchService';
        $this->seed = NOME_APLICATIVO . ( !empty($ini['general']['seed']) ? $ini['general']['seed'] : 's8dkld83kf73kf094' );
        $this->tag->{'widget'} = 'tdbmultisearch';
        $this->idBusca = true;
        $this->idTextoBusca = false;
    }
    
    /**
     * Define the search service
     * @param $servico Search service
     */
    public function setService($servico)
    {
        $this->servico = $servico;
    }
    
    /**
     * Disable search by id
     */
    public function desabilitaIdBusca()
    {
        $this->idBusca = false;
    }
    
    /**
     * Enable Id textual search
     */
    public function enableIdTextualSearch()
    {
        $this->idTextoBusca = true;
    }
    
    /**
     * Define the search operador
     * @param $operador Search operador
     */
    public function defOperador($operador)
    {
        $this->operador = $operador;
    }
    
    /**
     * Define the display mascara
     * @param $mascara Show mascara
     */
    public function defMascara($mascara)
    {
        $this->mascara = $mascara;
    }
    
    /**
     * Define the field's value
     * @param $valores An array the field's values
     */
    public function defValor($valores)
    {
        $valores_originais = $valores;
        $ini = ConfigAplicativo::obt();
        
        if (isset($ini['general']['compat']) AND $ini['general']['compat'] ==  '4')
        {
            if ($valores)
            {
                parent::defValor( $valores );
                parent::adicItens( $valores );
            }
        }
        else
        {
            $itens = [];
            if ($valores)
            {
                if (!empty($this->separador))
                {
                    $valores = explode($this->separador, $valores);
                }
                
                Transacao::abre($this->bancodados);
                foreach ($valores as $valor)
                {
                    if ($valor)
                    {
                        $modelo = $this->modelo;
                        
                        $pk = constant("{$modelo}::PRIMARYKEY");
                        
                        if ($pk === $this->chave) // key is the primary key (default)
                        {
                            // use find because it uses cache
                            $object = $modelo::localiza( $valor );
                        }
                        else // key is an alternative key (uses where->first)
                        {
                            $object = $modelo::where( $this->chave, '=', $valor )->first();
                        }
                        
                        if ($object)
                        {
                            $description = $object->renderiza($this->mascara);
                            $itens[$valor] = $description;
                        }
                    }
                }
                Transacao::fecha();
                
                parent::adicItens( $itens );
            }
            parent::defValor( $valores_originais );
        }
    }
    
    /**
     * Return the post data
     */
    public function getPostData()
    {
        $ini = AdiantiApplicationConfig::get();
        
        if (isset($_POST[$this->name]))
        {
            $valores = $_POST[$this->name];
            
            if (isset($ini['general']['compat']) AND $ini['general']['compat'] ==  '4')
            {
                $return = [];
                if (is_array($valores))
                {
                    TTransaction::open($this->bancodados);
                    foreach ($valores as $valor)
                    {
                        if ($valor)
                        {
                            $modelo = $this->modelo;
                            $pk = constant("{$modelo}::PRIMARYKEY");
                            
                            if ($pk === $this->chave) // key is the primary key (default)
                            {
                                // use find because it uses cache
                                $object = $modelo::find( $valor );
                            }
                            else // key is an alternative key (uses where->first)
                            {
                                $object = $modelo::where( $this->chave, '=', $valor )->first();
                            }
                            
                            if ($object)
                            {
                                $description = $object->render($this->mascara);
                                $return[$valor] = $description;
                            }
                        }
                    }
                }
                return $return;
            }
            else
            {
                if (empty($this->separador))
                {
                    return $valores;
                }
                else
                {
                    return implode($this->separador, $valores);
                }
            }
        }
        else
        {
            return '';
        }
    }
    
    /**
     * Shows the widget
     */
    public function show()
    {
        // define the tag properties
        $this->tag->{'id'}    = $this->id; // tag id
        
        if (empty($this->tag->{'name'})) // may be defined by child classes
        {
            $this->tag->{'name'}  = $this->name.'[]';  // tag name
        }
        
        if (strstr($this->tamanho, '%') !== FALSE)
        {
            $this->setProperty('style', "width:{$this->tamanho};", false); //aggregate style info
            $tamanho  = "{$this->tamanho}";
        }
        else
        {
            $this->setProperty('style', "width:{$this->tamanho}px;", false); //aggregate style info
            $tamanho  = "{$this->tamanho}px";
        }
        
        $multiple = $this->tamanhoMax == 1 ? 'false' : 'true';
        $ordemColuna = isset($this->ordemColuna) ? $this->ordemColuna : $this->coluna;
        $criterio = '';
        if ($this->criterio)
        {
            $criterio = str_replace(array('+', '/'), array('-', '_'), base64_encode(serialize($this->criterio)));
        }
        
        $hash = md5("{$this->seed}{$this->bancodados}{$this->chave}{$this->coluna}{$this->modelo}");
        $length = $this->tamnhoMin;
        
        $class = $this->servico;
        $callback = array($class, 'onSearch');
        $method = $callback[1];
        $id_search_string = $this->idBusca ? '1' : '0';
        $id_text_search = $this->idTextoBusca ? '1' : '0';
        $search_word = !empty($this->getProperty('placeholder'))? $this->getProperty('placeholder') : AdiantiCoreTranslator::translate('Search');
        $url = "engine.php?class={$class}&method={$method}&static=1&bancodados={$this->bancodados}&key={$this->chave}&coluna={$this->coluna}&model={$this->modelo}&ordemColuna={$ordemColuna}&criterio={$criterio}&operador={$this->operador}&mascara={$this->mascara}&idsearch={$id_search_string}&idtextsearch={$id_text_search}&minlength={$length}";
        $change_action = 'function() {}';
        
        if (isset($this->changeAction))
        {
            if (!TForm::getFormByName($this->formName) instanceof TForm)
            {
                throw new Exception(AdiantiCoreTranslator::translate('You must pass the ^1 (^2) as a parameter to ^3', __CLASS__, $this->name, 'TForm::setFields()') );
            }
            
            $string_action = $this->changeAction->serialize(FALSE);
            $change_action = "function() { __adianti_post_lookup('{$this->formName}', '{$string_action}', '{$this->id}', 'callback'); }";
            $this->setProperty('changeaction', "__adianti_post_lookup('{$this->formName}', '{$string_action}', '{$this->id}', 'callback')");
        }
        else if (isset($this->mudaFuncao))
        {
            $change_action = "function() { $this->mudaFuncao }";
            $this->setProperty('changeaction', $this->mudaFuncao, FALSE);
        }
        
        // shows the component
        parent::renderItems( false );
        $this->tag->show();
        
        TScript::create(" tdbmultisearch_start( '{$this->id}', '{$length}', '{$this->tamanhoMax}', '{$search_word}', $multiple, '{$url}', '{$tamanho}', '{$this->altura}px', '{$hash}', {$change_action} ); ");
        
        if (!$this->editavel)
        {
            TScript::create(" tmultisearch_disable_field( '{$this->formName}', '{$this->name}'); ");
        }
    }
}
