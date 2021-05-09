<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Util;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Controle\Acao;

class Suspenso extends Elemento
{
    protected $elementos;
    private $botao;
    
    /**
     * Class Constructor
     * @param $titulo Dropdown title
     * @param $icone  Dropdown icon
     */
    public function __construct($rotulo, $icone = NULL, $usa_caret = TRUE, $titulo = '', $altura = null)
    {
        parent::__construct('div');
        $this->{'class'} = 'btn-group';
        $this->{'style'} = 'display:inline-block; -moz-user-select: none; -webkit-user-select:none; user-select:none;';
        
        $botao = new Elemento('button');
        $botao->{'data-toggle'} = 'dropdown';
        $botao->{'class'}       = 'btn btn-default btn-sm dropdown-toggle';
        $this->botao = $botao;
        
        if ($icone)
        {
            $botao->adic(new Imagem($icone));
        }
        
        if ($titulo)
        {
            $botao->{'title'} = $titulo;
        }
        $botao->adic($rotulo);
        
        if ($usa_caret)
        {
            $span = new Elemento('span');
            $span->{'class'} = 'caret';
            $span->{'style'} = 'margin-left: 3px';
            $botao->adic($span);
        }
        
        parent::adic($botao);
        
        //$this->id = 'tdropdown_' . mt_rand(1000000000, 1999999999);
        $this->elementos = new Elemento('ul');
        $this->elementos->{'class'} = 'dropdown-menu pull-left';
        $this->elementos->{'aria-labelledby'} = 'drop2';
        $this->elementos->{'widget'} = 'tdropdown';
        
        if (!empty($altura))
        {
            $this->elementos->{'style'} = "height:{$altura}px;overflow:auto";
        }
        parent::adic($this->elementos);
    }
    
    /**
     * Define the pull side
     * @side left/right
     */
    public function defPuxarParaLado($lado)
    {
        $this->elementos->{'class'} = "dropdown-menu pull-{$lado} dropdown-menu-{$lado}";
    }

    /**
     * Define the button size
     * @size sm (small) lg (large)
     */
    public function defTamanhoBotao($size)
    {
        $this->botao->{'class'} = "btn btn-default btn-{$size} dropdown-toggle";
    }
    
    /**
     * Define the button class
     * @class CSS class
     */
    public function defClasseBotao($classe)
    {
        $this->botao->{'class'} = $classe;
    }
    
    /**
     * Returns the dropdown button
     */
    public function obtBotao()
    {
        return $this->botao;
    }
    
    /**
     * Add an action
     * @param $titulo  Title
     * @param $acao Action (Acao or string Javascript action)
     * @param $icone   Icon
     */
    public function adicAcao($titulo, $acao, $icone = NULL, $popover = '', $adic = true)
    {
        $li = new Elemento('li');
        // $li->class = "dropdown-item";
        $link = new Elemento('a');
        
        if ($acao instanceof Acao)
        { 
            $link->{'onclick'} = "__adianti_load_page('{$acao->serializa()}');";
        }
        else if (is_string($acao))
        {
            $link->{'onclick'} = $acao;
        }
        $link->{'style'} = 'cursor: pointer';
        
        if ($popover)
        {
            $link->{'title'} = $popover;
        }
        
        if ($icone)
        {
            $imagem = is_object($icone) ? clone $icone : new Imagem($icone);
            $imagem->{'style'} .= ';padding: 4px';
            $link->adic($imagem);
        }
        
        $span = new Elemento('span');
        $span->adic($titulo);
        $link->adic($span);
        $li->adic($link);
        
        if ($adic)
        {
            $this->elementos->adic($li);
        }
        return $li;
    }
    
    /**
     * Add an action group
     */
    public function adicAcaoGroup($titulo, $acaos, $icone)
    {
        $li = new Elemento('li');
        $li->{'class'} = "dropdown-submenu";
        $link = new Elemento('a');
        $span = new Elemento('span');
        
        if ($icone)
        {
            $imagem = is_object($icone) ? clone $icone : new Imagem($icone);
            $imagem->{'style'} .= ';padding: 4px';
            $link->adic($imagem);
        }
        
        $span->adic($titulo);
        $link->adic($span);
        $li->adic($link);
        
        $ul = new Elemento('ul');
        $ul->{'class'} = "dropdown-menu";
        $li->adic($ul);
        if ($acaos)
        {
            foreach ($acaos as $acao)
            {
                $ul->adic( $this->adicAcao( $acao[0], $acao[1], $acao[2], '', false ) );
            }
        }
        
        $this->elementos->adic($li);
    }
    
    /**
     * Add a header
     * @param $header Options Header
     */
    public function adicCabecalho($header)
    {
        $li = new Elemento('li');
        $li->{'role'} = 'presentation';
        $li->{'class'} = 'dropdown-header';
        $li->adic($header);
        $this->elementos->adic($li);
    }
    
    /**
     * Add a separator
     */
    public function adicSeparador()
    {
        $li = new Elemento('li');
        $li->{'class'} = 'divider';
        $this->elementos->adic($li);
    }
}
