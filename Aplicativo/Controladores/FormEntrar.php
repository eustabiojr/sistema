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
     */
    public function aoEntrar($param)
    {
   
    }

    /**
     * Método aoSair
     */
    public function aoSair($param) {
        Sessao::defValor('logado', FALSE);
        echo "<script language='JavaScript'>window.location = 'inicio.php'; </script>";
    }
}