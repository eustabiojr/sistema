<?php
/********************************************************************************************
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 ********************************************************************************************/
# definição do fuso-horário padrão
date_default_timezone_set('America/Bahia');

# Ambiente de execução
if (version_compare(PHP_VERSION, '8.0.0') == -1) {
    die('A versão mínima para executar esta aplicação é: 8.0.0');
}
/*
            echo '<pre>';
                print_r($objetos);
            echo '</pre>';
*/
//------------------------------------------------------------------------------------------- 
/**
 * Proximo passo: carregar classe dinamicamente (ou seja com parametro passado pelo URI)
 */
# Carregamento automático das classes da estrutura do sistema
include_once "Bib/Estrutura/Nucleo/AutoCarregadorEstrutura.php";
$ce = new Estrutura\Nucleo\AutoCarregadorEstrutura();
#Primeiro argumento: prefixo no espaço de nomes
#Segundo argumento: Diretório (Nota: A barra deve ser informado de acordo com o sistema hospedeiro)
$ce->adicEspacoNome('Estrutura','Bib/Estrutura');
$ce->registra();

# Carregamento automático do aplicativo
include_once "Bib/Estrutura/Nucleo/AutoCarregadorAplic.php";
$ca = new Estrutura\Nucleo\AutoCarregadorAplic();
$ca->adicPasta('Aplicativo/Controladores');
$ca->adicPasta('Aplicativo/Modelos');
$ca->registra();
//-------------------------------------------------------------------------------------------

# Vendor
$carregador = require 'vendor/autoload.php';
$carregador->register();

//------------------------------------------------------------------------------------------- 

$template = file_get_contents('Aplicativo/Templates/template.html');
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

