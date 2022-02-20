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
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * A Sortable list
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ListaClassificacao extends Campo implements InterfaceBugiganga
{
    private $ItensIniciais;
    private $itens;
    private $valorSet;
    private $conectadoAo;
    private $itemIcone;
    private $mudaAcao;
    private $orientacao;
    private $limite;
    protected $id;
    protected $mudaFuncao;
    protected $largura;
    protected $altura;
    protected $separador;
    
    /**
     * Class Constructor
     * @param  $nome widget's name
     */
    public function __construct($nome)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        $this->id   = 'tsortlist_'.mt_rand(1000000000, 1999999999);
        
        $this->ItensIniciais = array();
        $this->itens = array();
        $this->limite = -1;
        
        // creates a <ul> tag
        $this->tag = new Elemento('ul');
        $this->tag->{'class'} = 'tsortlist';
        $this->tag->{'itemname'} = $nome;
    }
    
    /**
     * Define orientacao
     * @param $orienatation (horizontal, vertical)
     */
    public function defOritencao($orientacao)
    {
        $this->orientacao = $orientacao;
    }
    
    /**
     * Define limit
     */
    public function defLimite($limite)
    {
        $this->limite = $limite;
    }
    
    /**
     * Define the item icon
     * @param $image Item icon
     */
    public function defItemIcone(Imagem $icone)
    {
        $this->itemIcone = $icone;
    }
    
    /**
     * Define the list size
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->largura = $largura;
        $this->altura = $altura;
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
     * @param $valor An array the field's values
     */
    public function defValor($valor)
    {
        if (!empty($this->separador))
        {
            $valor = explode($this->separador, $valor);
        }
        
        $itens = $this->ItensIniciais;
        if (is_array($valor))
        {
            $this->itens = array();
            foreach ($valor as $indice)
            {
                if (isset($itens[$indice]))
                {
                    $this->itens[$indice] = $itens[$indice];
                }
                else if (isset($this->conectadoAo) AND is_array($this->conectadoAo))
                {
                    foreach ($this->conectadoAo as $conectadoLista)
                    {
                        if (isset($conectadoLista->ItensIniciais[$indice] ) )
                        {
                            $this->itens[$indice] = $conectadoLista->ItensIniciais[$indice];
                        }
                    }
                }
            }
        	$this->valueSet = TRUE;
        }
    }
    
    /**
     * Connect to another list
     * @param $lista Another ListaClassificacao
     */
    public function conectadoAo(ListaClassificacao $lista)
    {
        $this->conectadoAo[] = $lista;
    }
    
    /**
     * Add itens to the sort list
     * @param $itens An indexed array containing the options
     */
    public function adicItens($itens)
    {
        if (is_array($itens))
        {
            $this->ItensIniciais += $itens;
            $this->itens += $itens;
        }
    }
    
    /**
     * Return the sort itens
     */
    public function obtItens()
    {
        return $this->ItensIniciais;
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost()
    {
        if (isset($_POST[$this->nome]))
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
            return array();
        }
    }
    
    /**
     * Define the acao to be executed when the user changes the combo
     * @param $acao Acao object
     */
    public function defMudaAcao(Acao $acao)
    {
        if ($acao->ehEstatico())
        {
            $this->mudaAcao = $acao;
        }
        else
        {
            $string_acao = $acao->paraString();
            throw new Exception(NucleoTradutor::traduz('A ação (&1) deve ser estática para ser usado em &2', $string_acao, __METHOD__));
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
     * Enable the field
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tsortlist_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tsortlist_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Clear the field
     */
    public static function limpaCampo($nome_form, $campo)
    {
        Script::cria( " tsortlist_clear_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Shows the widget at the screen
     */
    public function exibe()
    {
        $this->tag->{'id'} = $this->id;
        
        $this->defPropriedade('style', (strstr($this->largura, '%') !== FALSE)  ? "width:{$this->largura};"   : "width:{$this->largura}px;",   false); //aggregate style info
        $this->defPropriedade('style', (strstr($this->altura, '%') !== FALSE) ? "height:{$this->altura};" : "height:{$this->altura}px;", false); //aggregate style info
        
        if ($this->orientacao == 'horizontal')
        {
            $this->tag->{'itemdisplay'} = 'inline-block';
        }
        else
        {
            $this->tag->{'itemdisplay'} = 'block';
        }
        
        if ($this->itens)
        {
            $i = 1;
            // iterate the checkgroup options
            foreach ($this->itens as $indice => $rotulo)
            {
                // control to reduce available options when they are present
                // in another connected list as a post value
	            if ($this->conectadoAo AND is_array($this->conectadoAo))
	            {
	                foreach ($this->conectadoAo as $conectadoLista)
	                {
                        if (isset($conectadoLista->itens[$indice]) AND $conectadoLista->valueSet )
                        {
                            continue 2;
                        }
	                }
	            }

                // instantiates a new Item
                $item = new Elemento('li');
                
                if ($this->itemIcone)
                {
                    $item->adic($this->itemIcone);
                }

                $rotulo = new Rotulo($rotulo);
                $rotulo->estilo = 'width: 100%;';

                $item->adic($rotulo);
                $item->{'class'} = 'tsortlist_item btn btn-default';
                $item->{'style'} = 'display:block;';
                $item->{'id'} = "tsortlist_{$this->nome}_item_{$i}_li";
                $item->{'title'} = $this->tag->title;
                
                if ($this->orientacao == 'horizontal')
                {
                    $item->{'style'} = 'display:inline-block';
                }
                
                $entrada = new Elemento('input');
                $entrada->{'id'}   = "tsortlist_{$this->nome}_item_{$i}_li_input";
                $entrada->{'type'} = 'hidden';
                $entrada->{'name'} = $this->nome . '[]';
                $entrada->{'value'} = $indice;
                $item->adic($entrada);
                
                $this->tag->adic($item);
                $i ++;
            }
        }
        
        if (parent::obtEditavel())
        {
            $funcao_muda = 'function() {}';
            if (isset($this->mudaAcao))
            {
                if (!Form::obtFormPeloNome($this->formName) instanceof Form)
                {
                    throw new Exception(NucleoTradutor::traduz('Você deve passar o &1 (&2) como parâmetro para &3', __CLASS__, $this->nome, 'Form::defCampos()'));
                }            
                $string_acao = $this->mudaAcao->serialize(FALSE);
                $funcao_muda = "function() { __adianti_post_lookup('{$this->formName}', '{$string_acao}', '{$this->id}', 'callback'); }";
            }
            
            if (isset($this->mudaFuncao))
            {
                $funcao_muda = "function() { $this->mudaFuncao }";
            }
            
            $connect = 'false';
            if ($this->conectadoAo AND is_array($this->conectadoAo))
            {
                foreach ($this->conectadoAo as $conectadoLista)
                {
                    $connectIds[] =  '#'.$conectadoLista->getId();
                }
                $connect = implode(', ', $connectIds);
            }
            Script::cria(" tsortlist_start( '#{$this->id}', '{$connect}', $funcao_muda, $this->limite ) ");
        }
        $this->tag->exibe();
    }
}
