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
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

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
class Cor extends Entrada implements InterfaceBugiganga
{
    protected $nomeForm;
    protected $nome;
    protected $id;
    protected $tamanho;
    protected $mudaFuncao;
    protected $mudaAcao;
    
    /**
     * Class Constructor
     * @param $nome Name of the widget
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id = 'tcolor_'.mt_rand(1000000000, 1999999999);
        $this->tag->{'widget'} = 'tcolor';
        $this->tag->{'autocomplete'} = 'off';
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tcolor_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tcolor_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Set change function
     */
    public function defMudaFuncao($funcao)
    {
        $this->mudaFuncao = $funcao;
    }
    
    /**
     * Define the action to be executed when the user changes the content
     * @param $acao Acao object
     */
    public function defMudaAcao(Acao $acao) 
    {
        $this->mudaAcao = $acao;
    }
    
    /**
     * Shows the widget at the screen
     */
    public function exibe()
    {
        $embrulho = new Elemento('div');
        $embrulho->{'class'} = 'input-group color-div colorpicker-component';
        $embrulho->{'style'} = 'float:inherit';
        
        $span = new Elemento('span');
        $span->{'class'} = 'input-group-addon tcolor';
        
        $tamaho_exterior = 'undefined';
        if (strstr($this->tamanho, '%') !== FALSE)
        {
            $tamaho_exterior = $this->tamanho;
            $this->tamanho = '100%';
        }
        
        if ($this->mudaAcao)
        {
            if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form)
            {
                throw new Exception(NucleoTradutor::traduz('Você deve passar o &1 (&2) como parâmetro para &3', __CLASS__, $this->nome, 'Form::defCampos()'));
            }
            
            $string_acao = $this->mudaAcao->serializa(FALSE);
            $this->setProperty('changeaction', "__adianti_post_lookup('{$this->nomeForm}', '{$string_acao}', '{$this->id}', 'callback')");
            $this->mudaFuncao = $this->getProperty('changeaction');
        }
        
        $i = new Elemento('i');
        $i->{'class'} = 'tcolor-icon';
        $span->adic($i);
        ob_start();
        parent::exibe();
        $filho = ob_get_contents();
        ob_end_clean();
        $embrulho->adic($filho);
        $embrulho->adic($span);
        $embrulho->exibe();
        
        Script::cria("tcolor_start('{$this->id}', '{$tamaho_exterior}', function(color) { {$this->mudaFuncao} }); ");
        
        if (!parent::obtEditavel())
        {
            self::desabilitaCampo($this->nomeForm, $this->nome);
        }
    }
}
