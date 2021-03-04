<?php
/**
 * Controlador Inicio
 * 
 * Data: 26/02/2021
 */
namespace Aplicativo\Controladores;

#  extends Controlador
class Inicio 
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