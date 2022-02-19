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

/**
 * Color Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Icone extends Entrada implements InterfaceBugiganga
{
    protected $id;
    protected $funcaoMuda;
    protected $nomeForm;
    protected $nome;
    
    /**
     * Class Constructor
     * @param $nome Name of the widget
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id = 'ticon_'.mt_rand(1000000000, 1999999999);
        $this->tag->{'autocomplete'} = 'off';
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " ticon_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " ticon_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Set change function
     */
    public function defFuncaoMuda($funcao)
    {
        $this->funcaoMuda = $funcao;
    }

    /**
     * Shows the widget at the screen
     */
    public function exibe()
    {
        $embrulho = new Elemento('div');
        $embrulho->{'class'} = 'input-group';
        $span = new Elemento('span');
        $span->{'class'} = 'input-group-addon';
        
        if (!empty($this->acaoSair))
        {
            $this->defFuncaoMuda( $this->funcaoMuda . "; tform_fire_field_actions('{$this->nomeForm}', '{$this->nome}'); " );
        }
        
        $i = new Elemento('i');
        $span->adic($i);
        
        if (strstr($this->tamanho, '%') !== FALSE)
        {
            $tamanho_exterior = $this->tamanho;
            $this->tamanho = '100%';
            $embrulho->{'style'} = "width: $tamanho_exterior";
        }
        
        ob_start();
        parent::exibe();
        $filho = ob_get_contents();
        ob_end_clean();
        
        $embrulho->adic($filho);
        $embrulho->adic($span);
        $embrulho->exibe();
        
        if (parent::obtEditavel())
        {
            if($this->funcaoMuda)
            {
                Script::cria(" ticon_start('{$this->id}',function(icon){ {$this->funcaoMuda} }); ");   
            }
            else
            {
                Script::cria(" ticon_start('{$this->id}',false); ");
            }
        }
    }
}
