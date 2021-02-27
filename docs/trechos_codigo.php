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