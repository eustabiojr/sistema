<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Script;

/**
 * Slider Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Deslizante extends Campo implements InterfaceBugiganga
{
    protected $id;
    private $min;
    private $max;
    private $degrau;
    
    /**
     * Class Constructor
     * @param $name Name of the widget
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->id   = 'tslider_'.mt_rand(1000000000, 1999999999);
        $this->tag->{'widget'} = 'tslider';
    }
    
    /**
     * Define the field's range
     * @param $min Minimal value
     * @param $max Maximal value
     * @param $degrau Step value
     */
    public function defAlcance($min, $max, $degrau)
    {
        $this->min = $min;
        $this->max = $max;
        $this->degrau = $degrau;
        $this->valor = $min;
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tslider_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tslider_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Shows the widget at the screen
     */
    public function exibe()
    {
        // define the tag properties
        $this->tag->{'name'}  = $this->nome;    // TAG name
        $this->tag->{'value'} = $this->valor;   // TAG value
        $this->tag->{'type'}  = 'range';         // input type
        $this->tag->{'min'}   = $this->min;
        $this->tag->{'max'}   = $this->max;
        $this->tag->{'degrau'}  = $this->degrau;
        
        if (strstr($this->tamanho, '%') !== FALSE)
        {
            $this->defPropriedade('style', "width:{$this->tamanho};", false); //aggregate style info
        }
        else
        {
            $this->defPropriedade('style', "width:{$this->tamanho}px;", false); //aggregate style info
        }
        
        if ($this->id)
        {
            $this->tag->{'id'} = $this->id;
        }
        
        $this->tag->{'readonly'} = "1";
        $this->tag->exibe();
        
        Script::cria(" tslider_start( '#{$this->id}'); ");
        
        if (!parent::obtEditavel())
        {
            self::desabilitaCampo($this->nomeForm, $this->nome);
        }
    }
}
