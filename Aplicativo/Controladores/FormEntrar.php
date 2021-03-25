<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 19/03/2021
 ************************************************************************************/

use Estrutura\Autenticacao\Autenticador;
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
    private $autenticador;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        $this->conexao = 'exemplo';
        $this->autenticador = new Autenticador;

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

        $ficha = new Oculto('ficha_sinc');
        #$ficha->defEditavel(FALSE);

        # definindo a ficha no carregamento inicial da página.
        if (!$_GET) {
            #echo "Carregamento inicial <br>RRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR";
            $this->autenticador->defFicha();
        }

        # obtém a ficha no formulário
        $ficha->value = $this->autenticador->obtFicha();

        $this->form->adicCampo('Entrar', $usuario, 200);
        $this->form->adicCampo('Senha', $senha, 200);
        $this->form->adicCampo('Senha', $ficha);
        $this->form->adicAcao('Entrar', new Acao(array(new Autenticao, 'autenticaUsuario')));

        parent::adic($this->form);
    }

    /**
     * Método aoEntrar
     * 
     * 184
     * sanduicheira, freezer
     */
    public function aoEntrar($param)
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

    /**
     * Método aoSair
     */
    public function aoSair($param) {
        Sessao::defValor('logado', FALSE);
        echo "<script language='JavaScript'>window.location = 'inicio.php'; </script>";
    }
}