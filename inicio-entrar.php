<?php
/********************************************************************************************
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 ********************************************************************************************/

require_once 'inic.php';
//------------------------------------------------------------------------------------------- 

use Estrutura\Sessao\Sessao;

$conteudo = '';

new Sessao;

if (Sessao::obtValor('logado')) {
    $template = file_get_contents('Aplicativo/Templates/template.html');
    $classe = 'Inicio';
} else {
    $template = file_get_contents('Aplicativo/Templates/entrar.html');
    $classe = 'FormEntrar';
}

if (isset($_GET['classe']) AND Sessao::obtValor(('logado'))) {
    $classe = $_GET['classe'];
}

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
}

$saida = str_replace('{conteudo}', $conteudo, $template);
$saida = str_replace('{classe}', $classe, $saida);
echo $saida;
