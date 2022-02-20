<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Form\Campo;
use Estrutura\Bugigangas\Form\InterfaceBugiganga;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * Spinner Widget (also known as spin button)
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Giratorio extends Campo implements InterfaceBugiganga
{
    private $min;
    private $max;
    private $degrau;
    private $acaoSair;
    private $funcaoSair;
    protected $id;
    protected $formName;
    protected $valor;
    
    /**
     * Class Constructor
     * @param $nome Name of the widget
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id = 'tspinner_'.mt_rand(1000000000, 1999999999);
        $this->tag->{'widget'} = 'tspinner';
    }
    
    /**
     * Define the field's range
     * @param $min Minimal value
     * @param $max Maximal value
     * @param $degrau Step value
     */
    public function defIntervalor($min, $max, $degrau)
    {
        $this->min = $min;
        $this->max = $max;
        $this->degrau = $degrau;
        
        if ($degrau == 0)
        {
            throw new Exception(NucleoTradutor::traduz('Parâmetro inválido (&1) em &2', $degrau, 'defIntervalor'));
            
        }
        
        if (is_int($degrau) AND $this->obtValor() % $degrau !== 0)
        {
            parent::defValor($min);
        }
    }
    
    /**
     * Define the action to be executed when the user leaves the form field
     * @param $acao TAction object
     */
    function defAcaoSair(Acao $acao)
    {
        if ($acao->ehEstatico())
        {
            $this->acaoSair = $acao;
        }
        else
        {
            $string_acao = $acao->paraString();
            throw new Exception(NucleoTradutor::traduz('A ação (&1) deve ser estática para ser usado em &2', $string_acao, __METHOD__));
        }
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tspinner_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tspinner_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Set exit function
     */
    public function defFuncaoSair($funcao) 
    {
        $this->funcaoSair = $funcao;
    }
    
    /**
     * Shows the widget at the screen
     */
    public function exibe()
    {
        // define the tag properties
        $this->tag->{'name'}  = $this->nome;    // TAG name
        $this->tag->{'value'} = $this->valor;   // TAG value
        $this->tag->{'type'}  = 'text';         // input type
        $this->tag->{'data-min'} = $this->min;
        $this->tag->{'data-max'} = $this->max;
        $this->tag->{'data-step'} = $this->degrau;
        
        if ($this->degrau > 0 and $this->degrau < 1)
        {
            $this->tag->{'data-rule'} = 'currency';
        }
        
        $this->defPropriedade('style', "text-align:right", false); //aggregate style info
        
        if (strstr($this->tamanho, '%') !== FALSE)
        {
            $this->defPropriedade('style', "width:{$this->tamanho};", false); //aggregate style info
            $this->defPropriedade('relwidth', "{$this->tamanho}", false); //aggregate style info
        }
        else
        {
            $this->defPropriedade('style', "width:{$this->tamanho}px;", false); //aggregate style info
        }
        
        if ($this->id)
        {
            $this->tag->{'id'}  = $this->id;
        }
        
        if (isset($this->acaoSair))
        {
            if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form)
            {
                throw new Exception(NucleoTradutor::traduz('Você deve passar o &1 (&2) como parâmetro para &3', __CLASS__, $this->nome, 'Form::defCampos()') );
            }
            $string_acao = $this->acaoSair->serialize(FALSE);
            $this->defPropriedade('exitaction', "__adianti_post_lookup('{$this->nomeForm}', '{$string_acao}', '{$this->id}', 'callback')");
        }
        
        $acao_sair = "function() {}";
        if (isset($this->funcaoSair))
        {
            $acao_sair = "function() { {$this->funcaoSair} }";
        }
        
        if (!parent::obtEditavel())
        {
            $this->tag->{'tabindex'} = '-1';
        }
        $this->tag->exibe();
        Script::cria(" tspinner_start( '#{$this->id}', $acao_sair); ");
        
        // verify if the widget is non-editable
        if (!parent::obtEditavel())
        {
            self::desabilitaCampo($this->nomeForm, $this->nome);
        }
    }
    
    /**
     * Set the value
     */
    public function defValor($valor)
    {
        parent::defValor( (float) $valor);
    }
}
