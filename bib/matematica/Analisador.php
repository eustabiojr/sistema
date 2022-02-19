<?php
namespace Matematica;

/**
 * Avalia expressão matemática
 * 
 * @author Adrean Boyadzhiev (netforce) <adrean.boyadzhiev@gmail.com>
 * 
 * Api original: 'Parser'
 */
class Analisador
{
    /**
     * Lexer que deve tokenizar a expressão matemática
     * 
     * @var Lexer
     */
    protected $analisador_lexico;

    /**
     * EstrategiaTraducao que deve traduzir do infix
     * notação expressão matemática para polimento-reverso
     * 
     * @var EstrategiaTraducao\InterfaceEstrategiaTraducao
     */
    protected $estrategiaTraducao;

    /**
     * Array de opçõs chave => valor 
     * 
     * @var array
     */
    private $opcoes = array(
        'estrategiaTraducao' => '\Matematica\EstrategiaTraducao\PatioManobra',
    );

    /**
     * Cria um analisador léxico (Lexer) que pode avaliar uma expressão matemática
     * 
     * @param Opções de array
     */
    public function __construct(array $opcoes = array())
    {
        $this->analisador_lexico = new AnalisadorLexico();
        $this->opcoes = array_merge($this->opcoes, $opcoes);
        $this->estrategiaTraducao = new $this->opcoes['estrategiaTraducao']();
    }

    /**
     * Avalia string representando uma expressão matemática
     * 
     * @param string @expressao
     * @return float
     */
    public function avalia($expressao)
    {   
        $analisador_lexico = $this->obtAnalisadorLexico();
        $fichas            = $analisador_lexico->tokeniza($expressao);

        $estrategiaTraducao = new \Matematica\EstrategiaTraducao\PatioManobra();

        return $this->avaliaRPN($estrategiaTraducao->traduz($fichas));
    }

    /**
     * Evalia array sequencia de fichas (tokens) em notação Polimento Reverso (RPN)
     * expressão representando matemática
     * 
     * @param array $fichasExpressao
     * @return float
     * @throws \InvalidArgumentException
     */
    private function avaliaRPN(array $fichasExpressao)
    {
        $pilha = new \SplStack();

        foreach ($fichasExpressao as $ficha) {
            $valorFicha = $ficha->getValue();
            if (\is_numeric($valorFicha)) {
                $pilha->push((float) $valorFicha);
                continue;
            }

            switch ($valorFicha) {
                case '+':
                    $pilha->push($pilha->pop() + $pilha->pop());
                break;
                case '-':
                    $n = $pilha->pop();
                    $pilha->push($pilha->pop() - $n);
                break;
                case '*':
                    $pilha->push($pilha->pop() * $pilha->pop());
                break;
                case '/':
                    $n = $pilha->pop();
                    $pilha->push($pilha->pop() / $n);
                break;
                case '%':
                    $n = $pilha->pop();
                    $pilha->push($pilha->pop() % $n);
                break;
                default:
                    throw new \InvalidArgumentException(sprintf('Operador inválido detectado: %s', $valorFicha));
                break;
            }   
        }
        return $pilha->top();
    }

    /**
     * Retorna analisador léximo
     * 
     * @return Lexico
     */
    public function obtAnalisadorLexico()
    {
        return $this->analisador_lexico;
    }
}