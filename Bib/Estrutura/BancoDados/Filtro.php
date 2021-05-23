<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;

/**
 * Provides an interface to define filters to be used inside a criteria
 *
 * @version    7.1
 * @package    database
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Filtro extends Expressao
{
    private $variavel;
    private $operador;
    private $valor;
    private $valor2;
    private $varPreparadas;
    
    /**
     * Class Constructor
     * 
     * @param  $variavel = variavel
     * @param  $operador = operador (>, <, =, BETWEEN)
     * @param  $valor    = valor to be compared
     * @param  $valor2   = second valor to be compared (between)
     */
    public function __construct($variavel, $operador, $valor, $valor2 = NULL)
    {
        // store the properties
        $this->variavel = $variavel;
        $this->operador = $operador;
        $this->varsPreparadas = array();
        
        // transforma the valor according to its type
        $this->valor    = $valor;
        
        if ($valor2)
        {
            $this->valor2 = $valor2;
        }
    }
    
    /**
     * Transform the valor according to its PHP type
     * before send it to the database
     * @param $valor    Value to be transformaed
     * @param $preparado If the valor will be prepared
     * @return       Transformed Value
     */
    private function transforma($valor, $preparado = FALSE)
    {
        // if the valor is an array
        if (is_array($valor))
        {
            $bobo = array();
            // iterate the array
            foreach ($valor as $x)
            {
                // if the valor is an integer
                if (is_numeric($x))
                {
                    if ($preparado)
                    {
                        $varPreparada = ':par_'.$this->obtParametroAleatorio();
                        $this->varsPreparadas[ $varPreparada ] = $x;
                        $bobo[] = $varPreparada;
                    }
                    else
                    {
                        $bobo[] = $x;
                    }
                }
                else if (is_string($x))
                {
                    // if the valor is an string, add quotes
                    if ($preparado)
                    {
                        $varPreparada = ':par_'.$this->obtParametroAleatorio();
                        $this->varsPreparadas[ $varPreparada ] = $x;
                        $bobo[] = $varPreparada;
                    }
                    else
                    {
                        $bobo[] = "'$x'";
                    }
                }
                else if (is_bool($x))
                {
                    $bobo[] = ($x) ? 'TRUE' : 'FALSE';
                }
            }
            // convert the array into a string, splitted by ","
            $resultado = '(' . implode(',', $bobo) . ')';
        }
        // if the valor is a subselect (must not be escaped as string)
        else if (substr(strtoupper($valor),0,7) == '(SELECT')
        {
            $valor  = str_replace(['#', '--', '/*'], ['', '', ''], $valor);
            $resultado = $valor;
        }
        // if the valor must not be escaped (NOESC in front)
        else if (substr($valor,0,6) == 'NOESC:')
        {
            $valor  = str_replace(['#', '--', '/*'], ['', '', ''], $valor);
            $resultado = substr($valor,6);
        }
        // if the valor is a string
        else if (is_string($valor))
        {
            if ($preparado)
            {
                $varPreparada = ':par_'.$this->obtParametroAleatorio();
                $this->varsPreparadas[ $varPreparada ] = $valor;
                $resultado = $varPreparada;
            }
            else
            {
                // add quotes
                $resultado = "'$valor'";
            }
        }
        // if the valor is NULL
        else if (is_null($valor))
        {
            // the result is 'NULL'
            $resultado = 'NULL';
        }
        // if the valor is a boolean
        else if (is_bool($valor))
        {
            // the result is 'TRUE' of 'FALSE'
            $resultado = $valor ? 'TRUE' : 'FALSE';
        }
        // if the valor is a DeclaracaoSql object
        else if ($valor instanceof DeclaracaoSql)
        {
            // the result is the return of the obtInstrucao()
            $resultado = '(' . $valor->obtInstrucao() . ')';
        }
        else
        {
            if ($preparado)
            {
                $varPreparada = ':par_'.$this->obtParametroAleatorio();
                $this->varsPreparadas[ $varPreparada ] = $valor;
                $resultado = $varPreparada;
            }
            else
            {
                $resultado = $valor;
            }
        }
        
        // returns the result
        return $resultado;
    }
    
    /**
     * Return the prepared vars
     */
    public function obtVarsPreparadas()
    {
        return $this->varsPreparadas;
    }
    
    /**
     * Return the filter as a string expression
     * @return  A string containing the filter
     */
    public function despeja( $preparado = FALSE )
    {
        $this->varsPreparadas = array();
        $valor = $this->transforma($this->valor, $preparado);
        if ($this->valor2)
        {
            $valor2 = $this->transforma($this->valor2, $preparado);
            // concatenated the expression
            return "{$this->variavel} {$this->operador} {$valor} AND {$valor2}";
        }
        else
        {
            // concatenated the expression
            return "{$this->variavel} {$this->operador} {$valor}";
        }
    }
    
    /**
     * Retorna um parâmetro aleatório
     */
    private function obtParametroAleatorio()
    {
        return mt_rand(1000000000, 1999999999);
    }
}
