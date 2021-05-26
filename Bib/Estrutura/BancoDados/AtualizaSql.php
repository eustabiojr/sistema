<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;
/**
 * Provides an Interface to create UPDATE statements
 *
 * @version    7.1
 * @package    database
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class AtualizaSql extends DeclaracaoSql
{
    protected $sql;         // stores the SQL statement
    private $valoresColuna;
    private $varsPreparadas;
    
    /**
     * Assign values to the database columns
     * @param $coluna   Name of the database column
     * @param $valor    Value for the database column
     */
    public function defDadosLinha($coluna, $valor) 
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
     * @param $preparado If the value will be preparado
     * @return       Transformed Value
     */
    private function transforma($valor, $preparado = FALSE)
    {
        // store just scalar values (string, integer, ...)
        if (is_scalar($valor))
        {
            if (substr(strtoupper($valor),0,7) == '(SELECT')
            {
                $valor  = str_replace(['#', '--', '/*'], ['', '', ''], $valor);
                $resultado = $valor;
            }
            // if the value must not be escaped (NOESC in front)
            else if (substr($valor,0,6) == 'NOESC:')
            {
                $valor  = str_replace(['#', '--', '/*'], ['', '', ''], $valor);
                $resultado = substr($valor,6);
            }
            // if is a string
            else if (is_string($valor) and (!empty($valor)))
            {
                if ($preparado)
                {
                    $preparadoVar = ':par_'.self::obtParametroAleatorio();
                    $this->varsPreparadas[ $preparadoVar ] = $valor;
                    $resultado = $preparadoVar;
                }
                else
                {
                    $conn = Transacao::obt();
                    $resultado = $conn->quote($valor);
                }
            }
            else if (is_bool($valor)) // if is a boolean
            {
                $resultado = $valor ? 'TRUE': 'FALSE';
            }
            else if ($valor !== '') // if its another data type
            {
                if ($preparado)
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
     * Return the preparado vars
     */
    public function obtVarsPreparadas()
    {
        if ($this->criterio)
        {
            // "column values" preparado vars + "where" preparado vars
            return array_merge($this->varsPreparadas, $this->criterio->obtVarsPreparadas());
        }
        else
        {
            return $this->varsPreparadas;
        }
    }
    
    /**
     * Returns the UPDATE plain statement
     * @param $preparado Return a preparado Statement
     */
    public function obtInstrucao( $preparado = FALSE)
    {
        $this->varsPreparadas = array();
        // creates the UPDATE statement
        $this->sql = "UPDATE {$this->entidade}";
        
        // concatenate the column pairs COLUMN=VALUE
        if ($this->valoresColuna)
        {
            foreach ($this->valoresColuna as $coluna => $valor)
            {
                $valor = $this->transforma($valor, $preparado);
                $set[] = "{$coluna} = {$valor}";
            }
        }
        $this->sql .= ' SET ' . implode(', ', $set);
        
        // concatenates the criteria (WHERE)
        if ($this->criterio)
        {
            $this->sql .= ' WHERE ' . $this->criterio->despeja( $preparado );
        }
        
        // returns the SQL statement
        return $this->sql;
    }
}
