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
        if ($ficha_interna) {
            $this->ficha_interna = $ficha_interna;
        } else {
        # define ficha sincronizadora do formulário
            $this->ficha_interna = md5(uniqid('auth'));
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
     * Método defFicha
     */
    public function obtFichaInterna() {
        return $this->ficha_interna ?? Sessao::obtValor('ficha_sinc');
    }

    public function verificaFicha()
    {
        if ($this->ficha_form == Sessao::obtValor('ficha_sinc')) {
            return true;
        } else {
            return false;
        }
    }
}