<?php
/********************************************************************************************
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 ********************************************************************************************/

require_once 'inic.php';
#$tema = $ini['geral']['tema'];

new Sessao;
//------------------------------------------------------------------------------------------- 

$conteudo = file_get_contents('Aplicativo/Templates/painelcontrole.html');
$conteudo = str_replace('{template}', $tema, $conteudo);
$css      = Pagina::obtCSSCarregado();
$js       = Pagina::obtJSCarregado();
$conteudo = str_replace('{CABECALHO}', $css . $js . $conteudo);

echo $conteudo;

if (isset($_REQUEST['classe']))
{
    $metodo = isset($_REQUEST['metodo']) ?? NULL;
    NucleoAplicativo::carregaPagina($_REQUEST['classe'], $metodo, $_REQUEST);
}