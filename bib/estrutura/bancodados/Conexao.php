<?php
/**
 * Novo Projeto
 * 
 * Data: 07/11/2021
 ****************************************************************************/

namespace Estrutura\BancoDados;

use Estrutura\Nucleo\NucleoTradutor;
use Exception;
use PDO;

final class Conexao {
    # propriedades
    private static $cnx;
    private static $caminho_cfg;
    private static $cache_conexao;

    public function __construct() { }

    public static function abre($banco) {
        $inforBD = self::obtInforBD($banco);
        if (!$inforBD) {
            throw new Exception(NucleoTradutor::traduz('Arquivo não encontrado' . ': ' . "'{$banco}.ini'"));
        }
        return self::vertorBD($inforBD);
    }

    public static function vertorBD($banco) {
        $servidor = $banco['servidor'] ?? null;
        $nome     = $banco['nome']     ?? null;
        $porta    = $banco['porta']    ?? null;
        $usuario  = $banco['usuario']  ?? null;
        $senha    = $banco['senha']    ?? null;
        $tipo     = $banco['tipo']     ?? null;
        $carac    = $banco['carac']    ?? null;
        $echave   = $banco['echave']   ?? null;

        switch($tipo) {
            case 'sqlite':
                self::$cnx = new PDO("sqlite:{$nome}");
                if (is_null($echave) OR $echave == '1') {
                    self::$cnx->query('PRAGMA foreign_keys = ON');
                }
            break;
            case 'pgsql':
                $porta = $porta ?? '5432';
                self::$cnx = new PDO("pgsql:host={$servidor};port={$porta};dbname={$nome};user={$usuario};password={$senha}");
                if (!empty($carac)) {
                    self::$cnx->exec("SET CLIENT_ENCODING TO '{$carac}';"); # pesquisar
                }
            break;
            case 'interbase':
            case 'firebird':
                $string_bd = empty($porta) ? "{$servidor}:{$nome}" : "{$servidor}/{$porta}:{$nome}";
                $conjcarac = $carac ? ";charset={$carac}" : '';
                self::$cnx = new PDO("firebird:dbname={$string_bd}{$conjcarac}", $usuario, $senha);
            break;
            case 'mysql':
                $porta = $porta ?? '3306';
                if ($carac == 'ISO') {
                    self::$cnx = new PDO("mysql:host={$servidor};dbname={$nome};port={$porta}", $usuario, $senha);
                } else {
                    self::$cnx = new PDO("mysql:host={$servidor};dbname={$nome};port={$porta}", $usuario, $senha, 
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                }
            break;
            default:
                throw new Exception(NucleoTradutor::traduz('Driver não encontrado') . ': ' . $tipo);
            break;
        }

        self::$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$cnx; 
    } 

    public static function obtInforBD($nome) {

        $caminho = empty(self::$caminho_cfg) ? 'Aplicativo/Configuracao' : self::$caminho_cfg; 
        $arquivo_ini = "{$caminho}/{$nome}.ini";
        $arquivo_php = "{$caminho}/{$nome}.php";

        if (!empty(self::$cache_conexao[$nome])) {
            return self::$cache_conexao[$nome];
        }

        // verifica se existe arquivo de configuração para este banco de dados
        if (file_exists($arquivo_ini)) {
            $ini = parse_ini_file($arquivo_ini);
            self::$cache_conexao[$nome] = $ini;
            return $ini;
        } else if (file_exists($arquivo_php)) {
            $php = require $arquivo_php;
            self::$cache_conexao[$nome] = $php;
            return $php;
        } else {
            return false;
        }
    }

    public static function defInfoBancoDados($bancodados, $info)
    {
        self::$cache_conexao[$bancodados] = $info;
    }
}