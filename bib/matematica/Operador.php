<?php
namespace Matematica;

use InvalidArgumentException;

/**
 * Objeto valor representando um operador de expressão matemática
 * 
 */
class Operador extends Ficha
{
    const O_ASSOCIATIVO_ESQUERDO = -1;
    const O_ASSOCIATIVO_NENHUM   = 0;
    const O_ASSOCIATIVO_DIREITO  = 1;

    protected $prioridade;
    protected $associatividade;

    /**
     * Cria um novo "objeto valor" que representa um operador matemático
     * 
     * @param string $valor - string representando deste operador
     * @param inteiro $prioridade - valor de prioridade deste token
     * @param inteiro $associatividade que constantes associativas de operador
     * @throws InvalidArgumentException
     */
    public function __construct($valor, $prioridade, $associatividade)
    {
        if (!in_array($associatividade, array(self::O_ASSOCIATIVO_ESQUERDO, self::O_ASSOCIATIVO_NENHUM, self::O_ASSOCIATIVO_DIREITO))) {
            throw new InvalidArgumentException('Associatividade inválida: %s', $associatividade);
        }

        $this->prioridade = (int) $prioridade;
        $this->associatividade = (int) $associatividade;
        parent::__construct($valor, Ficha::T_OPERADOR);
    }

    /**
     * Retorna a associatividade deste operador
     * 
     * @return inteiro
     */
    public function obtAssociatividade()
    {
        return $this->associatividade;
    }

    /**
     * Retorna a prioridade deste operador
     * 
     * @return inteiro
     */
    public function obtPrioridade()
    {
        return $this->prioridade;
    }

    /**
     * Retorna verdadeiro se este operador possui menor prioridade do operador $o.
     * 
     * @param \Matematica\Operador $o
     * @return booleano
     */
    public function temMenorPrioridade(Operador $o) {
        $temMenorPrioridade = ((Operador::O_ASSOCIATIVO_ESQUERDO == $o->obtAssociatividade()
            && $this->obtPrioridade() == $o->obtPrioridade())
            || $this->obtPrioridade() < $o->obtPrioridade());

        return $temMenorPrioridade;
    }
}