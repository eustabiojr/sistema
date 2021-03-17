<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 16/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Sessao;

/**
 * Classe Sessao 
 */
class Sessao 
{
    /**
     * Método Construtor
     */
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * Método defValor
     */
    public static function defValor($var, $valor) 
    {
        $_SESSION[$var] = $valor;
    }

    /**
     * Método obtValor
     */
    public static function obtValor($var)
    {
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        }
    }

    /**
     * Método liberaSessao
     */
    public static function liberaSessao()
    {
        $_SESSION = array();
        session_destroy();
    }
}