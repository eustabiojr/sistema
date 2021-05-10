<?php
namespace Estrutura\Embrulho;

use Estrutura\Bugigangas\Gradedados\Gradedados;

/**
 * Bootstrap decorador grade de dados para Ageunet Framework
 * 
 * @version 0.1
 * @package warpper
 * @author Pablo Dall'Oglio
 * @copyright Copyright (c) 2006 Ageunet Solutions Ltd. (http://www.adiante.com.br)
 * @license 
 * @wrapper GGradeDados
 * @wrapper GGradeRapido
 */
class BootstrapEmbalgemGradedados
{
    private $decorado;

    /**
     * Método contrutor
     */
    public function __construct(Gradedados $gradedados) 
    {
        $this->decorado = $gradedados;
        $this->decorado->{'class'} = 'table table-striped table-hover';
        $this->decorado->{'type'}  = 'bootstrap';
    }

    /**
     * Duplica grade de dados
     */
    public function __clone()
    {
        $this->decorado = clone $this->decorado;
    }

    /**
     * Redireciona chamadas ao objeto decorado
     */
    public function __call($metodo, $parametros)
    {
        return call_user_func_array(array($this->decorado, $metodo), $parametros);
    }

    /**
     * Redireciona chamadas ao objeto decorado
     */
    public function __set($propriedade, $valor)
    {
        $this->decorado->$propriedade = $valor;
    }

    /**
     * Redireciona chamadas ao objeto decorado
     */
    public function __get($propriedade)
    {
        return $this->decorado->$propriedade;
    }

    /**
     * Exibe a grade de dados decorada
     */
    public function exibe()
    {
        $this->decorado->{'style'} .= ';border-collapse:collapse';

        $sessoes = $this->decorado->obtFilhos();
        if ($sessoes) {
            foreach ($sessoes as $sessao) {
                unset($sessao->{'class'});

                $linhas = $sessao->obtFilhos();
                if ($linhas) {
                    foreach ($linhas as $linha) {
                        if ($linha->{'class'} == 'ggrupo_gradedados') {
                            $linha->{'class'} = 'info';
                            $linha->{'style'} = 'user-select:none';
                        } else {
                            unset($linha->{'class'});

                            if (!empty($linha->{'nomeClasse'})) {
                                $linha->{'class'} = $linha->{'nomeClasse'};
                            }
                        }
                    }
                }
            }
        }
        $this->decorado->exibe();
    }
}