<?php
/**
 * Controlador Inicio
 * 
 * Data: 26/02/2021
 */
namespace Aplicativo\Controladores;

use Estrutura\Controle\Pagina;

echo "KKKK";

#  extends Controlador
class Inicio extends Pagina
{
    public function __construct()
    {
        echo "<p>Construtor do controlador inicial</p>\n";
    }

    public function Saudacao() 
    {
        echo "<p>Seja bem-vindo!</p>\n";
    }
}