<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Script;
use Estrutura\Nucleo\ConfigAplicativo;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * Multi Search Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Matheus Agnes Dias
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiBusca extends Seleciona implements InterfaceBugiganga
{
    protected $id;
    protected $items;
    protected $tamanho;
    protected $altura;
    protected $comprimentoMin;
    protected $tamanhoMax;
    protected $editavel;
    protected $mudaAcao;
    protected $mudaFuncao;
    protected $permiteLimpar;
    protected $permiteBusca ;
    protected $separador;
    protected $valor;
    
    /**
     * Class Constructor
     * @param  $nome Widget's name
     */
    public function __construct($nome)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        $this->id   = 'tmultisearch_'.mt_rand(1000000000, 1999999999);

        $this->altura = 100;
        $this->comprimentoMin = 3;
        $this->tamanhoMax = 0;
        $this->permiteLimpar = TRUE;
        $this->permiteBusca  = TRUE;
        
        parent::defOpcaoPadrao(FALSE);
        $this->tag->{'component'} = 'multisearch';
        $this->tag->{'widget'} = 'tmultisearch';
    }
    
    /**
     * Disable multiplo selection
     */
    public function desabilitaMultiplo()
    {
        unset($this->tag->{'multiplo'});
    }
    
    /**
     * Disable clear
     */
    public function desabilitaLimpar()
    {
        $this->permiteLimpar = FALSE;
    }
    
    /**
     * Disable search
     */
    public function desabilitaBusca()
    {
        $this->permiteBusca  = FALSE;
    }
    
    /**
     * Define the widget's size
     * @param  $largura   Widget's width
     * @param  $altura  Widget's height
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->tamanho   = $largura;
        if ($altura) {
            $this->altura = $altura;
        }
    }

    /**
     * Returns the size
     * @return array(width, height)
     */
    public function obtTamanho()
    {
        return array( $this->tamanho, $this->altura );
    }
    
    /**
     * Define the minimum length for search
     */
    public function defComprimentoMin($comprimento) 
    {
        $this->comprimentoMin = $comprimento;
    }

    /**
     * Define the maximum number of items that can be selected
     */
    public function defTamanhoMax($tamanhomax)
    {
        $this->tamanhoMax = $tamanhomax;
        
        if ($tamanhomax == 1) {
            unset($this->altura);
            parent::defOpcaoPadrao(TRUE);
        }
    }
    
    /**
     * Define the field's separador
     * @param $sep A string containing the field's separador
     */
    public function setValueSeparator($sep)
    {
        $this->separador = $sep;
    }
    
    /**
     * Define the field's value
     * @param $valor A string containing the field's value
     */
    public function defValor($valor)
    {
        $ini = ConfigAplicativo::obt();
        
        if (isset($ini['general']['compat']) AND $ini['general']['compat'] ==  '4') {
            if ($valor) {
                parent::defValor(array_keys((array)$valor));
            }
        } else {
            parent::defValor($valor);
        }
    }
    
    /**
     * Return the post data
     */
    public function getPostData()
    {
        $ini = ConfigAplicativo::obt();
        
        if (isset($_POST[$this->nome])) {
            $valores = $_POST[$this->nome];
            
            if (isset($ini['general']['compat']) AND $ini['general']['compat'] ==  '4') {
                $return = [];
                if (is_array($valores)) {
                    foreach ($valores as $item) {
                        $return[$item] = $this->items[$item];
                    }
                }
                return $return;
            } else {
                if (empty($this->separador)) {
                    return $valores;
                } else {
                    return implode($this->separador, $valores);
                }
            }
        } else {
            return '';
        }
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function enableField($nome_form, $campo)
    {
        Script::cria( " tmultisearch_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function disableField($nome_form, $campo)
    {
        Script::cria( " tmultisearch_disable_field('{$nome_form}', '{$campo}'); " );
    }

    /**
     * Clear the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function clearField($nome_form, $campo)
    {
        Script::cria( " tmultisearch_clear_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Shows the widget
     */
    public function exibe()
    {
        // define the tag properties
        $this->tag->{'id'}    = $this->id; // tag id
        
        if (empty($this->tag->{'name'})) // may be defined by child classes
        {
            $this->tag->{'name'}  = $this->nome.'[]'; // tag name
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
        
        $multiplo = $this->tamanhoMax == 1 ? 'false' : 'true';
        $palavra_busca = !empty($this->obtPropriedade('placeholder')) ? $this->obtPropriedade('placeholder') : 'Busca';
        $muda_acao = 'function() {}';
        $permitelimpar  = $this->permiteLimpar  ? 'true' : 'false';
        $permitebusca = $this->permiteBuscar ? '1' : 'Infinity';
        
        if (isset($this->mudaAcao)) {
            if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form) {
                throw new Exception(NucleoTradutor::traduz('Você deve passar o &1 (&2) como parâmetro para &3', __CLASS__, $this->nome, 'Form::defCampos()'));
            }
            
            $string_acao = $this->mudaAcao->serialize(FALSE);
            $muda_acao = "function() { __adianti_post_lookup('{$this->nomeForm}', '{$string_acao}', '{$this->id}', 'callback'); }";
            $this->setProperty('changeaction', "__adianti_post_lookup('{$this->nomeForm}', '{$string_acao}', '{$this->id}', 'callback')");
        } else if (isset($this->mudaFuncao)) {
            $muda_acao = "function() { $this->mudaFuncao }";
            $this->defPropriedade('changeaction', $this->mudaFuncao, FALSE);
        }
        
        // shows the component
        parent::renderizaItens( false );
        $this->tag->exibe();
        
        Script::cria(" tmultisearch_start( '{$this->id}', '{$this->comprimentoMin}', '{$this->tamanhoMax}', '{$palavra_busca}', $multiplo, '{$tamanho}', '{$this->altura}px', {$permitelimpar}, {$permitebusca}, $muda_acao ); ");
        
        if (!$this->editavel) {
            Script::cria(" tmultisearch_disable_field( '{$this->nomeForm}', '{$this->nome}'); ");
        }
    }
}
