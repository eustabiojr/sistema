<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 21/01/2022
 ***************************************************************************************/

namespace Estrutura\BancoDados;

use Exception;
use PDO;

/**
 * Prover uma interface para criar múltiplas declarações INSERT
 * 
 * @version 1.0.0
 * @package bancodados
 */
class SqlMultiInsert extends DeclaracaoSql {
    protected $sql;
    private $linhas;

    /**
     * Método construtor
     */
    public function __construct()
    {
        $this->linhas = [];
    }

    /**
     * Adiciona uma linha de dados
     * @param $linha Linha de dados
     */
    public function adicValoresLinhas($linha)
    {
        $this->linhas[] = $linha;
    }

    /**
     * Transforma o valor de acordo o seu tipo PHP antes de enviá-lo ao banco de dados
     * @param $valor O valor a ser transformado
     * @return O vaor transformado
     */
    private function transforma($valor)
    {
        # armazena somente valores escalares (tais como string, integer...)
        if (is_scalar($valor)) {
            // caso seja uma string
            if (is_string($valor) AND (!empty($valor))) {
                $cnx = Transacao::obt();
                $resultado = $cnx->quote($valor);
            # caso seja um valor booleano
            } else if (is_bool($valor)) {
                $resultado = $valor ? 'TRUE' : 'FALSE';
            } else if ($valor !== '') {
                $resultado = $valor;
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

    public function obtInstrucao($preparado = FALSE)
    {
        if($this->linhas) {
            $amortecedor = [];
            $colunas_alvo = implode(',', array_keys($this->linhas[0]));

            foreach($this->linhas as $chave => $valor) {
                $linha[$chave] = $this->transforma($valor);
            }

            $lista_valores = implode(',', $linha);
            $amortecedor[] = "($lista_valores)";
        }

        $this->sql = "INSERT INTO {$this->entidade} ($colunas_alvo) VALUES " . implode(',', $amortecedor);
        # retorna a string
        return $this->sql;
    }
}