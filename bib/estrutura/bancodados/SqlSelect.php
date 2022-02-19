<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 16/01/2022
 ***************************************************************************************/

namespace Estrutura\BancoDados;

use PDO;

/**
 * Prover uma interface para criar declarações SELECT
 * 
 * @version 1.0.0
 * @package bancodados
 */
class SqlSelect extends DeclaracaoSql {
    private $colunas;

    public function adicColuna($coluna)
    {
        $this->colunas[] = $coluna;
    }

    /**
     * Retorna a declaração SELECT como uma string de acordo o driver de banco de dados
     * @param $preparado Retorna a declaração preparada
     */
    public function obtInstrucao($preparado = FALSE)
    {
        $cnx = Transacao::obt();
        $condutor = $cnx->getAttribute(PDO::ATTR_DRIVER_NAME);

        if (in_array($condutor, array('mssql', 'dblib', 'sqlsrv'))) {
            return $this->obtInstrucaoSqlServer($preparado);
        }
        else {
            return $this->obtInstrucaoPadrao($preparado);
        }
    }

    /**
     * Retorna a declaração SELECT como uma string para o padrão de código aberto de driver de banco de dados
     * @param $preparado Retorna a declaração preparada
     */
    public function obtInstrucaoPadrao($preparado)
    {
        # cria a instrução SQL
        $this->sql = 'SELECT ';
        # concatena os nomes das colunas
        $this->sql .= implode(', ', $this->colunas);
        # concatena o nome da entidade
        $this->sql .= ' FROM ' . $this->entidade;

        # concatena os critério (WHERE)
        if ($this->criterio) {
            $expressao = $this->criterio->despeja($preparado);
            if ($expressao) {
                $this->sql .= ' WHERE ' . $expressao;
            }

            // obtem as propriedades do critério
            $ordem        = $this->criterio->obtPropriedade('order');
            $grupo        = $this->criterio->obtPropriedade('group');
            $limite       = (int) $this->criterio->obtPropriedade('limit');
            $deslocamento = (int) $this->criterio->obtPropriedade('offset');
            $direcao = in_array($this->criterio->obtPropriedade('direction'), array('asc', 'desc')) ? $this->criterio->obtPropriedade('direction') : '';

            if ($grupo) {
                $this->sql .= ' GROUP BY ' . $grupo;
            }
            if ($ordem) {
                $this->sql .= ' ORDER BY ' . $ordem . ' ' . $direcao;
            }
            if ($limite) {
                $this->sql .= ' LIMIT ' . $limite;
            }
            if ($deslocamento) {
                $this->sql .= ' OFFSET ' . $deslocamento;
            }
        }
        # retorna a declaração sql
        return $this->sql;
    }

    public function obtInstrucaoInterbase($preparado)
    {
        # cria a instrução SELECT
        $this->sql = 'SELECT ';

        if ($this->criterio) {
            $limite       = (int) $this->criterio->obtPropriedade('limit');
            $deslocamento = (int) $this->criterio->obtPropriedade('offset');

            if($limite) {
                $this->sql .= ' FIRST ' . $limite;
            }
            if($deslocamento) {
                $this->sql .= ' SKIP ' . $deslocamento;
            }
        }
    }

    /**
     * Retorna a declaração SELECT como uma string para drivers mssql/dblib
     * @param $preparado Retorna a declaração preparada
     */
    public function obtInstrucaoSqlServer($preparado)
    {
        # obtém a cláusula WHERE do objeto criterio
        if ($this->criterio) {
            $expressao = $this->criterio->despeja($preparado);

            # obtém as propriedades do critério
            $grupo        = $this->criterio->obtPropriedade('group');
            $ordem        = $this->criterio->obtPropriedade('order');
            $limite       = (int) $this->criterio->obtPropriedade('limit');
            $deslocamento = (int) $this->criterio->obtPropriedade('offset');
            $direcao      = in_array($this->criterio->obtPropriedade('direction'), array('asc', 'desc')) ? $this->criterio->obtPropriedade('direction') : '';
        }

        $colunas = implode(',', $this->colunas);

        if ((isset($limite) OR isset($deslocamento)) AND ($limite > 0 OR $deslocamento > 0)) {
            if (empty($ordem)) {
                $ordem = '(SELECT NULL)';
            }
            $this->sql = "SELECT {$colunas}
                FROM
                (
                    SELECT ROW_NUMBER() OVER (order by {$ordem} {$direcao}) AS __ROWNUMBER__,
                    {$colunas}
                    FROM {$this->entidade}";
            if(!empty($expressao)) {
                $this->sql .= "     WHERE {$expressao} ";
            }
            $this->sql .= " ) AS TAB2";
            if ((isset($limite) OR isset($deslocamento)) AND ($limite > 0 OR $deslocamento > 0)) {
                $this->sql .= " WHERE"; 
            }

            if ($limite > 0) {
                $total = $deslocamento + $limite;
                $this->sql .= " __ROWNUMBER__ <= {$total} ";

                if ($deslocamento) {
                    $this->sql .= " AND ";
                }
            }
            if ($deslocamento > 0) {
                $this->sql .= " __ROWNUMBER__ > {$deslocamento} ";
            }
        }
        else {
            $this->sql = 'SELECT ';
            $this->sql .= $colunas;
            $this->sql .= ' FROM ' . $this->entidade;
            if (!empty($expressao)) {
                $this->sql .= ' WHERE ' . $expressao;
            }

            if (isset($grupo) AND !empty($grupo)) {
                $this->sql .= ' GROUP BY ' . $grupo;
            }
            if (isset($ordem) AND !empty($ordem)) {
                $this->sql .= ' ORDEM BY ' . $ordem . ' ' . $direcao;
            }
        }
        return $this->sql;
    }
}