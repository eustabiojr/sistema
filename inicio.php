<?php
/********************************************************************************************
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 ********************************************************************************************/

require_once 'inic.php';
//------------------------------------------------------------------------------------------- 

$template = file_get_contents('Aplicativo/Templates/painelcontrole.html');
$conteudo = '';
$classe    = 'Inicio';

if ($_GET) {
    $classe = $_GET['classe'];
    if(class_exists($classe)) {
        try {
            $pagina = new $classe;
            ob_start();
            $pagina->exibe();
            $conteudo = ob_get_contents();
            ob_end_clean();
        } catch (Exception $e) {
            $conteudo = $e->getMessage() . '<br/>' . $e->getTraceAsString();
        }
    } else {
        $conteudo = "Classe <b>{$classe} não encontrada";
    }
}

$saida = str_replace('{conteudo}', $conteudo, $template);
$saida = str_replace('{classe}', $classe, $saida);
echo $saida;
