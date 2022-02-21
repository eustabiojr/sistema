<?php
namespace Estrutura\Bugigangas\Gradedados;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * Page Navigation provides navigation for a datagrid
 *
 * @version    7.1
 * @package    widget
 * @subpackage datagrid
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class NavegacaoPagina
{
    private $limite;
    private $contagem;
    private $ordem;
    private $pagina;
    private $primeira_pagina;
    private $acao;
    private $largura;
    private $sentido; # direcao
    private $oculto;
    private $retomar;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->oculto = false;
        $this->retomar = false;
    }
    
    /**
     * Hide
     */
    public function ocultar()
    {
        $this->oculto = true;
    }
    
    /**
     * Enable counters
     */
    public function ativaContadores()
    {
        $this->retomar = true;
    }
    
    /**
     * Get resume string
     */
    private function obtRetomada()
    {
        if( !$this->obtContagem() )
        {
            return NucleoTradutor::traduz('Nenhum registro localizado');
        }
        else
        {
            $max = number_format( (min(( $this->obtLimite() * $this->obtPagina() ) , $this->obtContagem())) , 0, '', '.');
            $min = number_format( (($this->obtLimite() * ($this->obtPagina() - 1) ) + 1) , 0, '', '.');
            
            return NucleoTradutor::traduz('&1 para &2 de &3 registros', $min, $max, number_format($this->obtContagem(), 0 , '', '.'));
            
        }
    }
    
    /**
     * Set the Amount of displayed records
     * @param $limite An integer
     */
    public function defLimite($limite)
    {
        $this->limite  = (int) $limite;
    }
    
    /**
     * Returns the limit of records
     */
    public function obtLimite()
    {
        return $this->limite;
    }
    
    /**
     * Define the PageNavigation's width
     * @param $largura PageNavigation's width
     */
    public function defLargura($largura)
    {
        $this->largura = $largura;
    }
    
    /**
     * Define the total count of records
     * @param $contagem An integer (the total count of records)
     */
    public function defContagem($contagem)
    {
        $this->contagem = (int) $contagem;
    }
    
    /**
     * Return the total count of records
     */
    public function obtContagem()
    {
        return $this->contagem;
    }
    
    /**
     * Define the current page
     * @param $pagina An integer (the current page)
     */
    public function defPagina($pagina)
    {
        $this->pagina = (int) $pagina;
    }
    
    /**
     * Returns the current page
     */
    public function obtPagina()
    {
        return $this->pagina;
    }
    
    /**
     * Define the first page
     * @param $pagina An integer (the first page)
     */
    public function defPrimeiraPagina($primeira_pagina)
    {
        $this->primeira_pagina = (int) $primeira_pagina;
    }
    
    /**
     * Define the ordering
     * @param $ordem A string containint the column name
     */
    public function defOrdem($ordem)
    {
        $this->ordem = $ordem;
    }
    
    /**
     * Define the ordering
     * @param $sentido asc, desc
     */
    public function defSentido($sentido)
    {
        $this->sentido = $sentido;
    }
    
    /**
     * Set the page navigation properties
     * @param $propriedades array of properties
     */
    public function defPropriedades($propriedades)
    {
        $ordem      = isset($propriedades['order'])  ? addslashes($propriedades['order'])  : '';
        $pagina     = isset($propriedades['page'])   ? $propriedades['page']   : 1;
        $sentido    = (isset($propriedades['sentido']) AND in_array($propriedades['sentido'], array('asc', 'desc')))  ? $propriedades['sentido']   : NULL;
        $primeira_pagina = isset($propriedades['first_page']) ? $propriedades['first_page']: 1;
        
        $this->defOrdem($ordem);
        $this->defPagina($pagina);
        $this->defSentido($sentido);
        $this->defPrimeiraPagina($primeira_pagina);
    }
    
    /**
     * Define the PageNavigation action
     * @param $acao TAction object (fired when the user navigates)
     */
    public function defAcao($acao)
    {
        $this->acao = $acao;
    }
    
    /**
     * Show the PageNavigation widget
     */
    public function exibe()
    {
        if ($this->oculto)
        {
            return;
        }
        
        if (!$this->acao instanceof Acao)
        {
            throw new Exception(NucleoTradutor::traduz('Você deve chamar &1 antes de adicionar este componente', __CLASS__ . '::' . 'defAcao()'));
        }
        
        if ($this->retomar)
        {
            $total = new Elemento('div');
            $total->{'class'} = 'tpagenavigation_resume';
            $total->adic($this->obtRetomada());
            $total->exibe();
        }
        
        $primeira_pagina = isset($this->primeira_pagina) ? $this->primeira_pagina : 1;
        $sentido  = 'asc';
        $page_size  = isset($this->limite) ? $this->limite : 10;
        $max = 10;
        $registros = $this->contagem;
        
        if (!$registros)
        {
            $registros = 0;
        }
        
        if ($page_size > 0)
        {
            $pages = (int) ($registros / $page_size) - $primeira_pagina +1;
        }
        else
        {
            $pages = 1;
        }
        
        $resto = 0;
        if ($page_size>0)
        {
            $resto = $registros % $page_size;
        }
        
        $pages += $resto > 0 ? 1 : 0;
        $last_page = min($pages, $max);
        
        $nav = new Elemento('nav');
        $nav->{'class'} = 'tpagenavigation';
        $nav->{'align'} = 'center';
        
        $ul = new Elemento('ul');
        $ul->{'class'} = 'pagination';
        $ul->{'style'} = 'display:inline-flex;';
        $nav->adic($ul);
        
        if ($primeira_pagina > 1)
        {
            // first
            $item = new Elemento('li');
            $link = new Elemento('a');
            $span = new Elemento('span');
            $link->{'aria-label'} = 'Previous';
            $ul->adic($item);
            $item->adic($link);
            $link->adic($span);
            $this->acao->defParametro('offset', 0);
            $this->acao->defParametro('limit',  $page_size);
            $this->acao->defParametro('sentido', $this->sentido);
            $this->acao->defParametro('page',   1);
            $this->acao->defParametro('first_page', 1);
            $this->acao->defParametro('order', $this->ordem);

            $link->{'class'}     = "page-link";
            $link->{'href'}      = $this->acao->serializa();
            $link->{'generator'} = 'adianti';
            $span->adic(Elemento::tag('span', '', ['class'=>'fa fa-angle-double-left']));
            
            // previous
            $item = new Elemento('li');
            $link = new Elemento('a');
            $span = new Elemento('span');
            $link->{'aria-label'} = 'Previous';
            $ul->adic($item);
            $item->adic($link);
            $link->adic($span);
            $this->acao->defParametro('offset', ($primeira_pagina - $max -1) * $page_size);
            $this->acao->defParametro('limit',  $page_size);
            $this->acao->defParametro('sentido', $this->sentido);
            $this->acao->defParametro('page',   $primeira_pagina - $max);
            $this->acao->defParametro('first_page', $primeira_pagina - $max);
            $this->acao->defParametro('order', $this->ordem);

            $link->{'class'}     = "page-link";
            $link->{'href'}      = $this->acao->serializa();
            $link->{'generator'} = 'adianti';
            $span->adic(Elemento::tag('span', '', ['class'=>'fa fa-angle-left'])); //$span->adic('&laquo;');
        }
        
        // active pages
        for ($n = $primeira_pagina; $n <= $last_page + $primeira_pagina -1; $n++)
        {
            $offset = ($n -1) * $page_size;
            $item = new Elemento('li');
            $link = new Elemento('a');
            $span = new Elemento('span');
            $ul->adic($item);
            $item->adic($link);
            $link->adic($span);
            $span->adic($n);
            
            $this->acao->defParametro('offset', $offset);
            $this->acao->defParametro('limit',  $page_size);
            $this->acao->defParametro('sentido', $this->sentido);
            $this->acao->defParametro('page',   $n);
            $this->acao->defParametro('first_page', $primeira_pagina);
            $this->acao->defParametro('order', $this->ordem);
            
            $link->{'href'}      = $this->acao->serializa();
            $link->{'generator'} = 'adianti';
            $link->{'class'}     = 'page-link';

            if($this->pagina == $n)
            {
                $item->{'class'} = 'active page-item';
            }
        }
        
        // inactive pages/placeholders
        for ($z=$n; $z<=10; $z++)
        {
            $item = new Elemento('li');
            $link = new Elemento('a');
            $span = new Elemento('span');
            $item->{'class'} = 'off page-item';
            $link->{'class'} = 'page-link';
            $ul->adic($item);
            $item->adic($link);
            $link->adic($span);
            $span->adic($z);
        }
        
        if ($pages > $max)
        {
            // next
            $primeira_pagina = $n;
            $item = new Elemento('li');
            $link = new Elemento('a');
            $span = new Elemento('span');
            $link->{'aria-label'} = "Next";
            $ul->adic($item);
            $item->adic($link);
            $link->adic($span);
            $this->acao->defParametro('offset',  ($n -1) * $page_size);
            $this->acao->defParametro('limit',   $page_size);
            $this->acao->defParametro('sentido', $this->sentido);
            $this->acao->defParametro('page',    $n);
            $this->acao->defParametro('first_page', $n);
            $this->acao->defParametro('order', $this->ordem);
            $link->{'class'}     = "page-link";
            $link->{'href'}      = $this->acao->serializa();
            $link->{'generator'} = 'adianti';
            $span->adic(Elemento::tag('span', '', ['class'=>'fa fa-angle-right'])); //$span->adic('&raquo;');
            
            // last
            $item = new Elemento('li');
            $link = new Elemento('a');
            $span = new Elemento('span');
            $link->{'aria-label'} = "Next";
            $ul->adic($item);
            $item->adic($link);
            $link->adic($span);
            $this->acao->defParametro('offset',  ceil($registros / $page_size)* $page_size - $page_size);
            $this->acao->defParametro('limit',   $page_size);
            $this->acao->defParametro('sentido', $this->sentido);
            $this->acao->defParametro('page',    ceil($registros / $page_size));
            $this->acao->defParametro('first_page', (int) ($registros / ($page_size *10)) *10 +1);
            $this->acao->defParametro('order', $this->ordem);
            $link->{'class'}     = "page-link";
            $link->{'href'}      = $this->acao->serializa();
            $link->{'generator'} = 'adianti';
            $span->adic(Elemento::tag('span', '', ['class'=>'fa fa-angle-double-right']));
        }
        
        $nav->exibe();
    }
}
