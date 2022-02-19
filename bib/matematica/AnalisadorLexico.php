<?php
namespace Matematica;

use InvalidArgumentException;

/**
 * Tokeniza expressão matemática
 * 
 * @author Adrean Boyadzhiev (netforce) <adrean.boyadzhiev@gmail.com>
 */
class AnalisadorLexico
{
    /**
     * Coleção de instancias de Token
     * 
     * @var array
     */
    protected $fichas;

    /**
     * Expressão matemática que deve ser tokenizada
     * 
     * @var string
     */
    protected $codigo;

    /**
     * Mapa de operadores matemático
     * 
     * @var array
     */
    protected static $mapaOperadores = array(
        '+' => array('prioridade' => 0, 'associatividade' => Operador::O_ASSOCIATIVO_ESQUERDO),
        '-' => array('prioridade' => 0, 'associatividade' => Operador::O_ASSOCIATIVO_ESQUERDO),
        '*' => array('prioridade' => 1, 'associatividade' => Operador::O_ASSOCIATIVO_ESQUERDO),
        '/' => array('prioridade' => 1, 'associatividade' => Operador::O_ASSOCIATIVO_ESQUERDO),
        '%' => array('prioridade' => 1, 'associatividade' => Operador::O_ASSOCIATIVO_ESQUERDO)
    );

    public function __construct()
    {
        $this->fichas = array();
    }

    /**
     * Tokeniza expressão matemática
     * 
     * @param tipo $codigo
     * @param array Instancia de coleço de tokens
     * @throws InvalidArgumentException
     */
    public function tokeniza($codigo)
    {
        $codigo = trim((string) $codigo);
        if (empty($codigo)) {
            throw new InvalidArgumentException('Não é possível tokenizar uma string vazia.');
        }

        $this->codigo = $codigo;
        $this->fichas = array();

        $arrayToken = explode(' ', $this->codigo);

        if (!\is_array($arrayToken) || empty($arrayToken)) {
            throw new InvalidArgumentException(
                sprintf('Não foi possível tokenizar a string: %s, por favor use " " (espaço em branco para o delimitador entre os tokens)', $this->codigo)
            );
        }

        foreach ($arrayToken as $t) {
            if (array_key_exists($t, static::$mapaOperadores)) {
                $ficha = new Operador(
                    $t,
                    static::$mapaOperadores[$t]['prioridade'],
                    static::$mapaOperadores[$t]['associatividade']
                );
            } else if (is_numeric($t)) {
                $ficha = new Ficha((float) $t, Ficha::T_OPERANDO);
            } else if ('(' === $t) {
                $ficha = new Ficha($t, Ficha::T_PARENTESES_ESQUERDO);
            } else if (')' === $t) {
                $ficha = new Ficha($t, Ficha::T_PARENTESES_DIREITO);
            } else if ('' === $t) {
                continue;
            } else {
                throw new InvalidArgumentException(\sprintf('Erro de sintaxe: token desconhecido "%s"', $t));
            }
            $this->fichas[] = $ficha;
        }
        return $this->fichas;
    }
}