<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 16/01/2022
 ***************************************************************************************/

namespace Estrutura\BancoDados;

/**
 * Prover uma interface para criar declarações DELETE
 * 
 * @version 1.0.0
 * @package bancodados
 */
class SqlDelete extends DeclaracaoSql {
    protected $sql; # a instrução SQL
    protected $criterio; # armazena o critério select

    public function obtInstrucao($preparado = FALSE)
    {
        # cria uma instrução DELETE
        $this->sql = "DELETE FROM {$this->entidade}";

        # concatena com o critério (WHERE)
        if ($this->criterio) {
            $expressao = $this->criterio->despeja($preparado);
            if ($expressao) {
                $this->sql .= ' WHERE ' . $expressao;
            }
        }
        return $this->sql;
    }
    
}