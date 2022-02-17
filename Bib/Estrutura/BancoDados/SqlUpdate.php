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
 * Prover uma interface para criar declarações UPDATE
 * 
 * @version 1.0.0
 * @package bancodados
 */
class SqlUpdate extends DeclaracaoSql {
    protected $sql;
    private $valoresColuna;
    private $varsPreparadas;

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

    private function transforma($valor, $preparado = FALSE)
    {
        # armazena apenas valores escalares (string, inteiro, ...)
        if (is_scalar($valor)) {
            if(substr(strtoupper($valor),0,7) == '(SELECT') {
                $valor = str_replace(['#', '--', '/*'], ['','',''], $valor);
                $resultado = $valor;
            } 
            # se o valor não deve escapado (NAOESC na frente)
            else if(substr($valor,0,6) == 'NAOESC:') {
                $valor = str_replace(['#', '--', '/*'], ['','',''], $valor);
                $resultado = substr($valor, 6);
            }
            // caso seja uma string
            else if (is_string($valor) AND (!empty($valor))) {
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
     * Retorna as variáveis preparadas
     */
    public function obtVarsPreparadas()
    {
        if($this->criterio) {
            # variáveis preparadas "valores coluna" + variáveis preparadas "WHERE"
            return array_merge($this->varsPreparadas, $this->criterio->obtVarsPreparadas());
        } else {
            return $this->varsPreparadas;
        }
    }

    public function obtInstrucao($preparado = FALSE)
    {
        $this->varsPreparadas = array();
        
        # cria uma declaração UPDATE
        $this->sql = "UPDATE {$this->entidade}";

        # concatena os pares coluna COLUNA=VALOR
        if ($this->valoresColuna) {
            foreach($this->valoresColuna as $coluna => $valor) {
                $valor = $this->transforma($valor, $preparado);
                $grupo[] = "{$coluna} = {$valor}";
            }
        }

        $this->sql .= ' SET ' . implode(', ', $grupo);

        # concatena o critério (WHERE)
        if ($this->criterio) {
            $this->sql .= ' WHERE ' . $this->criterio->despeja($preparado);
        }

        # retorna a string
        return $this->sql;
    }
}