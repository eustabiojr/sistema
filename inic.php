<?php

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

# lê as configurações
$ini = parse_ini_file('Aplicativo/Config/aplicativo.ini', true);

# definição do fuso-horário padrão
date_default_timezone_set($ini['geral']['fuso-horario']); 

# define constantes
define('NOME_APLICATIVO', $ini['geral']['idioma']);
define('CAMINHO', dirname(__FILE__));
define('IDIOMA', $ini['geral']['idioma']);

//------------------------------------------------------------------------------------------- 
# Ambiente de execução
if (version_compare(PHP_VERSION, '8.0.0') == -1) {
    die('A versão mínima para executar esta aplicação é: 8.0.0');
}