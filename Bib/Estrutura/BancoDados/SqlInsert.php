<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 16/01/2022
 ***************************************************************************************/

namespace Estrutura\BancoDados;

use Exception;
use PDO;

/**
 * Prover uma interface para criar declarações INSERT
 * 
 * @version 1.0.0
 * @package bancodados
 */
class SqlInsert extends DeclaracaoSql {
    protected $sql;
    private $valoresColuna;
    private $varsPreparadas;

    /**
     * Método construtor
     */
    public function __construct()
    {
        $this->valoresColuna = [];
        $this->varsPreparadas = [];
    }

    /**
     * Atribui valores para as colunas do banco de dados
     * @param $coluna O nome da coluna do banco de dados
     * @param $valor O valor para a coluna do banco de dados
     */
    public function defDadosLinha($coluna, $valor) 
    {
        if (is_scalar($valor) OR is_null($valor)) {
            $this->valoresColuna[$coluna] = $valor;
        }
    }

    /**
     * Transforma o valor de acordo o seu tipo PHP antes de enviá-lo ao banco de dados
     * @param $valor O valor a ser transformado
     * @return O vaor transformado
     */
    private function transforma($valor, $preparado = FALSE)
    {
        if (is_scalar($valor)) {
            // caso seja uma string
            if (is_string($valor) AND (!empty($valor))) {
                if($preparado) {
                    $varPreparada = ':par_' . self::obtParamAleatorio();
                    $this->varsPreparadas[$varPreparada] = $valor;
                    $resultado = $varPreparada;
                } else {
                    $cnx = Transacao::obt();
                    $resultado = $cnx->quote($valor);
                }
            } else if (is_bool($valor)) {
                $resultado = $valor ? 'TRUE' : 'FALSE';
            } else if ($valor !== '') {
                if($preparado) {
                    $varPreparada = ':par_' . self::obtParamAleatorio();
                    $this->varsPreparadas[$varPreparada] = $valor;
                    $resultado = $varPreparada;
                } else {
                    $resultado = $valor;
                }
            } else {
                $resultado = "NULL";
            }
        } else if (is_null($valor)) {
            $resultado = "NULL";
        }
        # 
        return $resultado;
    } 

    /**
     * Este método não existe neste contexto de classe
     * 
     * @param $criterio O objeto Criterio, especificando filtros
     * @exception Exceção em qualquer caso
     */
    public function defCriterio(Criterio $criterio)
    {
        throw new Exception("Não foi possível chamar defCriterio de " . __CLASS__);
    }

    /**
     * Retorna as variáveis preparadas
     */
    public function obtVarsPreparadas()
    {
        return $this->varsPreparadas;
    }

    public function obtInstrucao($preparado = FALSE)
    {
        $this->varsPreparadas = array();
        $valoresColuna = $this->valoresColuna;
        if ($valoresColuna) {
            foreach ($valoresColuna as $chave => $valor) {
                $valoresColuna[$chave] = $this->transforma($valor, $preparado);
            }
        }

        $this->sql = "INSERT INTO {$this->entidade} (";
        $colunas = implode(', ', array_keys($valoresColuna)); # concatena os nomes das colunas 
        $valores = implode(', ', array_values($valoresColuna)); # concatena os valores das colunas 
        $this->sql .= $colunas . ')';
        $this->sql .= " VALUES ({$valores})";
        # retorna a string
        return $this->sql;
    }
}