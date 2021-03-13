<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 12/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\GrupoCheck;
use Estrutura\Bugigangas\Form\GrupoRadio;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;

/**
 * Class FormContato
 */
class FormFuncionario extends Pagina
{
    private $form;

    public function __construct()
    {
        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_funcionario'));
        $this->form->defTitulo('Formulário de funcionário');

        # cria os campos do formulário
        $id           = new Entrada('id');
        $nome         = new Entrada('nome');
        $endereco     = new Entrada('endereco');
        $email        = new Entrada('email');
        $departamento = new Combo('departamento');
        $idiomas      = new GrupoCheck('idiomas');
        $contratacao  = new GrupoRadio('contratacao');

        $this->form->adicCampo('Código', $id, 300);
        $this->form->adicCampo('Nome', $nome, 300);
        $this->form->adicCampo('Endereço', $endereco, 300);
        $this->form->adicCampo('E-mail', $email, 300);
        $this->form->adicCampo('Departamento', $departamento, 300);
        $this->form->adicCampo('Idiomas', $idiomas, 300);
        $this->form->adicCampo('Contratação', $contratacao, 300);

        $id->defEditavel(FALSE);
        $idiomas->defEsboco('horizontal');
        $contratacao->defEsboco('horizontal');

        # define alguns atributos
        $departamento->adicItens(array('1' => 'RH',
                                  '2' => 'Atendimento',
                                  '3' => 'Engenharia',
                                  '4' => 'Produção'));

        # define alguns atributos
        $idiomas->adicItens(array('1' => 'Inglês',
                                  '2' => 'Espanhol',
                                  '3' => 'Alemão',
                                  '4' => 'Italiano'));
        # define alguns atributos
        $contratacao->adicItens(array('1' => 'Estagiário',
                                      '2' => 'Pessoa Jurífica',
                                      '3' => 'CLT',
                                      '4' => 'Sócio'));

        $this->form->adicAcao('Enviar', new Acao(array($this, 'aoSalvar')));
        $this->form->adicAcao('Limpar', new Acao(array($this, 'aoLimpar')));

        # adiciona o formulário à página
        parent::adic($this->form);
    }

    public function aoSalvar() 
    {
        try {
            Transacao::abre('exemplo');

            # obtém os dados
            $dados = $this->form->obtDados();

            # valida
            if (empty($dados->nome)) {
               throw new Exception('Nome vazio');
            }

            $funcionario = new Funcionario;
            $funcionario->doArray( (array) $dados);
            $funcionario->idiomas = implode(',', (array) $dados->idiomas);
            $funcionario->grava();

            $dados->id = $funcionario->id;
            Transacao::fecha();

            # mantém o formulário preenchido (agora com ID)
            $this->form->defDados($dados);

            new Mensagem('info', 'Dados salvos com sucesso');

        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }

    public function aoLimpar()
    {
        $arr = array();
        $this->form->defDados($arr);
    }
}