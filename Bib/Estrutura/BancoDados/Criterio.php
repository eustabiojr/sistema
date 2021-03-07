<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;

/**
 * Classe Criterio
 */
class Criterio {
    # propriedades da classe
    private $filtros;
    private $propriedades;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        $this->filtros = array();
    }

    /**
     * Método adic
     */
    public function adic($variavel, $operador_comparacao, $valor, $operador_logico = 'AND')
    {
        # na primeira vez não precisamos concatenar
        if (empty($this->filtros)) {
            $operador_logico = NULL;
        }

        $this->filtros[] = [$variavel, $operador_comparacao, $this->transformador($valor), $operador_logico];
    }

    /**
     * Método transformador
     */
    private function transformador($valor) 
    {
        # no caso de array
        if (is_array($valor)) {
            foreach($valor as $x) {
                if (is_integer($x)) {
                    $bobagem[] = $x;
                } else if (is_string($x)) {
                    $bobagem[] = "'$x'";
                }
            }
            # converte o array em string separada por ","
            $resultado = '(' . implode(',', $bobagem) . ')';
        } else if(is_string($valor)) {
            $resultado = "'$valor'";
        } else if(is_null($valor)) {
            $resultado = 'NULL';
        } else if(is_bool($valor)) {
            $resultado = $valor ? 'TRUE' : 'FALSE';
        } else {
            $resultado = $valor;
        }
        return $resultado;
    }

    /**
     * Método despeja
     */
    public function despeja() 
    {
        # concatena a lista de expressões
        if (is_array($this->filtros) AND count($this->filtros) > 0) {
            $resultado = '';
            foreach ($this->filtros as $filtro) {
                $resultado .= $filtro[3] . ' ' . $filtro[0] . ' ' . $filtro[1] . ' ' . $filtro[2] . ' ';
            }
            $resultado = trim($resultado);
            return "({$resultado})";
        }
    }

    /**
     * Método defPropriedade
     */
    public function defPropriedade($propriedade, $valor)
    {
        if (isset($valor)) {
            $this->propriedades[$propriedade] = $valor;
        } else {
            $this->propriedades[$propriedade] = NULL;
        }
    }

    /**
     * Método obtPropriedade
     */
    public function obtPropriedade($propriedade)
    {
        if (isset($this->propriedades[$propriedade])) {
            return $this->propriedades[$propriedade];
        }
    }
}
