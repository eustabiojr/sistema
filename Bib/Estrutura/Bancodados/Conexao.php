<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/04/2020
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\BancoDados;

# Ou use \PDO por exemplo
use PDO;
use Exception;

/**
 * Classe Conexao
 */
 final class Conexao 
 {
    private static $caminho_config;
    private static $cache_cnx;

    private function __construct() {}

    /**
     * Abre conexão com o BD especificado
     */
    public static function abre($bancodados)
    {
        $bdInfo = self::obtInfoBD($bancodados);

        if (!$bdInfo) {
            throw new Exception("Arquivo '$bancodados' não encontrado");
        }

        return self::vetorBD($bdInfo);
    }

    /**
     * Método obtInfoBD
     */
    public static function vetorBD() {
        # Lê as informações contidas no arquivo de config
        $usuario  = isset($bd['usuario'])  ?? NULL;
        $senha    = isset($bd['senha'])    ?? NULL;
        $nome     = isset($bd['nome'])     ?? NULL;
        $servidor = isset($bd['servidor']) ?? NULL;
        $tipo     = isset($bd['tipo'])     ?? NULL;
        $porta    = isset($bd['porta'])    ?? NULL;

        # Descobre qual o tipo (condutor) de banco de dados a ser utilizado
        switch ($tipo) {
            case 'pgsql':
                $porta = $porta ? $porta : '5432';
                $conexao = new PDO("pgsql:dbname={$nome}; user={$usuario}; password={$senha}; host={$servidor}; port={$porta}; ");
            break;
            case 'mysql':
                $porta = $porta ? $porta : '3306';
                $conexao = new PDO("mysql:host={$servidor}; port={$porta}; dbname={$nome}", $usuario, $senha);
            break;
            case 'sqlite':
                $conexao = new PDO("sqlite:{$nome}");
                $conexao->query('PRAGMA foreign_keys = ON');
            break;
            case 'ibase':
                $conexao = new PDO("firebird:dbname={$nome}", $usuario, $senha);
            break;
            case 'oci8':
                $conexao = new PDO("oci:dbname={$nome}", $usuario, $senha);
            break;
            case 'mssql':
                $conexao = new PDO("dblib:host={$servidor}, 1433;dbname={$nome}", $usuario, $senha);
            break;
        }
        
        # Define para que o PDO lance exceções na ocorrência de erros 
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    }

    /**
     * Método obtInfoBD
     */
    public static function obtInfoBD($bd) {
        # Verifica se existe arquivo de configuração para este banco de dados
        $caminho = empty(self::$caminho_config) ? 'Aplicativo/Config' : self::$caminho_config;
        $arquivoi = "{$caminho}/{$bd}.ini";
        $arquivop = "{$caminho}/{$bd}.php";

        if (!empty(self::$cache_cnx[$bd])) {
            return self::$cache_cnx[$bd];
        }

        # Verifica se o arquivo de configuração existe do banco de dados existe
        if (file_exists($arquivoi)) {
            # Lê o INI e retorna um array
            $ini = parse_ini_file($arquivoi);
            self::$cache_cnx[$bd] = $ini;
            return $ini;
        } else if (file_exists($arquivop)) {
            $ini = require $arquivop;
            self::$cache_cnx[$bd] = $ini;
            return $ini;
        } else {
            return FALSE;
        }
    }

    /**
     * Método defInfoBD
     */
    public static function defInfoBD($bd, $info) {
        self::$cache_cnx[$bd] = $info;
    }
 }