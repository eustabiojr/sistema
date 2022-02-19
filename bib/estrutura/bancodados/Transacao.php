<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 08/01/2022
 ***************************************************************************************/

namespace Estrutura\BancoDados;

use Closure;
use Estrutura\Historico\InterfaceHistorico;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;
use PDO;

final class Transacao {

    private static $cnx; # conexão ativa
    private static $bancodados; # nome do banco de dados
    private static $infobd; # nome do banco de dados
    private static $unicoid;
    private static $historico;
    private static $contador;

    private function __construc() { }
    
    public static function abre($bancodados, $infobd = NULL) 
    {
        if (!isset(self::$contador)) {
            self::$contador = 0;
        } else {
            self::$contador++;
        }
        # echo "<p>O contador é: <b>" . self::$contador . "</b></p>" . PHP_EOL;

        if($infobd) {
            self::$cnx[self::$contador] = Conexao::vertorBD($infobd);
            self::$infobd[self::$contador] = $infobd;
        } else {
            self::$cnx[self::$contador] = Conexao::abre($bancodados);
            self::$infobd[self::$contador] = Conexao::obtInforBD($bancodados);
        }
        
        self::$bancodados[self::$contador] = $bancodados;
        self::$unicoid[self::$contador] = uniqid();
        # echo "<p>O ID único é: <b>" . self::$unicoid[self::$contador] . "</b></p>" . PHP_EOL;
        
        self::$bancodados[self::$contador] = $bancodados;
        # echo "<p>O BD é: <b>" . self::$bancodados[self::$contador] . "</b></p>" . PHP_EOL;
        
        $condutor = self::$cnx[self::$contador]->getAttribute(PDO::ATTR_DRIVER_NAME);
        # echo "<p>O condutor é: <b>" . $condutor . "</b></p>" . PHP_EOL;
        
        if ($condutor !== 'dblib') {
            self::$cnx[self::$contador]->beginTransaction();
        }

        # 
        if (!empty(self::$infobd[self::$contador]['shist'])) {
            $classHistorico = self::$infobd[self::$contador]['shist'];
            if(class_exists($classHistorico)) {
                self::defHistorico(new $classHistorico);
            }
        } else {
            self::$historico[self::$contador] = NULL;
        }

        return self::$cnx[self::$contador];
    }
    
    public static function defHistorico(InterfaceHistorico $historico) 
    {
        if(isset(self::$historico[self::$contador])) {
            self::$historico[self::$contador] = $historico;
        } else {
            throw new Exception(NucleoTradutor::traduz('Não há transaçṍes ativas com o banco de dados') . ': ' . __METHOD__);
        }
    }

    public static function defFuncaoHist(Closure $historico)
    {
        if (isset(self::$cnx[self::$contador])) {
            self::$historico[self::$contador] = $historico;
        } else {
            throw new Exception(NucleoTradutor::traduz('Não há transaçṍes ativas com o banco de dados') . ': ' . __METHOD__);
        }
    }

    public static function hist($mensagem) 
    {
        if (!empty(self::$historico[self::$contador])) {
            $hist = self::$historico[self::$contador];

            # evitar histórico recursivo
            self::$historico[self::$contador] = NULL;

            if ($hist instanceof InterfaceHistorico) {
                $hist->escreve($mensagem);
            } else if($hist instanceof Closure) {
                $hist($mensagem);
            }
            # restaura função de histórico
            self::$historico[self::$contador] = $hist;
        }
    }

    public static function obt() 
    {
        if (isset(self::$cnx[self::$contador])) {
            return self::$cnx[self::$contador];
        }
    }

    public static function desfaz() 
    {
        if (isset(self::$cnx[self::$contador])) {
            # desfaz transação
            self::$cnx[self::$contador]->rollback();
            self::$unicoid[self::$contador] = NULL;
            self::$contador--;

            return true;
        }
    }
    public static function desfazTudo() 
    {
        $possui_conexao = true;

        while ($possui_conexao) {
            $possui_conexao = self::desfaz();
        }
    }

    public static function fecha() 
    {
        if (isset(self::$cnx[self::$contador])) {
            # desfaz transação
            self::$cnx[self::$contador]->commit();
            self::$unicoid[self::$contador] = NULL;
            self::$contador--;

            return true;
        }
    }

    public static function fechaTudo() 
    {
        $possui_conexao = true;

        while ($possui_conexao) {
            $possui_conexao = self::fecha();
        }
    }

    public static function obtBancodados() 
    {
        if (!empty(self::$bancodados[self::$contador])) {
            return self::$bancodados[self::$contador];
        }
    }

    public static function obtInfoBancodados()
    {
        if (!empty(self::$infobd[self::$contador]))
        {
            return self::$infobd[self::$contador];   
        }
    }

    public static function obtUnicoId()
    {
        if (!empty(self::$unicoid[self::$contador]))
        {
            return self::$unicoid[self::$contador];   
        }
    }
}

