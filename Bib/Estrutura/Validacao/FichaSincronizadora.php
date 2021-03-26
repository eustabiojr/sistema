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
 */
class FichaSincronizadora
{
    private $ficha_interna;
    private $ficha_form;

    /**
     * Método construtor
     */
    public function inicializa($ficha_interna = NULL)
    {
		$tam = count($this->ficha_interna);

		if ($tam == 0) {
			$this->ficha_interna = array($ficha_interna);
		} else if ($tam == 1) {
			$this->ficha_interna = array($this->ficha_interna[0], $ficha_interna);
		} else {
			$this->ficha_interna = array($this->ficha_interna[1], $ficha_interna);
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
        return $this->ficha_interna[0];
    }

    /**
     * Método defFicha
     */
    public function obtFichaInterna() {
        return $this->ficha_interna ?? Sessao::obtValor('ficha_sinc');
    }

    public function verificaFicha()
    {
        $ficha = Sessao::obtValor('ficha_sinc');

        if ($this->ficha_form == $ficha[0]) {
            return true;
        } else {
            return false;
        }
    }
}