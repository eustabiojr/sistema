<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 08/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Controle;

use Exception;
use ReflectionMethod;

/**
 * Class Acao
 */
class Acao implements InterfaceAcao {
    private $acao;
    private $param;

    /**
     * Método __construct
     */
    public function __construct(Callable $acao)
    {
        $this->acao = $acao;

        $this->acao = $acao;
        if (!is_callable($this->acao)) {
            $string_acao = $this->paraString();
            throw new Exception('Método {__METHOD__} deve receber um parâmetro do tipo Callback'). ' <br> '.
                                ('Verifique se a ação {$string_acao} existe');
        }

        if(!empty($parametros)) {
            $this->defParametros($parametros);
        }
    }

        /**
     * Retorna os campos usados nos parâmetros
     */
    public function obtCampoParametros()
    {
        $campo_parametros = [];

        if ($this->param) {
            foreach ($this->param as $parametro) {
                if (substr($parametro, 0, 1) == '{' && substr($parametro, -1) == '}') {
                    $campo_parametros[] = substr($parametro, 1, -1);
                }
            }
        }
        return $campo_parametros;
    }

    /**
     * Retorna a ação como um string
     */
    public function paraString()
    {
        $string_acao = '';
        if (is_string($this->acao)) {
            $string_acao = $this->acao;
        } else if (is_array($this->acao)) {
            if (is_string($this->acao)) {
                if (is_object($this->acao[0])) {
                    $string_acao = get_class($this->acao[0]) . '::' . $this->acao[1];
                } else {
                    $string_acao = $this->acao[0] . "::" . $this->acao[1];
                }
            }
            return $string_acao;
        }
    }

    /**
     * Adiciona um parâmetro para a ação
     * @param $param - nome do parâmetro
     * @param $valor - valor do parâmetro
     */
    public function defParametro($param, $valor)
    {
        $this->param[$param] = $valor;
    }

    /**
     * Define os parâmetros para a ação
     * @param $parametros - array de parâmetro
     */
    public function defParametros($parametros)
    {
        if (is_array($parametros)) {
            # Não sobreescreve a ação
            unset($parametros['classe']);
            unset($parametros['metodo']);
            $this->param = $parametros;
        }
    }

    /**
     * Retorna um parâmetro
     * @param $param - nome do parâmetro
     */
    public function obtParametro($param)
    {
        if ($this->param[$param]) {
            return $this->param[$param];
        }
        return NULL;
    }

    /**
     * Retorna os parâmetros da ação
     */
    public function obtParametros()
    {
        return $this->param;
    }

    /**
     * Retorna o callback atual
     */
    public function obtAcao()
    {
        return $this->acao;
    }

    /**
     * Configura propriedade
     * @param $param - nome do propriedade
     * @param $valor - valor do propriedade
     */
    public function defPropriedade($propriedade, $valor)
    {
        $this->propriedades[$propriedade] = $valor;
    }

    /**
     * Configura propriedade
     * @param $param - nome do propriedade
     * @param $valor - valor do propriedade
     */
    public function obtPropriedade($propriedade)
    {
        return $this->propriedades[$propriedade] ?? null;
    }

    /**
     * Prepara ação para usar em um objeto
     * @param $objeto Objeto Dados
     */
    public function prepara($objeto) {
        $parametros = $this->param;
        $acao       = clone $this;

        if ($parametros) {
            if (isset($parametros['*'])) {
                unset($parametros['*']);
                unset($acao->param['*']);

                foreach ($objeto as $atributo => $valor) {
                    if (\is_scalar($valor)) {
                        $parametros[$atributo] = $valor;
                    }
                }
            }

            foreach ($parametros as $parametro => $valor) {
                $acao->defParametro($parametro, $this->substitui($valor, $objeto));
            }
        }
        return $acao;
    }

    /**
     * Substitui um string com propriedades de objto detnro {padrão}
     * @param $conteudo String com padrão
     * @param $objeto Qualquer Objeto
     */
    public function substitui($conteudo, $objeto)
    {
        if (preg_match_all('/\{(.*?)}/', $conteudo, $combinacoes)) {
            foreach ($combinacoes[0] as $combinacao) {
                $propriedade = substr($combinacao, 1, -1);
                $valor       = $objeto->$propriedade ?? null;
                $conteudo    = \str_replace($combinacao, $valor, $conteudo);
            }
        }
        return $conteudo;
    }

    /**
     * Método serializa
     */
    public function serializa() 
    {
        # verifica se a ação é um método
        if (is_array($this->acao)) {
            // obtém o nome da classe a partir no objeto informado.
            $url['classe'] = is_object($this->acao[0]) ? get_class($this->acao[0]) : $this->acao[0];

            # obtém o nome da método
            $url['metodo'] = $this->acao[1];

            # verifica se há parâmetros
            if ($this->param) {
                $url = array_merge($url, $this->param);
            }
            # monta a URL
            return '?' . http_build_query($url);
        }
    }

    /**
     * Retorna se a ação é estática
     */
    public function ehEstatico()
    {
        if (is_callable($this->acao) AND is_array($this->acao)) {
            $classe = is_string($this->acao[0]) ? $this->acao[0] : get_class($this->acao[0]);
            $metodo = $this->acao[1];

            if (method_exists($classe, $metodo)) {
                $rm = new ReflectionMethod($classe, $metodo);
                return $rm->isStatic() || (isset($this->param['estatico']) && $this->param['estatico'] == '1');
            } else { 
                return TRUE;
            }
        }
        return FALSE;
    }
}
