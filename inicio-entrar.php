<?php
/********************************************************************************************
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 ********************************************************************************************/

require_once 'inic.php';

use Estrutura\Sessao\Sessao;

$conteudo = '';

new Sessao;
Sessao::verificaAtividade();

# Acho que devemos atualizar o tempo da sessão quando.
/**
 * Acho que a lógica é a seguinte. Verificamos se a sessão ainda é válida. 
 * Se for válida, restauramos o tempo de sessão. Em caso negativo, definimos
 * a sessão como não logado.
 */

if (Sessao::obtValor('logado')) {

    $template = file_get_contents('Aplicativo/Templates/painelcontrole.html');
    $classe = 'Inicio';

} else {
    $template = file_get_contents('Aplicativo/Templates/entrar.html');
    $classe = 'FormEntrar';
}

if (isset($_GET['classe']) AND Sessao::obtValor(('logado'))) {
        $classe = $_GET['classe'];
}

//$css      = Pagina::obtCSSCarregado();
//$js       = Pagina::obtCSSCarregado();
//$conteudo = str_replace('{CABECALHO}', $css.$js, $conteudo);

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

#$saida = str_replace('{conteudo}', $conteudo, PHP_EOL . '<span>{conteudo}</span>');
#$conteudo = '';
$saida = str_replace('{conteudo}', $conteudo, $template);
$saida = str_replace('{classe}', $classe, $saida);
echo $saida;
