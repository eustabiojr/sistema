<?php
/**
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 */
//------------------------------------------------------------------------------------------- 
/**
 * Proximo passo: carregar classe dinamicamente (ou seja com parametro passado pelo URI)
 */
# Carregamento automático das classes da estrutura do sistema
include_once "Bib/Estrutura/Nucleo/AutoCarregadorEstrutura.php";
$ce = new Estrutura\Nucleo\AutoCarregadorEstrutura();

/**
 * Primeiro argumento: prefixo no espaço de nomes
 * Segundo argumento: Diretório (Nota: A barra deve ser informado de acordo com o sistema hospedeiro)
 */
$ce->adicEspacoNome('Estrutura\Controle','Bib/Estrutura');
$ce->registra();

# Carregamento automático do aplicativo
include_once "Bib/Estrutura/Nucleo/AutoCarregadorAplic.php";
$ca = new Estrutura\Nucleo\AutoCarregadorAplic();
$ca->adicPasta('Aplicativo/Controladores');
$ca->registra();

//-------------------------------------------------------------------------------------------

class Teste {
    public function __construct()
    {
        echo "<p>Iniciada!...</p>" . PHP_EOL;
    }
}

$t = new Teste;

//------------------------------------------------------------------------------------------- 
/**
 * Nota: A classe 'Pagina' não deve ser instanciada diretamente. Essa classe deverá extendida
 * uma classe controlador.
 * 
 */
#$pgn = new Pagina;
#$pgn->operar();

//------------------------------------------------------------------------------------------- 
/*
echo '<pre>';
    print_r($crg->listaNamespace());
echo '</pre>'; */