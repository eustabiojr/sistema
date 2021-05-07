<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Menu;

#use Ageunet\Util\AgeunetConversaoString;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Utilidades\ConversaoString;
use SimpleXMLElement;

/**
 * BarraMenu Widget
 * 
 * @version 0.1
 * @package widget
 * @subpackage menu
 * @author Pabro Dall'Oglio (Modificado por: Eustábio J. Silva Júnior)
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BarraMenu extends Elemento
{
    public function __construct()
    {
        parent::__construct('div');
        $this->{'style'} = 'margin: 0;';
        $this->{'class'} = 'navbar';
    }

    /**
     * Constroi um BarraMenu de um arquivo XML
     * @param $arquivo_xml Caminho para o arquivo
     * @param $callback_permissao Verifica a permissão do callback
     */
    public static function novoDoXML($arquivo_xml, $callback_permissao = NULL, $classe_barra = 'nav navbar-nav', $classe_menu = 'dropdown-menu', $classe_item = '')
    {
        if (\file_exists($arquivo_xml)) {
            $string_menu = ConversaoString::garanteUnicode(file_get_contents(($arquivo_xml)));
            $xml = new SimpleXMLElement($string_menu);

            $barramenu = new BarraMenu;
            $ul = new Elemento('ul');
            $ul->{'class'} = $classe_barra;
            $barramenu->adic($ul);
            foreach ($xml as $elementoXml) {
                $atributos = $elementoXml->attributes();
                $rotulo = (string) $atributos['rotulo'];
                $acao   = (string) $elementoXml->acao;
                $icone  = (string) $elementoXml->icone;

                $item = new ItemMenu($rotulo, $acao, $icone);
                $menu = new Menu($elementoXml->menu->itemmenu, $callback_permissao, 1, $classe_menu, $classe_item);

                # Verifica filhos (permissões)
                if (count($menu->obtItensMenu()) > 0) {
                    $item->defMenu($menu);
                    $item->{'class'} = 'active';
                    $ul->adic($item);
                } else if ($acao) {
                    $ul->adic($item);
                }
            }
            return $barramenu;
        }
    } 

    /**
     * Exibe
     */
    public function exibe()
    {
        Script::cria('gbarramenu_inicio();');
        parent::exibe();
    }
}