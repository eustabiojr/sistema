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
# inclusões
include_once "./ageu/base/autocarregador.php";

use ageu\bib\Teste;
use ageu\bib\Inicio;
use ageu\base\Pagina;

$classe = ucfirst($_GET['classe'] ?? 'Inicio');
$nomeClasse = __NAMESPACE__ . $classe;

//------------------------------------------------------------------------------------------- 

$crg = new AutoCarregadorClasses();
$crg->adicNamespace("ageu\base");
$crg->adicNamespace("ageu\bib");
$crg->inicializa($classe);
#$espaco_nome = $crg->listaNamespace();

$ob = new Teste; # Teste # Inicio;
$pg = new Pagina;

/*
echo '<pre>';
    print_r($crg->listaNamespace());
echo '</pre>'; */


