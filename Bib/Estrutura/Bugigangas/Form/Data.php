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
  * DatePicker Widget
  *
  * @version    7.1
  * @package    widget
  * @subpackage form
  * @author     Pablo Dall'Oglio
  * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
  * @license    http://www.adianti.com.br/framework-license
  */
 class Data extends Entrada implements InterfaceBugiganga
 {
     protected $mascara;
     protected $mascarabd;
     protected $id;
     protected $tamanho;
     protected $opcoes;
     protected $valor;
     protected $substituiNoPost;
     
     /**
      * Class Constructor
      * @param $nome Name of the widget
      */
     public function __construct($nome)
     {
         parent::__construct($nome);
         $this->id   = 'date_' . mt_rand(1000000000, 1999999999);
         $this->mascara = 'yyyy-mm-dd';
         $this->mascarabd = null;
         $this->opcoes = [];
         $this->substituiNoPost = FALSE;
         
         $mascaranova = $this->mascara;
         $mascaranova = str_replace('dd',   '99',   $mascaranova);
         $mascaranova = str_replace('mm',   '99',   $mascaranova);
         $mascaranova = str_replace('yyyy', '9999', $mascaranova);
         parent::defMascara($mascaranova);
         $this->tag->{'widget'} = 'tdate';
         $this->tag->{'autocomplete'} = 'off';
     }
     
     /**
      * Store the value inside the object
      */
     public function defValor($valor)
     {
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
      * Convert from one mascara to another
      * @param $valor original date
      * @param $daMascara source mascara
      * @param $paraMascara target mascara
      */
     public static function converteParaMascara($valor, $daMascara, $paraMascara)
     {
         if ($valor)
         {
             $valor = substr($valor,0,strlen($daMascara));
             
             $daMascaraPhp     = str_replace( ['dd','mm', 'yyyy'], ['d','m','Y'], $daMascara);
             $phpParaMascara   = str_replace( ['dd','mm', 'yyyy'], ['d','m','Y'], $paraMascara);
             
             $data = DateTime::createFromFormat($daMascaraPhp    , $valor);
             if ($data)
             {
                 return $data->format($phpParaMascara);
             }
         }
         
         return $valor;
     }
     
     /**
      * Define the field's mascara
      * @param $mascara  Mask for the field (dd-mm-yyyy)
      */
     public function defMascara($mascara, $substituiNoPost = FALSE)
     {
         $this->mascara = $mascara;
         $this->substituiNoPost = $substituiNoPost;
         
         $mascaranova = $this->mascara;
         $mascaranova = str_replace('dd',   '99',   $mascaranova);
         $mascaranova = str_replace('mm',   '99',   $mascaranova);
         $mascaranova = str_replace('yyyy', '9999', $mascaranova);
         
         parent::defMascara($mascaranova);
     }
     
     /**
      * Return mascara
      */
     public function obtMascara()
     {
         return $this->mascara;
     }
     
     /**
      * Set the mascara to be used to colect the data
      */
     public function defMascaraBancodados($mascara) 
     {
         $this->mascarabd = $mascara;
     }
     
     /**
      * Return database mascara
      */
     public function obtMascaraBancodados()
     {
         return $this->mascarabd;
     }
     
     /**
      * Set extra datepicker opcoes (ex: autoclose, startDate, daysOfWeekDisabled, datesDisabled)
      */
     public function defOpcao($opcao, $valor)
     {
         $this->opcoes[$opcao] = $valor;
     }
     
     /**
      * Shortcut to convert a date to format yyyy-mm-dd
      * @param $data = date in format dd/mm/yyyy
      */
     public static function date2us($data)
     {
         if ($data)
         {
             // get the date parts
             $dia  = substr($data,0,2);
             $mes  = substr($data,3,2);
             $ano  = substr($data,6,4);
             return "{$ano }-{$mes}-{$dia}";
         }
     }
     
     /**
      * Shortcut to convert a date to format dd/mm/yyyy
      * @param $data = date in format yyyy-mm-dd
      */
     public static function date2br($data)
     {
         if ($data)
         {
             // get the date parts
             $ano  = substr($data,0,4);
             $mes  = substr($data,5,2);
             $dia  = substr($data,8,2);
             return "{$dia}/{$mes}/{$ano }";
         }
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
         $js_mascara = str_replace('yyyy', 'yy', $this->mascara);
         $idioma = 'portugues'; #strtolower( TradutorNucleo::obtIdioma() );
         $opcoes = json_encode($this->opcoes);
         
         if (parent::obtEditavel())
         {
             $lado_exterior = 'undefined';
             if (strstr($this->tamanho, '%') !== FALSE)
             {
                 $lado_exterior = $this->tamanho;
                 $this->tamanho = '100%';
             }
         }
         
         parent::exibe();
         
         if (parent::obtEditavel())
         {
             Script::cria( "tdate_start( '#{$this->id}', '{$this->mascara}', '{$idioma}', '{$lado_exterior}', '{$opcoes}');");
         }
     }
 }
 