<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 16/01/2022
 ***************************************************************************************/

namespace Estrutura\BancoDados;

/**
 * Prover uma interface abstrata para criar declarações SQL
 * 
 * @version 1.0.0
 * @package bancodados
 */
abstract class DeclaracaoSql {
    protected $sql;
    protected $criterio;
    protected $entidade;

    /**
     * define o nome da entidade do banco de dados
     * @param $entidade O nome da entidade do banco de dados 
     */
    final public function defEntidade($entidade)
    {
        $this->entidade = $entidade;
    }

    /**  
    * Retorna o nome da entidade do banco de dados
    */
    final public function obtEntidade()
    {
        return $this->entidade;
    }

    /**
    * Define um criterio de seleção
    * @param $criterio Um objeto Criterio, especificando os filtros 
    */
    public function defCriterio(Criterio $criterio)
    {
        $this->criterio = $criterio;
    }

    /**
     * Retorna um parâmetro aleatório
     */
    public static function obtParamAleatorio()
    {
        return mt_rand(1000000000, 1999999999);
    }

    // força a reescrita do método em classes filhas
    abstract function obtInstrucao();
}