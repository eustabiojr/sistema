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

        $logo = new Elemento('img');
        $logo->class  = "mb-2";
        $logo->src    = "Aplicativo/Templates/ativos/marca/logo_ageu.svg";
        $logo->alt    = "";
        $logo->width  = "150";
        $logo->height = "160";

        $titulo = new Elemento('h1');
        $titulo->class = "h3 mb-3 fw-normal";
        $titulo->adic("Por favor se registre");

        $rotulo = new Elemento('label');
        $rotulo->for = "entradaUsuario";
        $rotulo->class = "visually-hidden";
        $rotulo->adic("Usuário");

        # cria os campos do formulário
        $usuario  = new Entrada('usuario');
        $usuario->type  = "email";
        $usuario->id    = "entradaUsuario";
        $usuario->class = "form-control";
        $usuario->{'required'} = '';
        $usuario->{'autofocus'} = '';
        $usuario->placeholder = 'admin';

        $senha   = new Senha('senha');
        $senha->type  = "email";
        $senha->id    = "entradaSenha";
        $senha->class = "form-control";
        $senha->{'required'} = '';
        $senha->placeholder = 'senha';

        $entrada = new BotaoCheck('lembre-me');
        $entrada->value = "Lembre-me";

        $rotulo = new Elemento('label');

        $div = new Elemento('div');
        $div->class = "checkbox mb-3";

        $paragrafo = new Elemento('p');
        $paragrafo->class = "mt-5 mb-3 text-muted";
        $paragrafo->adic("&copy; 2020–2021");

        $this->form->adicCampo('Entrar', $usuario, 200);
        $this->form->adicCampo('Senha', $senha, 200);
        $this->form->adicAcao('Entrar', new Acao(array($this, 'aoEntrar')));
/*
        <form>
        #<img class="mb-2" src="Aplicativo/Templates/ativos/marca/logo_ageu.svg" alt="" width="150" height="160">
        #<h1 class="h3 mb-3 fw-normal">Por favor se registre</h1>
        #<label for="inputEmail" class="visually-hidden">Email</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
        <label for="inputPassword" class="visually-hidden">Senha</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Senha" required>
        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Lembre-me
          </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
      </form>
      */

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