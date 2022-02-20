<?php
/********************************************************************************************
* Sistema Agenet
* 
* Data: 10/03/2021
********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use DateTime;
use Estrutura\Bugigangas\Base\Script;

/**
 * TimePicker Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Tempo extends Entrada implements InterfaceBugiganga
{
    private $mascara;
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
        $this->id   = 'ttime_' . mt_rand(1000000000, 1999999999);
        $this->mascara = 'hh:ii';
        $this->opcoes = [];
        
        $this->defOpcao('startView', 1);
        $this->defOpcao('pickDate', false);
        $this->defOpcao('formatViewType', 'time');
        $this->defOpcao('fontAwesome', true);
    
        $nova_mascara = $this->mascara;
        $nova_mascara = str_replace('hh',   '99',   $nova_mascara);
        $nova_mascara = str_replace('ii',   '99',   $nova_mascara);
        parent::defMascara($nova_mascara);
        $this->tag->{'widget'} = 'ttime';
    }
    
    /**
     * Define the field's mascara
     * @param $mascara  Mask for the field (dd-mm-yyyy)
     */
    public function defMascara($mascara, $substituiNoPost = FALSE)
    {
        $this->mascara = $mascara;
        $this->substituiNoPost = $substituiNoPost;
        
        $nova_mascara = $this->mascara;
        $nova_mascara = str_replace('hh',   '99',   $nova_mascara);
        $nova_mascara = str_replace('ii',   '99',   $nova_mascara);
        
        parent::defMascara($nova_mascara, $substituiNoPost);
    }
    
    /**
     * Set extra datepicker options (ex: autoclose, startDate, daysOfWeekDisabled, datesDisabled)
     */
    public function defOpcao($opcao, $valor)
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
        $idioma = "portugues"; # strtolower( AdiantiCoreTranslator::obtIdioma() );
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
