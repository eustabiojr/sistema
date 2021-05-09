<?php
namespace Matematica;

use InvalidArgumentException;

/**
 * Avalia expressão matemática
 * 
 * @author Adrean Boyadzhiev (netforce) <adrean.boyadzhiev@gmail.com>
 * 
 */
class Ficha
{
    const T_OPERADOR = 1;
    const T_OPERANDO  = 2;
    const T_PARENTESES_ESQUERDO = 3;
    const T_PARENTESES_DIREITO  = 4;

    /**
     * String de representação para este token
     * 
     * @var string
     */
    protected $valor;

    /**
     * Token tipo um de Ficha::T_* constantes
     * 
     * @var inteiro
     */
    protected $tipo;

    /**
     * Cria um novo "Objeto Valor" que representa um token
     * 
     * @param inteiro | string valor
     * @param inteiro $tipo
     * @throws \InvalidArgumentException
     */
    public function __construct($valor, $tipo)
    {
        $tiposFichas = array(
            self::T_OPERADOR,
            self::T_OPERANDO,
            self::T_PARENTESES_ESQUERDO,
            self::T_PARENTESES_DIREITO
        );

        if (!in_array($tipo, $tiposFichas, true)) {
            throw new InvalidArgumentException(sprintf('Tipo de ficha inválido: %s', $tipo));
        }

        $this->valor = $valor;
        $this->tipo  = $tipo;
    }

    /**
     * Retorna o valor da ficha (token)
     * 
     * @return string|inteiro
     */
    public function obtValor()
    {
        return $this->valor;
    }

    /**
     * Retorna o tipo da ficha (token)
     * 
     * @return inteiro
     */
    public function obtTipo()
    {
        return $this->tipo;
    }
}