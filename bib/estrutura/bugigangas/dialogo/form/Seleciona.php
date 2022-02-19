<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Controle\Acao;
use Exception;

/**
 * Select Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Seleciona extends Campo implements InterfaceBugiganga
{
    protected $id;
    protected $altura;
    protected $itens; // array containing the combobox options
    protected $nomeForm;
    protected $mudaFuncao;
    protected $mudaAcao;
    protected $opcaoPadrao;
    protected $separador;
    protected $valor;
    protected $comTitulos;
    
    /**
     * Class Constructor
     * @param  $nome widget's name
     */
    public function __construct($nome)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        $this->id   = 'tselect_' . mt_rand(1000000000, 1999999999);
        $this->opcaoPadrao = '';
        $this->comTitulos = true;
        
        // creates a <select> tag
        $this->tag = new Elemento('select');
        $this->tag->{'class'} = 'tselect'; // CSS
        $this->tag->{'multiple'} = '1';
        $this->tag->{'widget'} = 'tselect';
    }
    
    
    /**
     * Disable multiple selection
     */
    public function desabilitaMultiplo()
    {
        unset($this->tag->{'multiple'});
        $this->tag->{'size'} = 3;
    }

    /**
     * Disable option titles
     */
    public function desabilitaTitulos()
    {
        $this->comTitulos = false;
    }
    
    public function defOpcaoPadrao($opcao)
    {
        $this->opcaoPadrao = $opcao;
    }
    
    /**
     * Add items to the select
     * @param $itens An indexed array containing the combo options
     */
    public function adicItens($itens)
    {
        if (is_array($itens))
        {
            $this->itens = $itens;
        }
    }
    
    /**
     * Return the items
     */
    public function obtItens()
    {
        return $this->itens;
    }
    
    /**
     * Define the Field's width
     * @param $largura Field's width in pixels
     * @param $altura Field's height in pixels
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->tamanho = $largura;
        $this->altura = $altura;
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
     * Define the field's separador
     * @param $sep A string containing the field's separador
     */
    public function defValorSeparador($sep)
    {
        $this->separador = $sep;
    }
    
    /**
     * Define the field's value
     * @param $valor A string containing the field's value
     */
    public function defValor($valor)
    {
        if (empty($this->separador))
        {
            $this->valor = $valor;
        }
        else
        {
            if ($valor)
            {
                $this->valor = explode($this->separador, $valor);
            }
        }
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost()
    {
        if (isset($_POST[$this->nome]))
        {
            if ($this->tag->{'multiple'})
            {
                if (empty($this->separador))
                {
                    return $_POST[$this->nome];
                }
                else
                {
                    return implode($this->separador, $_POST[$this->nome]);
                }
            }
            else
            {
                return $_POST[$this->nome][0];
            }
        }
        else
        {
            return array();
        }
    }
    
    /**
     * Define the action to be executed when the user changes the combo
     * @param $acao TAction object
     */
    public function defAcaoMudar(Acao $acao)
    {
        if ($acao->ehEstatico())
        {
            $this->mudaAcao = $acao;
        }
        else
        {
            $string_acao = $acao->paraString();
            throw new Exception("A ação {$string_acao} deve ser estática a ser usada em {__METHOD__}");
        }
    }
    
    /**
     * Set change function
     */
    public function defMudaFuncao($funcao) 
    {
        $this->mudaFuncao = $funcao;
    }
    
    /**
     * Reload combobox items after it is already exiben
     * @param $nomeform form name (used in gtk version)
     * @param $nome field name
     * @param $itens array with items
     * @param $iniciaVazio ...
     */
    public static function recarrega($nomeform, $nome, $itens, $iniciaVazio = FALSE)
    {
        $codigo = "tselect_clear('{$nomeform}', '{$nome}'); ";
        if ($iniciaVazio)
        {
            $codigo .= "tselect_add_option('{$nomeform}', '{$nome}', '', ''); ";
        }
        
        if ($itens)
        {
            foreach ($itens as $chave => $valor)
            {
                $codigo .= "tselect_add_option('{$nomeform}', '{$nome}', '{$chave}', '{$valor}'); ";
            }
        }
        Script::cria($codigo);
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tselect_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tselect_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Clear the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function limpaCampo($nome_form, $campo)
    {
        Script::cria( " tselect_clear_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Render items
     */
    protected function renderizaItens( $com_titulos = true )
    {
        if ($this->opcaoPadrao !== FALSE)
        {
            // creates an empty <option> tag
            $opcao = new Elemento('option');
            
            $opcao->adic( $this->opcaoPadrao );
            $opcao->{'value'} = '';   // tag value

            // add the option tag to the combo
            $this->tag->adic($opcao);
        }
        
        if ($this->itens)
        {
            // iterate the combobox items
            foreach ($this->itens as $chave => $item)
            {
                if (substr($chave, 0, 3) == '>>>')
                {
                    $opcaogrupo = new Elemento('optgroup');
                    $opcaogrupo->{'label'} = $item;
                    // add the option to the combo
                    $this->tag->adic($opcaogrupo);
                }
                else
                {
                    // creates an <option> tag
                    $opcao = new Elemento('option');
                    $opcao->{'value'} = $chave;  // define the index
                    if ($com_titulos)
                    {
                        $opcao->{'title'} = $item;  // define the title
                    }
                    $opcao->{'titside'} = 'left';  // define the title side
                    $opcao->adic(htmlspecialchars($item));      // add the item label
                    
                    // verify if this option is selected
                    if ( (is_array($this->valor)  AND @in_array($chave, $this->valor)) OR
                         (is_scalar($this->valor) AND strlen( (string) $this->valor ) > 0 AND @in_array($chave, (array) $this->valor)))
                    {
                        // mark as selected
                        $opcao->{'selected'} = 1;
                    }
                    
                    if (isset($opcaogrupo))
                    {
                        $opcaogrupo->adic($opcao);
                    }
                    else
                    {
                        $this->tag->adic($opcao);
                    }                    
                }
            }
        }
    }
    
    /**
     * Shows the widget
     */
    public function exibe()
    {
        // define the tag properties
        $this->tag->{'name'}  = $this->nome.'[]';    // tag name
        $this->tag->{'id'}    = $this->id;
        
        $this->defPropriedade('style', (strstr($this->tamanho, '%') !== FALSE)   ? "width:{$this->tamanho}"    : "width:{$this->tamanho}px",   false); //aggregate style info
        $this->defPropriedade('style', (strstr($this->altura, '%') !== FALSE) ? "height:{$this->altura}" : "height:{$this->altura}px", false); //aggregate style info
        
        // verify whether the widget is editable
        if (parent::getEditable())
        {
            if (isset($this->mudaAcao))
            {
                if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form)
                {
                    throw new Exception("Você deve passer a {__CLASS__} ({$this->nome}) como parâmetro para Form::defCampos()");
                }
                
                $string_acao = $this->mudaAcao->serializa(FALSE);
                $this->defPropriedade('changeaction', "__ageunet_pesquisa_post('{$this->nomeForm}', '{$string_acao}', this, 'callback')");
                $this->defPropriedade('onChange', $this->obtPropriedade('changeaction'));
            }
            
            if (isset($this->mudaFuncao))
            {
                $this->defPropriedade('changeaction', $this->mudaFuncao, FALSE);
                $this->defPropriedade('onChange', $this->mudaFuncao, FALSE);
            }
        }
        else
        {
            // make the widget read-only
            $this->tag->{'onclick'} = "return false;";
            $this->tag->{'style'}  .= ';pointer-events:none';
            $this->tag->{'class'}   = 'tselect_disabled'; // CSS
        }
        
        // exibes the widget
        $this->renderizaItens( $this->comTitulos );
        $this->tag->exibe();
    }
}
