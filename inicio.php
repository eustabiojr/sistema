<?php
/********************************************************************************************
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 ********************************************************************************************/

use Estrutura\Controle\Pagina;
use Estrutura\Nucleo\NucleoAplicativo;

#
 require_once 'inic.php';
$tema = $ini['geral']['tema'];

new Sessao;
//------------------------------------------------------------------------------------------- 

$conteudo = file_get_contents("aplicativo/templates/{$tema}/painelcontrole.html"); ### pagina_vazia # painelcontrole
$string_menu = ConstrutorMenu::analisa('menu.xml', $tema);
$conteudo = str_replace('{MENU}', $string_menu, $conteudo);
$conteudo = str_replace('{template}', $tema, $conteudo);
$conteudo = str_replace('{BIBLIOTECAS}', file_get_contents("aplicativo/templates/{$tema}/bibliotecas.html"), $conteudo);
$conteudo = str_replace('{classe}', $_REQUEST['classe'] ?? '', $conteudo);
$conteudo = str_replace('{template}', $tema, $conteudo);
$conteudo = str_replace('{MENU}', $string_menu, $conteudo);
$css      = Pagina::obtCSSCarregado();
$js       = Pagina::obtJSCarregado();
$conteudo = str_replace('{CABECALHO}', $css . $js , $conteudo);

echo $conteudo;

if (isset($_REQUEST['classe']))
{
    $metodo = isset($_REQUEST['metodo']) ?? NULL;
    NucleoAplicativo::carregaPagina($_REQUEST['classe'], $metodo, $_REQUEST);
}
