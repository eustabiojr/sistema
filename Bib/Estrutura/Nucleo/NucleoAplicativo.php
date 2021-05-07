<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Nucleo;

/**
 * Classe NucleoAplicativo
 * 
 * Estrutura básica do aplicativo
 */
class NucleoAplicativo
{
    private static $roteador;


    /**
     * Configura callback roteador
     */
    public static function defRoteador(Callable $callback)
    {
        self::$roteador = $callback;
    }

    /**
     * Obtém callback roteador
     */
    public static function obtRoteador()
    {
        return self::$roteador;
    }
}