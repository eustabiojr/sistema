<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 16/01/2022
 ***************************************************************************************/

namespace Estrutura\BancoDados;

/**
 * Fornece uma interface para definir filtros para ser usado em um critério
 * 
 * @package banco de dados
 */
class Filtro extends Expressao {
    private $variavel;
    private $operador;
    private $valor;
    private $valor2;
    private $varsPreparadas;

    /**
     * Construtor da classe
     * 
     * @param $variavel = variável
     * @param $operador = operador (>, <, =, BETWEEN)
     * @param $valor = valor a ser comparado
     * @param $valor2 = segundo valor a ser comparado (between)
     */
    public function __construct($variavel, $operador, $valor, $valor2 = NULL)
    {
        // armazenamos as propriedades aqui
        $this->variavel = $variavel;
        $this->operador = $operador;
        $this->varsPreparadas   = array();

        # transforma o valor de acordo o seu tipo
        $this->valor = $valor;

        if ($valor2) {
            $this->valor2 = $valor2;
        }
    }

    /**
     * Transforma o valor de acordo o seu tipo PHP antes 
     * de enviá-lo para o banco de dados
     * 
     * @param $valor O valor a ser transformado
     * @param $preparado Se o valor será preparado
     * @return O valor transformado
     * 
     * Primeiro verificamos se o parametro $valor é um array. Se for, verifamos o tipo do valor. Se ele é 
     * um numérico, uma cadeia de caracteres ou um booleano.
     */
    public function transforma($valor, $preparado = FALSE)
    {
        if(is_array($valor)) {
            $bobagem = array();

            // tera o array
            foreach($valor as $x) {
                if(is_numeric($x)) {                    
                    if($preparado) {
                        $varPreparada = ':par_' . $this->obtParamAleatorio();
                        $this->varsPreparadas[$varPreparada] = $valor;
                        $bobagem[] = $varPreparada;
                    } else {
                        $bobagem[] = $x;
                    }
                } else if(is_string($x)) {
                    # echo "<h2>É string</h2>" . PHP_EOL;
                    if($preparado) {
                        $varPreparada = ':par_' . $this->obtParamAleatorio();
                        $this->varsPreparadas[$varPreparada] = $valor;
                        $bobagem[] = $varPreparada;
                    } else {
                        $bobagem[] = "'$x'";
                    }
                } else if(is_bool($x)) {
                    # echo "<h2>É booleano</h2>" . PHP_EOL;
                    $bobagem[] = ($x) ? 'TRUE' : 'FALSE';
                }
            }
            # converte o array em uma string, separada por vírgula
            $resultado = '(' . implode(', ', $bobagem) . ')';
        
        # se não for array
        # caso o valor seja um sub select, não deve ser escapado como uma string
        } else if (substr(strtoupper($valor), 0, 7) == '(SELECT') {
            $valor = str_replace(['#', '--', '/*'], ['','',''], $valor);
            $resultado = $valor;
        # se no valor não deve ser escapado. O que fazemos aqui, é basicamente retirar caracteres especiais 
        # para o SQL e linguagem de programação. Este são caracteres de comentários.
        } else if(substr($valor, 0, 6) == 'NAOESC:') {
            $valor = str_replace(['#', '--', '/*'], ['','',''], $valor);
            $valor = substr($valor, 0, 6);
        # caso o valor seja uma string
        } else if(is_string($valor)) {
            if ($preparado) {
                $varPreparada = ':par_' . $this->obtParamAleatorio();
                $this->varsPreparadas[$varPreparada] = $valor;
                $resultado = $varPreparada;
            } else {
                $resultado = "'$valor'";
            }
        }
        # caso o valor seja NULO
        else if(is_null($valor)) {
            $resultado = 'NULL';
        }
        # se o valor é um booleano
        else if(is_bool($valor)) {
            $resultado = $valor ? 'TRUE' : 'FALSE';
        }
        # caso seja um objeto DeclaracaoSql
        else if($valor instanceof DeclaracaoSql) {
            $resultado = '(' . $valor->obtInstrucao() . ')';
        }
        else {
            if($preparado) {
                $varPreparada = ':par_' . $this->obtParamAleatorio();
                $this->varsPreparadas[$varPreparada] = $valor;
                $resultado = $varPreparada;
            } else {
                $resultado = $valor;
            }
        }
        # retorna o resultado
        return $resultado;
    }

    /** 
     * Retorna as variáveis preparadas
     */
    public function obtVarsPreparadas()
    {
        return $this->varsPreparadas;
    }

    /**
     * Retorna o filtro como uma expressão string
     * @return Uma string contendo o filtro
     */
    public function despeja($preparado = FALSE)
    {
        $this->varsPreparadas = array();
        $valor = $this->transforma($this->valor, $preparado);

        if($this->valor2) {
            $valor2 = $this->transforma($this->valor2, $preparado);

            # expressão concatenanda
            return "{$this->variavel} {$this->operador} {$valor} AND {$valor2}";
        } else {
            # expressão concatenanda
            return "{$this->variavel} {$this->operador} {$valor}";
        }
    }

    /**
     * Retorna um parâmetro aleatório
     */
    public function obtParamAleatorio()
    {
        return mt_rand(1000000000, 1999999999);
    }
}