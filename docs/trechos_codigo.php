<?php
/**
 * Trechos de códigos
 */
 
 //-----------------------------------------------------------------------------------------------------
class Inicio {
    public function __construct()
    {
        echo "<p>Construtor da classe</p>\n";
    }
}

function inicia() {
    if ($_GET) {
        echo "<p>É GET</p>\n\r";
        if (isset($_GET['classe'])) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;<p>Parâmetro classe está definido</p>\n\r";
        } else {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;<p>Parâmetro classe não está definido</p>\n\r";
        }
    } else {
        echo "<p>Não é GET</p>\n\r";
    }
}

 //-----------------------------------------------------------------------------------------------------
 # $u = "http" . (isset($_SERVER['HTTP_HOST']))

if (isset($_SERVER['HTTPS'])) {
    echo "<p>É HTTPS</p>\n\r";
} else {
    echo "<p>Não é HTTPS</p>\n\r"; 
}

$url_atual = "http" . (isset($_SERVER['HTTPS']) ? (($_SERVER['HTTPS']=="on") ? "s" : "") : "") . "://" .
 "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
echo $url_atual;
 //-----------------------------------------------------------------------------------------------------
/*
$url = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
echo "<p> A hospedagem é: " . $url . "</p>\r\n";
echo "<p> A URI é: " . $uri . "</p>\r\n";*/

 //-----------------------------------------------------------------------------------------------------
$param = strtolower($classe);

include_once "aplic/visoes/{$param}.php";

 //-----------------------------------------------------------------------------------------------------

 $raiz_docs = $_SERVER['DOCUMENT_ROOT'];

 echo "<p> A raiz de documentos é: " . $raiz_docs . "</p>" . PHP_EOL;

 //-----------------------------------------------------------------------------------------------------

 #spl_autoload_register(function($classe) {
    /*
    echo '<pre>';
    print($classe);
    echo '</pre>'; */
    /*
    $string1 = 'abc'; # casa                    | # ageu\bib\Teste
    $string2 = 'abcx'; # apto # apartamento     | # ageu\bib\Teste
    echo strncmp($string1, $string2, 4); */

    # $c = "ageu\bib\Teste";
    #$ce = explode("\\", $classe);
    #$nome_classe = array_pop($ce);
    # $cer = array_reverse($ce);
    /*
    echo "<br>A classe é: " . $nome_classe;
    echo '<pre>';
    print_r($ce);
    echo '</pre>';*/

    #$caminho =  implode('/', $ce);

    /**
     * Retorno: ageu\bib\Teste
     * 
     * Preciso separar o nome da classe do nome no caminho. Qual a melhor forma de fazer
     * isso? a) poderia explodir com base na barra invertida; a) poderia pegar o tamanho 
     * da última palavra, pegar o comprimento da mesma para fazer a separação.
     * 
     * No caso: O caminho = ageu\bib\ e a classe = Teste
     */
    #$caminho = '/' . $caminho . '/';
    #$fonte = $_SERVER['DOCUMENT_ROOT'] . $caminho . strtolower($nome_classe) . '.php';
    #echo '<pre>' . $fonte . '</pre>' . PHP_EOL;
    #include_once $fonte;
#});

 //-----------------------------------------------------------------------------------------------------
class MinhaClasse {
    private $variavel;

    public function obtNomeClasse() {
        return get_class($this);
    }
}

$mc = new MinhaClasse;
echo "<br>O nome da classe é: " . $mc->obtNomeClasse();

//------------------------------------------------------------------------------------------- 
$dados = include_once("aplic/config/exemplo.php");
echo "<p>" . $dados['nome'] . "</p>" . PHP_EOL;

//------------------------------------------------------------------------------------------- 
error_reporting(E_ALL);
function incrementa(&$var) {
    $var++;
}

function imprime($param) {
    echo "<p>" . $param . "</p>" . PHP_EOL;
}

$a = "Alguma coisa qualquer";
call_user_func('imprime', $a);
