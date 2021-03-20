<?php
/*****************************************************************************************
 * Controlador Inicio
 * 
 * Data: 26/02/2021
 *****************************************************************************************/

use Estrutura\Controle\Pagina;

class Inicio extends Pagina
{
    public function __construct()
    {
        #echo "<p>Construtor do controlador inicial</p>\n";
    }

    public function Saudacao() 
    {
        echo "<p>Seja bem-vindo!</p>\n";
    }
}