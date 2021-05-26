<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;

use Estrutura\BancoDados\Criterio;

/**
 * Provides an abstract Interface to create a SQL statement
 *
 * @version    7.1
 * @package    database
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
abstract class DeclaracaoSql
{
    protected $sql;         // stores the SQL instruction
    protected $criterio;    // stores the select criterio
    protected $entidade;
    
    /**
     * defines the database entidade name
     * @param $entidade Name of the database entidade
     */
    final public function defEntidade($entidade)
    {
        $this->entidade = $entidade;
    }
    
    /**
     * Returns the database entidade name
     */
    final public function obtEntidade()
    {
        return $this->entidade;
    }
    
    /**
     * Define a select criterio
     * @param $criterio  An Criterio object, specifiyng the filters
     */
    public function defCriterio(Criterio $criterio)
    {
        $this->criterio = $criterio;
    }
    
    /**
     * Returns a random parameter
     */
    protected static function obtParametroAleatorio()
    {
        return mt_rand(1000000000, 1999999999);
    }
    
    // force method rewrite in child classes
    abstract function obtInstrucao();
}
