<?php

use Estrutura\BancoDados\Conexao;

/********************************************************************************************
 * Sistema
 * 
 * Data: 06/03/2021
 ********************************************************************************************/

 /**
  * Classe Transacao
  */
final class Transacao {
    private static $conexao;

    private function __construct() {}

    public static function abre($bd) 
    {
        if (empty(self::$conexao)) {
            self::$conexao = Conexao::abre($bd);
            self::$conexao->beginTransaction();
        }
    }

    public static function obt() 
    {
        return self::$conexao;
    }

    public static function desfaz() 
    {
        if (self::$conexao) {
            self::$conexao->desfaz();
            self::$conexao = NULL;
        }
    }

    public static function fecha() 
    {
        if (self::$conexao) {
            self::$conexao->confirma();
            self::$conexao = NULL;
        }
    }
}