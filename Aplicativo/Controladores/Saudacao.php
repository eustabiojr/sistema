<?php
/**
 * Controlador Inicio
 * 
 * Data: 27/02/2021
 */
#namespace Aplicativo\Controladores;

use Estrutura\Controle\Pagina;

#  extends Controlador
class Saudacao extends Pagina
{
    public function __construct()
    {
        echo "<p>Construtor do controlador Saudação</p>\n";
    }

    public function Outro() 
    {
        echo "<p>Outra coisa qualquer!</p>\n";
    }
}