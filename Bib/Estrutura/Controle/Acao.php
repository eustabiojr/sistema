<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 08/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Controle;

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
    }

    /**
     * Método defParametro
     */
    public function defParametro($param, $valor)
    {
        $this->param[$param] = $valor;
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
}
