<?php
//------------------------------------------------------------------------------------------- 

require_once 'Bib/Estrutura/Nucleo/CarregadorNucleo.php';

spl_autoload_register(array('Estrutura\Nucleo\CarregadorNucleo', 'autocarrega'));
Estrutura\Nucleo\CarregadorNucleo::carregaMapaClasse();
//-------------------------------------------------------------------------------------------
#(new ClasseTeste);

# Vendor
$carregador = require 'vendor/autoload.php';
$carregador->register();

# lê as configurações
$ini = parse_ini_file('Aplicativo/Config/aplicativo.ini', true);
# definição do fuso-horário padrão
date_default_timezone_set($ini['geral']['fuso-horario']); 
# 
ConfigAplicativo::carrega($ini);
ConfigAplicativo::aplica();


# define constantes
define('NOME_APLICATIVO', $ini['geral']['idioma']);
define('CAMINHO', dirname(__FILE__));
define('IDIOMA', $ini['geral']['idioma']);

//------------------------------------------------------------------------------------------- 
# Ambiente de execução
if (version_compare(PHP_VERSION, '8.0.0') == -1) {
    die('A versão mínima para executar esta aplicação é: 8.0.0');
}