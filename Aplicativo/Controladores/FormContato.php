<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 11/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\Texto;
use Estrutura\Controle\Pagina;
use Estrutura\Controle\Acao;

/**
 * Class FormContato
 */
class FormContato extends Pagina
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_contato'));
        $this->form->defTitulo('Formulário de contato');

        # cria os campos do formulário
        $nome     = new Entrada('nome');
        $email    = new Entrada('email');
        $assunto  = new Combo('assunto');
        $mensagem = new Texto('mensagem');

        $this->form->adicCampo('Nome', $nome, 300);
        $this->form->adicCampo('E-mail', $email, 300);
        $this->form->adicCampo('Assunto', $assunto, 300);
        $this->form->adicCampo('Mensagem', $mensagem, 300);

        # define alguns atributos
        $assunto->adicItens(array('1' => 'Sugestão',
                                  '2' => 'Reclamação',
                                  '3' => 'Suporte Técnico',
                                  '4' => 'Cobrança',
                                  '5' => 'Outro'));
        $mensagem->defTamanho(300,80);

        $this->form->adicAcao('Enviar', new Acao(array($this, 'aoEnviar')));

        # adiciona o formulário à página
        parent::adic($this->form);
    }

    /**
     * Class aoEnviar
     */
    public function aoEnviar() 
    {
        try {
            # obtém os dados
            $dados = $this->form->obtDados();

            # mantém o formulário preenchido
            $this->form->defDados($dados);

            # valida
            if (empty($dados->email)) {
                throw new Exception('Email vazio');
            }

            if (empty($dados->assunto)) {
                throw new Exception('Assunto vazio');
            }

            // monta mensagem
            $mensagem  = "Nome: {$dados->nome} <br/>" . PHP_EOL;
            $mensagem .= "Email: {$dados->email} <br/>" . PHP_EOL;
            $mensagem .= "Assunto: {$dados->assunto} <br/>" . PHP_EOL;
            $mensagem .= "Mensagem: {$dados->mensagem} <br/>" . PHP_EOL;

        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }
}