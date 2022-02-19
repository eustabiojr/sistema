<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Nucleo\ConfigAplicativo;

/**
 * Html Editor
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class EditorHtml extends Campo implements InterfaceBugiganga
{
    protected $id;
    protected $tamanho;
    protected $nomeForm;
    protected $barraferramenta;
    protected $completar;
    protected $opcoes;
    private   $altura;
    
    /**
     * Class Constructor
     * @param $nome Widet's name
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id = 'EditorHtml_'.mt_rand(1000000000, 1999999999);
        $this->barraferramenta = true;
        $this->opcoes = [];
        
        // creates a tag
        $this->tag = new Elemento('textarea');
        $this->tag->{'widget'} = 'thtmleditor';
    }
    
    /**
     * Set extra calendar opcoes
     */
    public function defOpcao($opcao, $valor)
    {
        $this->opcoes[$opcao] = $valor;
    }
    
    /**
     * Define the widget's size
     * @param  $largura   Widget's width
     * @param  $altura  Widget's height
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->tamanho   = $largura;
        if ($altura)
        {
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
     * Disable barraferramenta
     */
    public function desabiltaBarraFerramenta()
    {
        $this->barraferramenta = false;
    }
    
    /**
     * Define opcoes for completar
     * @param $opcoes array of opcoes for completar
     */
    function defCompletar($opcoes)
    {
        $this->completar = $opcoes;
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " thtmleditor_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " thtmleditor_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Clear the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function limpaCampo($nome_form, $campo)
    {
        Script::cria( " thtmleditor_clear_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Reload completar
     * 
     * @param $campo Field name or id
     * @param $opcoes array of opcoes for autocomplete
     */
    public static function recarregaCompletar($campo, $opcoes)
    {
        $opcoes = json_encode($opcoes);
        Script::cria(" thtml_editor_reload_completar( '{$campo}', $opcoes); ");
    }
    
    /**
     * Show the widget
     */
    public function exibe()
    {
        $this->tag->{'id'} = $this->id;
        $this->tag->{'class'}  = 'thtmleditor';       // CSS
        $this->tag->{'name'}   = $this->nome;   // tag name
        
        $ini = ConfigAplicativo::obt();
        $local = !empty($ini['general']['locale']) ? $ini['general']['locale'] : 'pt-BR';
        
        // add the content to the textarea
        $this->tag->adic(htmlspecialchars($this->value));
        
        // exibe the tag
        $this->tag->exibe();
        
        $opcoes = $this->opcoes;
        if (!$this->barraferramenta)
        {
            $opcoes[ 'airMode'] = true;
        }
        if (!empty($this->completar))
        {
            $opcoes[ 'completar'] = $this->completar;
        }
        
        $opcoes_json = json_encode( $opcoes );
        Script::cria(" thtmleditor_start( '{$this->tag->{'id'}}', '{$this->tamanho}', '{$this->altura}', '{$local}', '{$opcoes_json}' ); ");
        
        // check if the field is not editable
        if (!parent::obtEditavel())
        {
            Script::cria( " thtmleditor_disable_field('{$this->nomeForm}', '{$this->nome}'); " );
        }
    }
}
