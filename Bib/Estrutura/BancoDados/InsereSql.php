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
 * Provides an Interface to create an INSERT statement
 *
 * @version    7.1
 * @package    database
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class InsereSql extends DeclaracaoSql
{
    protected $sql;
    private $valoresColuna;
    private $varsPreparadas;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->valoresColuna = [];
        $this->varsPreparadas = [];
    }
    
    /**
     * Assign values to the database columns
     * @param $coluna   Name of the database column
     * @param $valor    Value for the database column
     */
    public function setRowData($coluna, $valor)
    {
        if (is_scalar($valor) OR is_null($valor))
        {
            $this->valoresColuna[$coluna] = $valor;
        }
    }
    
    /**
     * Transform the value according to its PHP type
     * before send it to the database
     * @param $valor    Value to be transformaed
     * @param $preparada If the value will be prepared
     * @return       Transformed Value
     */
    private function transforma($valor, $preparada = FALSE)
    {
        // store just scalar values (string, integer, ...)
        if (is_scalar($valor))
        {
            // if is a string
            if (is_string($valor) and (!empty($valor)))
            {
                if ($preparada)
                {
                    $varPreparada = ':par_'.self::obtParametroAleatorio();
                    $this->varsPreparadas[ $varPreparada ] = $valor;
                    $resultado = $varPreparada;
                }
                else
                {
                    $cnx = Transacao::obt();
                    $resultado = $cnx->quote($valor);
                }
            }
            else if (is_bool($valor)) // if is a boolean
            {
                $resultado = $valor ? 'TRUE': 'FALSE';
            }
            else if ($valor !== '') // if its another data type
            {
                if ($preparada)
                {
                    $varPreparada = ':par_'.self::obtParametroAleatorio();
                    $this->varsPreparadas[ $varPreparada ] = $valor;
                    $resultado = $varPreparada;
                }
                else
                {
                    $resultado = $valor;
                }
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
    public function setCriteria(Criterio $criterio)
    {
        throw new Exception("Não foi possível chamar defCriterio de " . __CLASS__);
    }
    
    /**
     * Return the prepared vars
     */
    public function obtVarsPreparadas()
    {
        return $this->varsPreparadas;
    }
    
    /**
     * Returns the INSERT plain statement
     * @param $preparada Return a prepared Statement
     */
    public function obtInstrucao( $preparada = FALSE )
    {
        $this->varsPreparadas = array();
        $valoresColuna = $this->valoresColuna;
        if ($valoresColuna)
        {
            foreach ($valoresColuna as $chava => $valor)
            {
                $valoresColuna[$chava] = $this->transforma($valor, $preparada);
            }
        }
        
        $this->sql = "INSERT INTO {$this->entidade} (";
        $colunas = implode(', ', array_keys($valoresColuna));   // concatenates the column names
        $valores  = implode(', ', array_values($valoresColuna)); // concatenates the column values
        $this->sql .= $colunas . ')';
        $this->sql .= " VALUES ({$valores})";
        // returns the string
        return $this->sql;
    }
}
