<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 23/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Sessao\Sessao;

/**
 * Classe Autenticador
 *  
 */ 
class Autenticao
{
    /**
     * Método Construtor
     */
    public function __construct()
    {
        $this->conexao = 'exemplo';        
    }

    /**
     * Método autenticaUsuario
     */
    public function autenticaUsuario()
    {
        Transacao::abre($this->conexao);

        $dados = $this->form->obtDados();

        #echo "<p>Ficha enviada: " . $dados->ficha_sinc . "</p>" . PHP_EOL;

        #echo "<p>Ficha gravada: " . Sessao::obtValor('ficha_sinc') . "</p>" . PHP_EOL;

        $ficha = $this->autenticador->verificaFicha($dados->ficha_sinc);
        #$teste =  $ficha ? "Sim" : "Não";
        #echo "<p> Ficha confere? " . $teste . "</p>" . PHP_EOL;

        $rst = $this->autenticador->autentica($dados->usuario, $dados->senha);

        # se a ficha confere
        if ($ficha) {
            # caso o login seja bem sucedido
            if ($rst) {
                #echo "<p> Logado com sucesso!</p>" . PHP_EOL;
                Sessao::defValor('logado', TRUE);
                Sessao::atualizaAtividade();
                echo "<script language='JavaScript'>window.location = 'inicio.php'; </script>";

            # caso o login seja mau sucedido
            }  else {
                Sessao::defValor('logado', FALSE);
                new Mensagem('erro', 'Senha ou usuario incorreto!');

                # Só preciso renovar a ficha sincronizadora caso o login não seja bem sucedido. Pois no c
                # caso de login bem sucedido, o usuário é redirecionado para a página inicial do site.
                #echo "<p>Ficha gravada (APÓS CONFERENCIA): " . Sessao::obtValor('ficha_sinc') . "</p>" . PHP_EOL
                //echo "<script language='JavaScript'>window.location = 'inicio.php'; </script>";
            }  
        # se a ficha não confere 
        }  else {

            Sessao::defValor('logado', FALSE);
            new Mensagem('erro', 'Detectada tentativa de invasão!');
        }
    }
}