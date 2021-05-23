<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 12/04/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\Acao;

/**
 * Notebook
 *
 * @version    7.1
 * @package    widget
 * @subpackage container
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Caderno extends Elemento
{
    private $largura;
    private $altura;
    private $paginaCorrente;
    private $paginas;
    private $contador;
    private $id;
    private $acaoAba;
    private $visibilidadeAbas;
    private $sensibilidadeAbas;
    private $recipiente;
    static private $contadorNota;
    
    /**
     * Class Constructor
     * @param $largura   Notebook's width
     * @param $altura  Notebook's height
     */
    public function __construct($largura = null, $altura = null)
    {
        parent::__construct('div');
        $this->id = 'tnotebook_' . mt_rand(1000000000, 1999999999);
        $this->contador = ++ self::$contadorNota;
        
        // define some default values
        $this->paginas = [];
        $this->largura = $largura;
        $this->altura = $altura;
        $this->paginaCorrente = 0;
        $this->visibilidadeAbas = TRUE;
        $this->sensibilidadeAbas = TRUE;
    }
    
    /**
     * Define if the tabs will be visible or not
     * @param $visibilidade If the tabs will be visible
     */
    public function defVisibilidadeAbas($visibilidade) 
    {
        $this->visibilidadeAbas = $visibilidade;
    }
    
    /**
     * Define the tabs click sensibility
     * @param $sensibilidade If the tabs will be sensible to click
     */
    public function defSensibilidadeAbras($sensibilidade)
    {
        $this->sensibilidadeAbas = $sensibilidade;
    }
    
    /**
     * Returns the element ID
     */
    public function obtId()
    {
        return $this->id;
    }
    
    /**
     * Set the notebook size
     * @param $largura  Notebook's width
     * @param $altura Notebook's height
     */
    public function defTamanho($largura, $altura)
    {
        // define the width and height
        $this->largura  = $largura;
        $this->altura = $altura;
    }
    
    /**
     * Returns the frame size
     * @return array(largura, altura)
     */
    public function obtTamanho()
    {
        return array($this->largura, $this->altura);
    }
    
    /**
     * Define the current page to be exiben
     * @param $i An integer representing the page number (start at 0)
     */
    public function defPaginaCorrente($i) 
    {
        // atribui a página corrente
        $this->paginaCorrente = $i;
    }
    
    /**
     * Returns the current page
     */
    public function obtPaginaCorrente()
    {
        return $this->paginaCorrente;
    }
    
    /**
     * Add a tab to the notebook
     * @param $titulo   tab's title
     * @param $objeto  tab's content
     */
    public function anexaPagina($titulo, $objeto)
    {
        $this->paginas[$titulo] = $objeto;
    }

    /**
     * Return the Page count
     */
    public function obtContadorPagina() 
    {
        return count($this->paginas);
    }
    
    /**
     * Define the action for the Notebook tab
     * @param $acao Action taken when the user
     * clicks over Notebook tab (A TAction object)
     */
    public function defAcaoAba(Acao $acao)
    {
        $this->acaoAba = $acao;
    }
    
    /**
     * Render the notebook
     */
    public function renderiza()
    {
        // count the pages
        $paginas = $this->obtContadorPagina();
        
        $this->recipiente = new Elemento('div');
        if ($this->largura)
        {
            $this->recipiente->{'style'} = ";min-width:{$this->largura}px";
        }
        $this->recipiente->{'class'} = 'tnotebook';
        
        $ul = new Elemento('ul');
        $ul->{'class'} = 'nav nav-tabs';
        $this->recipiente->adic($ul);
        
        $space = new Elemento('div');
        if ($this->largura)
        {
            $space->{'style'} = "min-width:{$this->largura}px";
        }
        $space->{'class'} = 'spacer';
        $this->recipiente->adic($space);
        
        $i = 0;
        $id = $this->id;
        
        
        if ($this->paginas)
        {
            // iterate the tabs, exibeing them
            foreach ($this->paginas as $titulo => $conteudo)
            {
                // verify if the current page is to be exiben
                $classe = ($i == $this->paginaCorrente) ? 'active' : '';
                
                // add a cell for this tab
                if ($this->visibilidadeAbas)
                {
                    $item = new Elemento('li');
                    $link = new Elemento('a');
                    $link->{'aria-controls'} = "home";
                    $link->{'role'} = "tab";
                    $link->{'data-toggle'} = "tab";
                    $link->{'href'} = "#"."panel_{$id}_{$i}";
                    $link->{'class'} = $classe . " nav-link";
                    
                    if (!$this->sensibilidadeAbas)
                    {
                        $link->{'style'} = "pointer-events:none";
                    }
                    
                    $item->adic($link);
                    $link->adic("$titulo");
                    $item->{'class'} = $classe . " nav-item";
                    $item->{'role'} = "presentation";
                    $item->{'id'} = "tab_{$id}_{$i}";
                    
                    if ($this->acaoAba)
                    {
                        $this->acaoAba->setParameter('current_page', $i+1);
                        $acao_string = $this->acaoAba->serialize(FALSE);
                        $link->onclick = "__adianti_ajax_exec('$acao_string')";
                    }
                    
                    $ul->adic($item);
                    $i ++;
                }
            }
        }
        
        // creates a <div> around the content
        $quadro = new Elemento('div');
        $quadro->{'class'} = 'frame tab-content';
        
        $largura = $this->largura;
        $altura= $this->altura;// -30;
        
        if ($largura)
        {
            $quadro->{'style'} .= ";min-width:{$largura}px";
        }
        
        if($altura)
        {
            $quadro->{'style'} .= ";min-height:{$altura}px";
        }
        
        $i = 0;
        // iterate the tabs again, now to exibe the content
        if ($this->paginas)
        {
            foreach ($this->paginas as $titulo => $conteudo)
            {
                $classePainel = ($i == $this->paginaCorrente) ? 'active': '';
                
                // creates a <div> for the contents
                $painel = new Elemento('div');
                $painel->{'role'}  = "tabpanel";
                $painel->{'class'} = "tab-pane " . $classePainel;
                $painel->{'id'}    = "panel_{$id}_{$i}"; // ID
                $quadro->adic($painel);
                
                // check if the content is an object
                if (is_object($conteudo))
                {
                    $painel->adic($conteudo);
                }
                
                $i ++;
            }
        }
        
        $this->recipiente->adic($quadro);
        return $this->recipiente;
    }
    
    /**
     * Show the notebook
     */
    public function exibe()
    {
        if (empty($this->recipiente))
        {
            $this->recipiente = $this->renderiza();
        }
        parent::adic($this->recipiente);
        parent::exibe();
    }
}
