<?php
/**
 * Inicio
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 */

# inclusões
include_once "./ageu/base/autocarregador.php";

use ageu\bib\Teste;
use ageu\bib\Inicio;

$classe = ucfirst($_GET['classe'] ?? 'Inicio');
$nomeClasse = __NAMESPACE__ . $classe;

$ob = new Teste; # Teste # Inicio;

//
#use ageu\bib;
#use aplic\controladores\Inicio;
#use aplic\controladores\Saudacao;

#include_once "./ageu/bib/pagina.php";
// include_once "./aplic/controladores/inicio.php";
// include_once "./aplic/controladores/saudacao.php";

// $classe = ucfirst($_GET['classe'] ?? 'Inicio');

#print_r($classe);
// $nomeClasse = "\\aplic\controladores\\" . $classe;
#$nomeClasse = __NAMESPACE__ . $classe;
#print_r($nomeClasse);
// $ic = new $nomeClasse;
