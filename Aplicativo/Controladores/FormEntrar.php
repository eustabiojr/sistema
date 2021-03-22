<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 19/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Form\BotaoCheck;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\Oculto;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Bugigangas\Form\Senha;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;
use Estrutura\Sessao\Sessao;

/**
 * Classe FormEntrar
 */
class FormEntrar extends Pagina
{
    private $form;
    private $conexao;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        $this->conexao = 'exemplo';

        parent::__construct();

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_entrar'));
        $this->form->defTitulo('Entrar');
        $this->form->defTipoLinha(1);

        # cria os campos do formulário
        $usuario  = new Entrada('usuario');
        $usuario->class    = "form-control";
        $usuario->id    = "entradaUsuario"; 
        #$usuario->{'required'} = '';
        #$usuario->{'autofocus'} = '';
        $usuario->placeholder = 'admin';

        $senha   = new Senha('senha');
        $senha->class    = "form-control";
        $senha->id    = "entradaSenha";
        #$senha->{'required'} = '';
        $senha->placeholder = 'senha';

        $ficha = new Entrada('ficha_sinc');
        $ficha->defEditavel(FALSE);
        $ficha->value = Sessao::obtValor('ficha_sinc');

        $this->form->adicCampo('Entrar', $usuario, 200);
        $this->form->adicCampo('Senha', $senha, 200);
        $this->form->adicCampo('Senha', $ficha);
        $this->form->adicAcao('Entrar', new Acao(array($this, 'aoEntrar')));

        parent::adic($this->form);
    }

    /**
     * Método aoEntrar
     */
    public function aoEntrar($param)
    {
        Transacao::abre($this->conexao);

        $dados = $this->form->obtDados();

        $entrada = new Usuario();

        echo "<p>Ficha enviada: " . $dados->ficha_sinc . "</p>" . PHP_EOL;

        #echo "<p>Ficha gravada: " . Sessao::obtValor('ficha_sinc') . "</p>" . PHP_EOL;

        $ficha = $this->verificaFicha($dados->ficha_sinc);

        $teste =  $ficha ? "Sim" : "Não";
        echo "<p> Ficha confere? " . $teste . "</p>" . PHP_EOL;

        $rst = $entrada->fazLogin($dados->usuario, $dados->senha);

        if ($ficha) {
            if ($rst) {
                echo "<p> Logado com sucesso!</p>" . PHP_EOL;
                Sessao::defValor('logado', TRUE);
                Sessao::atualizaAtividade();
                echo "<script language='JavaScript'>window.location = 'inicio.php'; </script>";
            }  else {
                new Mensagem('erro', 'Senha ou usuario incorreto!');
            }   
        }  else {
            Sessao::defValor('logado', FALSE);
            #echo "<script language='JavaScript'>window.location = 'inicio.php'; </script>";
            new Mensagem('erro', 'Detectada tentativa de invasão!');
        }   
    }

    /**
     * Método aoSair
     */
    public function aoSair($param) {
        Sessao::defValor('logado', FALSE);
        echo "<script language='JavaScript'>window.location = 'inicio.php'; </script>";
    }

    private function verificaFicha($ficha)
    {
        if ($ficha == Sessao::obtValor('ficha_sinc')) {
            return true;
        } else {
            return false;
        }

        # Só devemos alterar a ficha após a última ficha ser conferida
        $this->defFicha();
    }

    /**
     * Método defFicha
     */
    private function defFicha() {
        # define ficha sincronizadora do formulário
        $ficha = md5(uniqid('auth'));
        Sessao::defValor('ficha_sinc', $ficha);

        return $ficha;
    }
}