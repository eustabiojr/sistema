<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Menu;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Nucleo\NucleoAplicativo;

/**
 * ItemMenu Widget
 * 
 * @version 0.1
 * @package widget
 * @subpackage menu
 * @author Pabro Dall'Oglio (Modificado por: Eustábio J. Silva Júnior)
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ItemMenu extends Elemento 
{
    private $rotulo;
    private $acao;
    private $imagem;
    private $menu;
    private $nivel;
    private $link;
    private $classeLink;

    /**
     * Construtor Classe
     * @param $rotulo O rótulo menu
     * @param $acao A ação menu
     * @param $imagem A imagem menu
     */
    public function __construct($rotulo, $acao, $imagem = NULL, $nivel = 0)
    {
        parent::__construct('li');
        $this->rotulo     = $rotulo;
        $this->acao       = $acao;
        $this->nivel      = $nivel;
        $this->link       = new Elemento('a');
        $this->classeLink = 'dropdown-toggle';

        if ($imagem) {
            $this->imagem = $imagem;
        }
    }

    /**
     * Retorna a ação
     */
    public function obtAcao()
    {
        return $this->acao;
    }

    /**
     * Define a ação
     */
    public function defAcao($acao)
    {
        $this->acao = $acao;
    }

    /**
     * Retorna a rotulo
     */
    public function obtRotulo()
    {
        return $this->rotulo;
    }

    /**
     * Define o rotulo
     */
    public function defRotulo($rotulo)
    {
        $this->rotulo = $rotulo;
    }

    /**
     * Retorna a imagem
     */
    public function obtImagem()
    {
        return $this->imagem;
    }

    /**
     * Define a imagem
     */
    public function defImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    /**
     * Configura a classe do link
     */
    public function defClasseLink($classe)
    {
        $this->classeLink = $classe;
    }

    /**
     * Define o submenu para o item
     * @param $menu O objeto Menu
     */
    public function defMenu(Menu $menu)
    {
        $this->{'class'} = 'dropdown-submenu';
        $this->menu = $menu;
    }

    /**
     * Exibe o widget na tela
     */
    public function exibe()
    {
        if ($this->acao) {

            $acao = \str_replace('#', '&', $this->acao);
            if ((\substr($acao, 0, 7) == 'http://') OR (\substr($acao, 0, 8) == 'https://')) {
                $this->link->{'href'}   = $acao;
                $this->link->{'target'} = '_black';
            } else {
                if ($roteador = NucleoAplicativo::obtRoteador()) {
                    $this->link->{'href'} = $roteador("classe={$acao}", true);
                } else {
                    $this->link->{'href'} = "inicio.php?classe={$acao}";
                }
                $this->link->{'generator'} = 'ageunet';
            }
        } else {
            $this->link->{'href'} = '#';
        }

        if (isset($this->imagem)) {
            $imagem = new Imagem($this->imagem);
            $this->link->adic($imagem);
        }

        $rotulo = new Elemento('span');
        if (substr($this->rotulo, 0, 3) == '_a{') {
            $rotulo->adic(_a(substr($this->rotulo, 3, -1)));
        } else {
            $rotulo->adic($this->rotulo);
        }

        if (!empty($this->rotulo)) {
            $this->link->adic($rotulo);
            $this->adic($this->link);
        }

        if ($this->menu instanceof Menu) {
            $this->link->{'class'} = $this->classeLink;
            if (strstr($this->classeLink, 'dropdown')) {
                $this->link->{'data-toggle'} = "dropdown";
            }

            if ($this->nivel == 0) {
                $chapeu = new Elemento('b');
                $chapeu->{'class'} = 'caret';
                $chapeu->adic('');
                $this->link->adic($chapeu);
            }
            parent::adic($this->menu);
        }
        parent::exibe();
    }
}