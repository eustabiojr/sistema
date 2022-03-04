<?php
//------------------------------------------------------------------------------------------- 

use Estrutura\Nucleo\ConfigAplicativo;
use Estrutura\Nucleo\NucleoTradutor;

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
# configurações de idioma
NucleoTradutor::defIdioma($ini['geral']['idioma']);
TradutorAplicativo::defIdioma($ini['geral']['idioma']);
# definição do fuso-horário padrão
date_default_timezone_set($ini['geral']['fuso-horario']); 
# 
ConfigAplicativo::carrega($ini);
ConfigAplicativo::aplica();

# define constantes
define('NOME_APLICATIVO', $ini['geral']['aplicativo']);
define('OS', strtoupper(substr(PHP_OS, 0, 3)));
define('CAMINHO', dirname(__FILE__));
define('IDIOMA', $ini['geral']['idioma']);

// nome de sessão personalizado
session_name('PHPSESSID_'.$ini['geral']['aplicativo']);

//------------------------------------------------------------------------------------------- 
# Ambiente de execução
if (version_compare(PHP_VERSION, '8.1.0') == -1) {
    die(NucleoTradutor::traduz('A versão mínima exigida para PHP é &1', '8.1'));
}