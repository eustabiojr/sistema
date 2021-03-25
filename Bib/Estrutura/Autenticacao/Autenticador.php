<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Autenticacao;

use Estrutura\BancoDados\Transacao;
use Estrutura\Sessao\Sessao;

/**
 * Classe Autenticador
 */
class Autenticador 
{
    /**
     * Método fazLogin
     */
    public function autentica($nome, $senha)
    {

    }

    /**
     * Método verificaFicha
     */
    public function verificaFicha($ficha)
    {
        if (!empty($ficha)) {

            if ($ficha == Sessao::obtValor('ficha_sinc')) {

                # Só devemos alterar a ficha após a última ficha ser conferida
                $this->defFicha();

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Método defFicha
     * 
     * A ficha deve ser definida tanto no carregamento inicial da página, quanto no carregamento 
     * POST. Entretanto, quando o formulário for subimetido, não deve ocorrer a re-definição de ficha.
     */
    public function defFicha() {

        # define ficha sincronizadora do formulário
        $ficha = md5(uniqid('auth'));
        Sessao::defValor('ficha_sinc', $ficha);

        return $ficha;
    }

    /**
     * Método defFicha
     */
    public function obtFicha() {
        return Sessao::obtValor('ficha_sinc');
    }
}