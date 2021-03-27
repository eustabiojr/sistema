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
use Estrutura\Validacao\FichaSincronizadora;

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
        $this->fc = new FichaSincronizadora;
        $this->fc->adicFicha(md5(uniqid('auth')));
        
        parent::__construct();

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_entrar'));
        $this->form->defTitulo('Entrar');
        $this->form->defTipoLinha(1);
        $this->form->defTituloCabecalho("Identifique-se por favor!");

        # cria os campos do formulário
        $usuario  = new Entrada('usuario');
        $usuario->class = "form-control";
        $usuario->id    = "entradaUsuario"; 
        #$usuario->{'required'} = '';
        #$usuario->{'autofocus'} = '';
        $usuario->placeholder = 'usuario';

        $senha   = new Senha('senha');
        $senha->class    = "form-control";
        $senha->id    = "entradaSenha";
        #$senha->{'required'} = '';
        $senha->placeholder = 'senha';

        $ficha = new Oculto('ficha_sinc'); // Oculto
        $ficha->defEditavel(FALSE);
        
        # obtém a ficha no formulário
        $ficha->value = $this->fc->obtFichaInterna();
        $this->form->adicCampo('Entrar', $usuario, 200);
        $this->form->adicCampo('Senha', $senha, 200);
        $this->form->adicCampo('', $ficha);
        $this->form->adicAcao('Entrar', new Acao(array($this, 'aoEntrar')));

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

        $ficha = $this->fc->verificaFicha($dados->ficha_sinc);
        #echo "<p> Ficha confere? " . ($ficha ? "Sim" : "Não") . "</p>" . PHP_EOL;

        $u = new Usuario;
        $u->defUsuario($dados->usuario);
        $u->defSenha($dados->senha);
        $rst = $u->validaEntrada();

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
            }  
        # se a ficha não confere 
        }  else {
            # 
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