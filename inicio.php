<?php
/********************************************************************************************
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 ********************************************************************************************/

require_once 'inic.php';
$tema = $ini['geral']['tema'];

new Sessao;
//------------------------------------------------------------------------------------------- 

$conteudo = file_get_contents('Aplicativo/Templates/painelcontrole.html');
$string_menu = ConstrutorMenu::analisa('menu.xml', $tema);
$conteudo = str_replace('{MENU}', $string_menu, $tema);
$conteudo = str_replace('{template}', $tema, $conteudo);
#$conteudo = str_replace('{BIBLIOTECAS}', file_get_contents("Aplic/template/{$tema}/bibliotecas.html"), $conteudo);
$conteudo = str_replace('{class}', $_REQUEST['classe'] ?? '', $conteudo);
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
