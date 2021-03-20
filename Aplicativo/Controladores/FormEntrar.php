<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 19/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Form\BotaoCheck;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
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

    /**
     * Método Construtor
     */
    public function __construct()
    {
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

        $this->form->adicCampo('Entrar', $usuario, 200);
        $this->form->adicCampo('Senha', $senha, 200);
        $this->form->adicAcao('Entrar', new Acao(array($this, 'aoEntrar')));

        parent::adic($this->form);
    }

    /**
     * Método aoEntrar
     */
    public function aoEntrar($param)
    {
        $dados = $this->form->obtDados();
        if ($dados->usuario == 'admin' AND $dados->senha == 'admin') {
            Sessao::defValor('logado', TRUE);
            echo "<script language='JavaScript'>window.location = 'inicio.php'; </script>";
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