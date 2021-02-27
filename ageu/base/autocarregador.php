<?php
/**
 * Auto-carregador de classes
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 */

spl_autoload_register(function($classe) {
    /*
    echo '<pre>';
    print($classe);
    echo '</pre>'; */
    /*
    $string1 = 'abc'; # casa                    | # ageu\bib\Teste
    $string2 = 'abcx'; # apto # apartamento     | # ageu\bib\Teste
    echo strncmp($string1, $string2, 4); */

    # $c = "ageu\bib\Teste";
    $ce = explode("\\", $classe);
    $nome_classe = array_pop($ce);
    # $cer = array_reverse($ce);
    /*
    echo "<br>A classe é: " . $nome_classe;
    echo '<pre>';
    print_r($ce);
    echo '</pre>';*/

    $caminho =  implode('/', $ce);

    /**
     * Retorno: ageu\bib\Teste
     * 
     * Preciso separar o nome da classe do nome no caminho. Qual a melhor forma de fazer
     * isso? a) poderia explodir com base na barra invertida; a) poderia pegar o tamanho 
     * da última palavra, pegar o comprimento da mesma para fazer a separação.
     * 
     * No caso: O caminho = ageu\bib\ e a classe = Teste
     */
    $caminho = '/' . $caminho . '/';
    $fonte = $_SERVER['DOCUMENT_ROOT'] . $caminho . strtolower($nome_classe) . '.php';
    #echo '<pre>' . $fonte . '</pre>' . PHP_EOL;
    include_once $fonte;
});


/**
 * Pretendo criar uma variável array para registrar os namespace que serão consultados 
 * durante o carregamento das classes
 */
/*
class AutoCarregadorClasses {

    public function inicializa($classe)
    {
        spl_autoload_register(function($classe) {

            $caminho = "ageu/bib";
            include_once $caminho . $classe;
        });
    }
}

*/
