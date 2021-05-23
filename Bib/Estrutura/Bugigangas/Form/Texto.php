<?php
/********************************************************************************************
* Sistema Agenet
* 
* Data: 10/03/2021
********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\Acao;
use Exception;

/**
 * Text Widget (also known as Memo)
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Texto extends Campo implements InterfaceBugiganga
{
    private   $acaoSair;
    private   $funcaoSair;
    protected $id;
    protected $nomeForm;
    protected $tamanho;
    protected $altura;
    
    /**
     * Class Constructor
     * @param $nome Widet's name
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id   = 'ttext_' . mt_rand(1000000000, 1999999999);
        
        // creates a <textarea> tag
        $this->tag = new Elemento('textarea');
        $this->tag->{'class'} = 'tfield';       // CSS
        $this->tag->{'widget'} = 'ttext';
        // defines the text default height
        $this->altura= 100;
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
     * Define max length
     * @param  $length Max length
     */
    public function defComprimentoMax($length)
    {
        if ($length > 0)
        {
            $this->tag->{'maxlength'} = $length;
        }
    }
    
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
     * Set exit function
     */
    public function defFuncaoSair($funcao)
    {
        $this->funcaoSair = $funcao;
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost()
    {
        $nome = str_replace(['[',']'], ['',''], $this->nome);
        
        if (isset($_POST[$nome]))
        {
            return $_POST[$nome];
        }
        else
        {
            return '';
        }
    }
    
    /**
     * Show the widget
     */
    public function exibe()
    {
        $this->tag->{'name'}  = $this->nome;   // tag name
        
        if ($this->tamanho)
        {
            $tamanho = (strstr($this->tamanho, '%') !== FALSE) ? $this->tamanho : "{$this->tamanho}px";
            $this->defPropriedade('style', "width:{$tamanho};", FALSE); //aggregate style info
        }
        
        if ($this->altura)
        {
            $altura = (strstr($this->altura, '%') !== FALSE) ? $this->altura : "{$this->altura}px";
            $this->defPropriedade('style', "height:{$altura}", FALSE); //aggregate style info
        }
        
        if ($this->id and empty($this->tag->{'id'}))
        {
            $this->tag->{'id'} = $this->id;
        }
        
        // check if the field is not editable
        if (!parent::getEditable())
        {
            // make the widget read-only
            $this->tag->{'readonly'} = "1";
            $this->tag->{'class'} = $this->tag->{'class'} == 'tfield' ? 'tfield_disabled' : $this->tag->{'class'} . ' tfield_disabled'; // CSS
            $this->tag->{'tabindex'} = '-1';
        }
        
        if (isset($this->acaoSair))
        {
            if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form)
            {
                throw new Exception("Você deve passar a {__CLASS__} ({$this->nome}) como parâmetro para Form::defCampos()");
            }
            $acao_string = $this->acaoSair->serializa(FALSE);
            $this->defPropriedade('exitaction', "__adianti_post_lookup('{$this->nomeForm}', '{$acao_string}', this, 'callback')");
            $this->defPropriedade('onBlur', $this->obtPropriedade('exitaction'), FALSE);
        }
        
        if (isset($this->funcaoSair))
        {
            $this->defPropriedade('onBlur', $this->funcaoSair, FALSE );
        }
        
        // add the content to the textarea
        $this->tag->adic(htmlspecialchars($this->valor));
        // exibe the tag
        $this->tag->exibe();
    }
}
