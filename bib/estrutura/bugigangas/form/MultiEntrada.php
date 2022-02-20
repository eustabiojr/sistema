<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * Multi Entry Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Matheus Agnes Dias
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiEntrada extends Seleciona implements InterfaceBugiganga
{
    protected $id;
    protected $itens;
    protected $tamanho;
    protected $altura;
    protected $tamanhoMax;
    protected $editavel;
    protected $acaoMuda;
    protected $funcaoMuda;
    
    /**
     * Class Constructor
     * @param  $nome Widget's name
     */
    public function __construct($nome)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        $this->id   = 'tmultientry_'.mt_rand(1000000000, 1999999999);

        $this->altura = 34;
        $this->tamanhoMax = 0;
        
        $this->tag->{'component'} = 'multientry';
        $this->tag->{'widget'} = 'tmultientry';
    }
    
    /**
     * Define the widget's tamanho
     * @param  $largura   Widget's width
     * @param  $altura  Widget's altura
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
     * Define the maximum number of items that can be selected
     */
    public function defTamanhoMax($maxtamanho)
    {
        $this->tamanhoMax = $maxtamanho;
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tmultisearch_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tmultisearch_disable_field('{$nome_form}', '{$campo}'); " );
    }

    /**
     * Clear the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function limpaCampo($nome_form, $campo)
    {
        Script::cria( " tmultisearch_clear_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Render items
     */
    protected function renderizaItens( $com_titulos = true)
    {
        if (parent::obtValor())
        {
            // iterate the combobox items
            foreach (parent::obtValor() as $item)
            {
                // creates an <option> tag
                $opcao = new Elemento('option');
                $opcao->{'value'} = $item;  // define the index
                $opcao->adic($item);      // add the item label
                
                if ($com_titulos)
                {
                    $opcao->{'title'} = $item;  // define the title
                }
                
                // mark as selected
                $opcao->{'selected'} = 1;
                
                $this->tag->adic($opcao);
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
        $this->tag->{'id'}  = $this->id;    // tag name
        
        if (strstr($this->tamanho, '%') !== FALSE)
        {
            $this->defPropriedade('style', "width:{$this->tamanho};", false); //aggregate style info
            $tamanho  = "{$this->tamanho}";
        }
        else
        {
            $this->defPropriedade('style', "width:{$this->tamanho}px;", false); //aggregate style info
            $tamanho  = "{$this->tamanho}px";
        }
        
        $acao_muda = 'function() {}';
        
        $this->renderizaItens( false );
        
        if ($this->editavel)
        {
            if (isset($this->acaoMuda))
            {
                if (!Form::obtFormPeloNome($this->formName) instanceof Form)
                {
                    throw new Exception(NucleoTradutor::traduz('Você deve passar o &1 (&2) como parâmetro para &3', __CLASS__, $this->nome, 'Form::defCampos()'));
                }
                
                $string_acao = $this->acaoMuda->serializa(FALSE);
                $acao_muda = "function() { __adianti_post_lookup('{$this->formName}', '{$string_acao}', '{$this->id}', 'callback'); }";
            }
            else if (isset($this->changeFunction))
            {
                $acao_muda = "function() { $this->changeFunction }";
            }
            $this->tag->exibe();
            Script::cria(" tmultientry_start( '{$this->id}', '{$this->tamanhoMax}', '{$tamanho}', '{$this->altura}px', $acao_muda ); ");
        }
        else
        {
            $this->tag->exibe();
            Script::cria(" tmultientry_start( '{$this->id}', '{$this->tamanhoMax}', '{$tamanho}', '{$this->altura}px', $acao_muda ); ");
            Script::cria(" tmultientry_disable_field( '{$this->formName}', '{$this->nome}'); ");
        }
    }
}
