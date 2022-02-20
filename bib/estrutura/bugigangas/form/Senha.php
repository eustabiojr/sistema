<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Controle\Acao;
use Exception;
 
 /**
  * Password Widget
  *
  * @version    7.1
  * @package    widget
  * @subpackage form
  * @author     Pablo Dall'Oglio
  * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
  * @license    http://www.adianti.com.br/framework-license
  */
class Senha extends Campo implements InterfaceBugiganga
{
    private $acaoSair;
    private $funcaoSair;
    protected $nomeForm;
     
    /**
     * Define the action to be executed when the user leaves the form field
     * @param $acao Acao object
     */
    function defAcaoSair(Acao $acao)
    {
        if ($acao->ehEstatico())
        {
            $this->acaoSair = $acao;
        }
        else
        {
            $acao_string = $acao->paraString();
            throw new Exception("Ação {$acao_string} deve ser estatico a ser usada em ({__METHOD__})");
        }
    }
     
    /**
     * Define max length
     * @param  $comprimento Max length
     */
    public function defComprimentoMax($comprimento) 
    {
        if ($comprimento > 0)
        {
            $this->tag->{'maxlength'} = $comprimento;
        }
    }
     
    /**
     * Define the javascript function to be executed when the user leaves the form field
     * @param $funcao Javascript function
     */
    public function defFuncaoSair($funcao) 
    {
        $this->funcaoSair = $funcao;
    }
     
    /**
     * Show the widget at the screen
     */
    public function exibe()
    {
        // define the tag properties
        $this->tag->name  =  $this->nome;   // tag name
        $this->tag->value =  $this->valor;  // tag value
        $this->tag->type  =  'password';    // input type
        
        if (!empty($this->tamanho))
        {
            if (strstr($this->tamanho, '%') !== FALSE)
            {
                $this->defPropriedade('style', "width:{$this->tamanho};", FALSE); //aggregate style info
            }
            else
            {
                $this->defPropriedade('style', "width:{$this->tamanho}px;", FALSE); //aggregate style info
            }
        }
         
        // verify if the field is not editable
        if (parent::obtEditavel())
        {
            if (isset($this->acaoSair))
            {
                if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form)
                {
                    throw new Exception("Você deve passar a {__CLASS__} ({$this->nome}) como parâmetro para Form::defCampos()");
                }
                 
                $acao_string = $this->acaoSair->serialize(FALSE);
                $this->defPropriedade('onBlur', "__adianti_post_lookup('{$this->nomeForm}', '{$acao_string}', this, 'callback')");
            }
             
            if (isset($this->funcaoSair))
            {
                $this->defPropriedade('onBlur', $this->funcaoSair, FALSE );
            }
        }
        else
        {
            // make the field read-only
            $this->tag-> readonly = "1";
            $this->tag->{'class'} .= ' tfield_disabled'; // CSS
            $this->tag->{'tabindex'} = '-1';
        }
         
        // exibe the tag
        $this->tag->exibe();
    }
}
 