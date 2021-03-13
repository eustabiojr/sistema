<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 06/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;

use Estrutura\Historico\Historico;

 /**
  * Classe Transacao
  */
final class Transacao {

    private static $conexao;
    private static $historico; 

    # Método construtor
    private function __construct() {}

    /**
     * Método abre
     */
    public static function abre($bd) 
    {
        if (empty(self::$conexao)) {
            self::$conexao = Conexao::abre($bd);
            # inicia a transação
            self::$conexao->beginTransaction();
            # desliga o histórico de SQL
            self::$historico = NULL;
        }
    }

    /**
     * Método obt
     */
    public static function obt() 
    {
        return self::$conexao;
    }

    /**
     * Método desfaz
     */
    public static function desfaz() 
    {
        if (self::$conexao) {
            // desfaz as operações realizadas durante a transação
            self::$conexao->rollback();
            self::$conexao = NULL;
        }
    }

    /**
     * Método fecha
     */
    public static function fecha() 
    {
        if (self::$conexao) {
            # aplica as operações realizadas durante a transação
            self::$conexao->commit();
            self::$conexao = NULL;
        }
    }

    /**
     * Método estático defRegistrador - Define o histórico de operações
     */
    public static function defHistorico(Historico $historico)
    {
        self::$historico = $historico;
    }

    /**
     * Método hist (histórico)
     */
    public static function hist($mensagem)
    {
        if (self::$historico) {
            self::$historico->escreve($mensagem);
        }
    }
}