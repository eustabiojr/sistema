<?php
//------------------------------------------------------------------------------------------- 

require_once 'bib/estrutura/nucleo/CarregadorNucleo.php';

spl_autoload_register(array('estrutura\nucleo\CarregadorNucleo', 'autocarrega'));
Estrutura\Nucleo\CarregadorNucleo::carregaMapaClasse();
//-------------------------------------------------------------------------------------------
#(new ClasseTeste);

# Vendor
$carregador = require 'vendor/autoload.php';
$carregador->register();

# lê as configurações
$ini = parse_ini_file('aplicativo/config/aplicativo.ini', true);
# definição do fuso-horário padrão
date_default_timezone_set($ini['geral']['fuso-horario']); 
# 
ConfigAplicativo::carrega($ini);
ConfigAplicativo::aplica();


# define constantes
define('NOME_APLICATIVO', $ini['geral']['idioma']);
define('OS', strtoupper(substr(PHP_OS, 0, 3)));
define('CAMINHO', dirname(__FILE__));
define('IDIOMA', $ini['geral']['idioma']);

//------------------------------------------------------------------------------------------- 
# Ambiente de execução
if (version_compare(PHP_VERSION, '8.0.0') == -1) {
    die('A versão mínima para executar esta aplicação é: 8.0.0');
}