<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;

use Exception;

/**
 * Provides an Interface to create an MULTI INSERT statement
 *
 * @version    7.1
 * @package    database
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiInsereSql extends DeclaracaoSql
{
    protected $sql;
    private $linhas;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->linhas = [];
    }
    
    /**
     * Add a row data
     * @param $linha Row data
     */
    public function adicValoresLinha($linha)
    {
        $this->linhas[] = $linha;
    }
    
    /**
     * Transform the value according to its PHP type before send it to the database
     * @param $valor    Value to be transformed
     * @return       Transformed Value
     */
    private function transforma($valor)
    {
        // store just scalar values (string, integer, ...)
        if (is_scalar($valor))
        {
            // if is a string
            if (is_string($valor) and (!empty($valor)))
            {
                $conn = Transacao::obt();
                $resultado = $conn->quote($valor);
            }
            else if (is_bool($valor)) // if is a boolean
            {
                $resultado = $valor ? 'TRUE': 'FALSE';
            }
            else if ($valor !== '') // if its another data type
            {
                $resultado = $valor;
            }
            else
            {
                $resultado = "NULL";
            }
        }
        else if (is_null($valor))
        {
            $resultado = "NULL";
        }
        
        return $resultado;
    }
    
    /**
     * this method doesn't exist in this class context
     * @param $criterio A Criterio object, specifiyng the filters
     * @exception       Exception in any case
     */
    public function defCriterio(Criterio $criterio)
    {
        throw new Exception("Não foi possível chamar defCriterio de " . __CLASS__);
    }
    
    /**
     * Returns the INSERT plain statement
     * @param $preparado Return a prepared Statement
     */
    public function obtInstrucao( $preparado = FALSE )
    {
        if ($this->linhas)
        {
            $buffer = [];
            $colunas_alvo = implode(',', array_keys($this->linhas[0]));
            
            foreach ($this->linhas as $linha)
            {
                foreach ($linha as $chave => $valor)
                {
                    $linha[$chave] = $this->transforma($valor);
                }
                
                $valores_lista = implode(',', $linha);
                $buffer[] = "($valores_lista)";
            }
            
            $this->sql = "INSERT INTO {$this->entity} ($colunas_alvo) VALUES " . implode(',', $buffer);
            return $this->sql;
        }
    }
}
