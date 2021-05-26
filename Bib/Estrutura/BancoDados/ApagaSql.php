<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;

/**
 * Provides an Interface to create DELETE statements
 *
 * @version    7.1
 * @package    database
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ApagaSql extends DeclaracaoSql
{
    protected $sql;
    protected $criterio;    // stores the select criterio
    
    /**
     * Returns a string containing the DELETE plain statement
     * @param $preparado Return a prepared Statement
     */
    public function obtInstrucao( $preparado = FALSE )
    {
        // creates the DELETE instruction
        $this->sql  = "DELETE FROM {$this->entity}";
        
        // concatenates with the criterio (WHERE)
        if ($this->criterio)
        {
            $expressao = $this->criterio->dump( $preparado );
            if ($expressao)
            {
                $this->sql .= ' WHERE ' . $expressao;
            }
        }
        return $this->sql;
    }
}
