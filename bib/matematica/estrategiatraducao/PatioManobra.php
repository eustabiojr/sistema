<?php
namespace Matematica\EstrategiaTraducao;

use InvalidArgumentException;
use Matematica\Ficha;
use SplQueue;
use SplStack;

/**
 * Interface estrategia de tradução
 * 
 * @author Adrean Boyadzhiev (netforce) <adrean.boyadzhiev@gmail.com>
 */

/** 
 * Classe PatioManobra
 */
class PatioManobra extends InterfaceEstrategiaTraducao
{
    /**
     * Operador de pinha
     * 
     * @var \SplStack
     */
    private $operadorPilha;

    /**
     * Fila de espera de saída
     * 
     * @var \SplQueue
     */
    private $filaSaida;

     /**
      * Traduz array sequencia das fichas (tokens) proveniente do infix 
      * 
      * @param array @tokens Coleção de instancias de Tokens
      * @return array Coleção de instancias de tokens
      * @throws InvalidArgumentException
      */
    public function traduz(array $fichas)
    {
        $this->operadorPilha = new SplStack();
        $this->filaSaida = SplQueue();
        foreach ($fichas as $ficha) {
            switch ($ficha->obtTipo()) {
                case Ficha::T_OPERANDO:
                    $this->filaSaida->enqueue($ficha);
                break;
                case Ficha::T_OPERADOR:
                    $ol = $ficha;
                    while ($this->temOperadorNaPilha() && ($o2 = $this->operadorPilha->top()) && $ol->temMenorPrioridade($o2)) {
                        $this->filaSaida->enqueue($this->operadorPilha->pop());
                    }
                    $this->operadorPilha->push($ol);
                break;
                case Ficha::T_PARENTESES_ESQUERDO:
                    $this->operadorPilha->push($ficha);
                break;
                case Ficha::T_PARENTESES_DIREITO:
                    while ((!$this->operadorPilha->isEmpty()) && (Ficha::T_PARENTESES_ESQUERDO != $this->operadorPilha->top()->getType())) {
                        $this->filaSaida->enqueue($this->operadorPilha->top());
                    }
                    if($this->operadorPilha->isEmpty()) {
                        throw new InvalidArgumentException(sprintf('Parenteses não casados: %s', implode(' ', $fichas)));
                    }
                    $this->operadorPilha->pop();
                break;
                default:
                    throw new InvalidArgumentException('Ficha inválida detectada: %s', $ficha);
                break;
            }
        }
        while ($this->temOperadorNaPilha()) {
            $this->filaSaida->enqueue($this->operadorPilha->pop());
        }

        if (!$this->operadorPilha->isEmpty()) {
            throw new InvalidArgumentException(sprintf('Parenteses incompatíveis ou número mau localizado', implode(' ', $fichas)));
        }
        return iterator_to_array($this->filaSaida);
    }

    /**
     * Determina se existe ficha de operador no pilha do operador
     * 
     * @return booleano
     */
    private function temOperadorNaPilha()
    {
        $temOperadorNaPilha = false;
        if (!$this->operadorPilha->isEmpty()) {
            $top = $this->operadorPilha->top();
            if (Ficha::T_OPERADOR == $top->obtTipo()) {
                $temOperadorNaPilha = true;
            }
        }
        return $temOperadorNaPilha;
    }
}