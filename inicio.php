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

//------------------------------------------------------------------------------------------- 
use ageu\bib\Teste;
use ageu\bib\Inicio;
use ageu\controle\Pagina;

//------------------------------------------------------------------------------------------- 
include_once "./ageu/base/autocarregador.php";
$crg = new AutoCarregadorClasses();
$crg->adicNamespace("ageu\base");
$crg->adicNamespace("ageu\bib");
$crg->adicNamespace("ageu\controle");
$crg->registra();

(new Inicio);
//------------------------------------------------------------------------------------------- 
/**
 * Nota: A classe 'Pagina' não deve ser instanciada diretamente. Essa classe deverá extendida
 * uma classe controlador.
 * 
 * Antes de continuar, preciso implementar alguns recursos a mais no auto-carregamento de
 * classes.
 */
$pgn = new Pagina;
$pgn->operar();

//------------------------------------------------------------------------------------------- 


//-------------------------------------------------------------------------------------------
/*
echo '<pre>';
    print_r($crg->listaNamespace());
echo '</pre>'; */