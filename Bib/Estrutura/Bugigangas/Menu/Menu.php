<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Menu;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Menu\ItemMenu;
use SimpleXMLElement;

/**
 * Menu Widget
 * 
 * @version 0.1
 * @package widget
 * @subpackage menu
 * @author Pabro Dall'Oglio (Modificado por: Eustábio J. Silva Júnior)
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Menu extends Elemento
{
    private $itens;
    private $classe_menu;
    private $classe_item;
    private $nivel_menu;
    private $classe_link;
    private $transformador_item;

    /**
     * Classe Construtor
     * @param $xml Analisador SimpleXMLElement do Menu XML
     */
    public function __construct($xml, $callback_permissao = NULL, $nivel_menu = 1, $classe_menu = 'dropdown-menu', $classe_item = '',
    $classe_link = 'dropdown-toggle', $transformador_item = null) {
        parent::__construct('ul');
        $this->itens = array();

        $this->{'class'} = $classe_menu . "nivel-{$nivel_menu}";
        $this->classe_menu = $classe_menu;
        $this->nivel_menu  = $nivel_menu;
        $this->classe_item = $classe_item;
        $this->classe_link = $classe_link;
        $this->transformador_item = $transformador_item;

        if ($xml instanceof SimpleXMLElement) {
            $this->analisa($xml, $callback_permissao);
        }
    }

    /**
     * Adiciona um ItemMenu
     * @param $itemmenu - Um objeto ItemMenu
     */
    public function adicItemMenu(ItemMenu $itemmenu)
    {
        if (!empty($this->transformador_item)) {
            \call_user_func($this->transformador_item, $itemmenu);
        }
        $this->itens[] = $itemmenu;
    }

    /**
     * Retorna os itens do menu
     */
    public function obtItensMenu(){
        return $this->itens;
    }

    /**
     * Analisa o XMLElement lendo as entradas do menu
     * @param $xml Um objeto SimpleXMLElement
     * @param $callback_permissao verifica a permissão do callback
     */
    public function analisa($xml, $callback_permissao = NULL) 
    {
        $i = 0; 
        foreach ($xml as $elementoXml) {
            $atrib      = $elementoXml->atributos();
            $rotulo     = (string) $atrib['rotulo'];
            $acao       = (string) $elementoXml->acao;
            $icone      = (string) $elementoXml->icone;
            $menu       = NULL;
            $itemMenu   = new ItemMenu($rotulo, $acao, $icone, $this->nivel_menu);
            $itemMenu->defClasseLink($this->classe_link);

            if ($elementoXml->menu) {
                $atrib_menu = $elementoXml->menu->attributes();
                $classe_menu = !empty($atrib_menu['class']) ? $atrib_menu['class'] : $this->classe_menu;
                $menu = new Menu($elementoXml->menu->itemmenu, $callback_permissao, $this->nivel_menu +1, $classe_menu,
                $this->classe_item, $this->classe_link, $this->transformador_item);

                foreach (parent::obtPropriedades() as $propriedade => $valor) {
                    $menu->defPropriedade($propriedade, $valor);
                }

                $itemMenu->defMenu($menu);
                if ($this->classe_item) {
                    $itemMenu->{'class'} = $this->classe_item;
                }
            }

            # Apenas nós filho tem ações
            if ($acao) {
                if (!empty($acao) AND $callback_permissao AND (substr($acao, 0, 7) !== 'http://') AND (substr($acao, 0, 8) !== 'https://')) {
                    # Verifica permissão
                    $partes = explode('#', $acao);
                    $nomeClasse = $partes[0];
                    if (\call_user_func($callback_permissao, $nomeClasse)) {
                        $this->adicItemMenu($itemMenu);
                    }
                } else {
                    # menus sem verificação de permissão
                    $this->adicItemMenu($itemMenu);
                }
            # Nós pais são mostrados apenas quando eles tem filhos válidos (com permissão)
            } else {
                $this->adicItemMenu($itemMenu);
            }
            $i++;
        }
    }

    /** 
     * Exibe o widget na tela 
     */
    public function exibe()
    {
        if ($this->itens) {
            if ($this->itens) {
                foreach ($this->itens as $item) {
                    parent::adic($item);
                }
            }
        }
    }
}