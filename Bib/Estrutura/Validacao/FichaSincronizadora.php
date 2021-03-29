<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 25/03/2021
 ************************************************************************************/

namespace Estrutura\Validacao;

use Estrutura\Sessao\Sessao;

/**
 * Classe FichaSincronizadora
 * 
 * Precisamos inicializar a ficha interna quando a página de login é carregada inicialmente
 * (ou antes de ser postada). Além disso, é importante evitar que a ficha do formulário
 * seja alterada durante a postagem do formulário. Precisamos renovar a ficha no formulário 
 * só depois da conferência da ficha postada anteriormente, e direcionar novamente para a 
 * página de login.
 * 
 * Outra ideia, é permitir que a ficha seja alterada a cada carregamento da página. E como,
 * no carregamento da página a ficha é alterada, e a ficha do post é a anterior. Precisams,
 * comparar a ficha anterior (ou seja, antes da alteração).
 * 
 * Nota: Foi adotada a última opção.
 */
class FichaSincronizadora
{
    private $ficha_interna = array();
    private $ficha_form;

    /**
     * Método construtor
     */
    public function adicFicha($ficha = NULL)
    {
		$this->ficha_interna = array($ficha);
        $ficha_sinc = Sessao::obtValor('ficha_sinc');

        if (isset($ficha_sinc[0])) {
            array_push($this->ficha_interna, $ficha_sinc[0]);
        }
        Sessao::defValor('ficha_sinc', $this->ficha_interna);
    }

    /**
     * Método defFichaForm
     */
    public function defFichaForm($ficha)
    {
        $this->ficha_form = $ficha;
    }

    /**
     * Método obtFichaForm
     */
    public function obtFichaForm()
    {
        return $this->ficha_form;
    }

    /**
     * Método defFicha
     */
    public function obtFichaInterna() {
        $ficha = Sessao::obtValor('ficha_sinc');
        return $ficha[0];
    }

    /**
     * Método verificaFicha
     */
    public function verificaFicha($ficha)
    {
        $ficha = $this->ficha_form ?? $ficha;
        $ficha_armazenada = Sessao::obtValor('ficha_sinc');

        if ($ficha == $ficha_armazenada[1]) {
            return true;
        } else {
            return false;
        }
    }
}