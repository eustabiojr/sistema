<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use DateTime;
use Estrutura\Bugigangas\Base\Script;

/**
 * DateTimePicker Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DataTempo extends Entrada implements InterfaceBugiganga
{
    private $mascara;
    private $mascarabd;
    protected $id;
    protected $tamanho;
    protected $valor;
    protected $opcoes;
    protected $substituiNoPost;
    
    /**
     * Class Constructor
     * @param $nome Name of the widget
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id   = 'tdatetime_' . mt_rand(1000000000, 1999999999);
        $this->mascara = 'yyyy-mm-dd hh:ii';
        $this->mascarabd = null;
        $this->opcoes = [];
        $this->substituiNoPost = FALSE;
        
        $this->setOption('fontAwesome', true);
        
        $mascaranova = $this->mascara;
        $mascaranova = str_replace('dd',   '99',   $mascaranova);
        $mascaranova = str_replace('hh',   '99',   $mascaranova);
        $mascaranova = str_replace('ii',   '99',   $mascaranova);
        $mascaranova = str_replace('mm',   '99',   $mascaranova);
        $mascaranova = str_replace('yyyy', '9999', $mascaranova);
        parent::defMascara($mascaranova);
        $this->tag->{'widget'} = 'tdatetime';
    }
    
    /**
     * Store the valor inside the object
     */
    public function defValor($valor)
    {
        $valor = str_replace('T', ' ', $valor);
        if (!empty($this->mascarabd) and ($this->mascara !== $this->mascarabd) )
        {
            return parent::defValor( self::converteParaMascara($valor, $this->mascarabd, $this->mascara) );
        }
        else
        {
            return parent::defValor($valor);
        }
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost() : string
    {
        $valor = parent::obtDadosPost();
        
        if (!empty($this->mascarabd) and ($this->mascara !== $this->mascarabd) )
        {
            return self::converteParaMascara($valor, $this->mascara, $this->mascarabd);
        }
        else
        {
            return $valor;
        }
    }
    
    /**
     * Convert from one mask to another
     * @param $valor original date
     * @param $daMascara source mask
     * @param $paraMascara target mask
     */
    public static function converteParaMascara($valor, $daMascara, $paraMascara)
    {
        if ($valor)
        {
            $valor = substr($valor,0,strlen($daMascara));
            
            $phpdaMascara = str_replace( ['dd','mm', 'yyyy', 'hh', 'ii', 'ss'], ['d','m','Y', 'H', 'i', 's'], $daMascara);
            $phpParaMascara   = str_replace( ['dd','mm', 'yyyy', 'hh', 'ii', 'ss'], ['d','m','Y', 'H', 'i', 's'], $paraMascara);
            
            $data = DateTime::createFromFormat($phpdaMascara, $valor);
            if ($data)
            {
                return $data->format($phpParaMascara);
            }
        }
        
        return $valor;
    }
    
    /**
     * Define the field's mask
     * @param $mascara  Mask for the field (dd-mm-yyyy)
     */
    public function defMascara($mascara, $replaceOnPost = FALSE)
    {
        $this->mascara = $mascara;
        $this->substituiNoPost = $replaceOnPost;
        
        $mascaranova = $this->mascara;
        $mascaranova = str_replace('dd',   '99',   $mascaranova);
        $mascaranova = str_replace('hh',   '99',   $mascaranova);
        $mascaranova = str_replace('ii',   '99',   $mascaranova);
        $mascaranova = str_replace('mm',   '99',   $mascaranova);
        $mascaranova = str_replace('yyyy', '9999', $mascaranova);
        
        parent::defMascara($mascaranova);
    }
    
    /**
     * Set the mask to be used to colect the data
     */
    public function defMascaraBancodados($mascara) 
    {
        $this->mascarabd = $mascara;
    }
    
    /**
     * Set extra datepicker options (ex: autoclose, startDate, daysOfWeekDisabled, datesDisabled)
     */
    public function setOption($opcao, $valor)
    {
        $this->opcoes[$opcao] = $valor;
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tdate_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tdate_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Shows the widget at the screen
     */
    public function exibe()
    {
        $mascara_js = str_replace('yyyy', 'yy', $this->mascara);
        $idioma = 'pt-BR'; # strtolower( TradutorNucleo::obtIdioma() );
        $opcoes = json_encode($this->opcoes);
        
        if (parent::obtEditavel())
        {
            $tamanho_exterior = 'undefined';
            if (strstr($this->tamanho, '%') !== FALSE)
            {
                $tamanho_exterior = $this->tamanho;
                $this->tamanho = '100%';
            }
        }
        
        parent::exibe();
        
        if (parent::obtEditavel())
        {
            Script::cria( "tdatetime_start( '#{$this->id}', '{$this->mascara}', '{$idioma}', '{$tamanho_exterior}', '{$opcoes}');");
        }
    }
}
