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
        $conexao = Transacao::obt();

        # limpando as strings
        $nome  = $this->fazLimpesa($nome);
        $senha = md5($this->fazLimpesa($senha));
        
        # usando consultas preparadas (é mais seguro)
        $sql = 'SELECT * FROM usuario WHERE nome = :nome AND senha = :senha'; #  LIMIT 1
        $declaracao = $conexao->prepare($sql);
        $declaracao->bindValue(':nome', $nome);
        $declaracao->bindValue(':senha', $senha);

        #$resultado = $declaracao->fetchAll(PDO::FETCH_ASSOC);
        $declaracao->execute();

        # se retornar algum registro
        if ($declaracao->rowCount() == 1) {

            /*
            # verificar se o usuario esta bloqueado
            if (verifica_forca_bruta($id_usuario, $sql) == true) {

                return false;
            } else {

            }*/
            return true;
        }
    }

    /**
     * Método fazLimpesa
     * 
     * Faz a limpeza da string passada
     */
    private function fazLimpesa($string)
    {
        # Expressões regulares
        # |[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i
        return $nome = preg_replace('/[^[:alnum:]_]/', '', $string); # /[^0-9]+/, # /[^a-zA-Z0-9_\-]+/
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