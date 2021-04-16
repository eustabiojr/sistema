<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 15/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\GrupoCheck;
use Estrutura\Bugigangas\Form\Texto;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;

/**
 * Classe FormPessoas
 */
class FormPessoas extends Pagina
{
    private $form;
    private $conexao;
    private $registroAtivo;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Pessoa';

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_pessoas'));
        $this->form->defTitulo('Pessoa');

        # cria os campos do formulário
        $codigo   = new Entrada('id');
        $nome     = new Entrada('nome');
        $endereco = new Entrada('endereco');
        $bairro   = new Entrada('bairro');
        $telefone = new Entrada('telefone');
        $email    = new Entrada('email');
        $cidade   = new Combo('id_cidade');
        $grupo    = new GrupoCheck('ids_grupos');
        $grupo->defEsboco('horizontal');

        # carrega as cidades do banco de dados
        Transacao::abre('exemplo');

        $cidades = Cidade::todos();
        $itens = array();
        foreach ($cidades as $obj_cidade) {
            $itens[$obj_cidade->id] = $obj_cidade->nome;
        }
        $cidade->adicItens($itens);

        $grupos = Grupo::todos();
        $itens = array();
        foreach ($grupos as $obj_grupo) {
            $itens[$obj_grupo->id] = $obj_grupo->nome;
        }
        $grupo->adicItens($itens);

        $this->form->adicCampo('Código',   $codigo, '30%');
        $this->form->adicCampo('Nome',     $nome, '70%');
        $this->form->adicCampo('Endereço', $endereco, '70%');
        $this->form->adicCampo('Bairro',   $bairro, '70%');
        $this->form->adicCampo('Telefone', $telefone, '70%');
        $this->form->adicCampo('Email',    $email, '70%');
        $this->form->adicCampo('Cidade',   $cidade, '70%');
        $this->form->adicCampo('Grupo',    $grupo, '70%');

        $codigo->defEditavel(FALSE);
        # O parâmetro da classe ação vai um array. Sendo que no índice 0 tem um objeto e no índice 1 possui o método
        # O objeto ação (Acao) transforma o array em parâmetros enviados pela URL.
        $this->form->adicAcao('Salvar', new Acao(array($this, 'aoSalvar')));

        # adiciona o formulário à página
        parent::adic($this->form);
    }

    public function aoSalvar()
    {
        try {
            # inicia transação com o banco de dados
            Transacao::abre($this->registroAtivo);

            //Transacao::defHistorico("/tmp/log");
            $dados = $this->form->obtDados();
            
            $idsGrupos = $dados->ids_grupos;

            $this->form->defDados($dados);

            $grupo_pessoa = new GrupoPessoa;
            $pessoa = new Pessoa;

            $pessoa->apagGrupos();
            if ($dados->ids_grupos) {
                foreach ($dados->ids_grupos as $id_grupo) {
                    $pessoa->adicGrupo(new Grupo($id_grupo));
                }
            }

            unset($dados->ids_grupos);

            $pessoa->doArray((array) $dados);
            $pessoa->grava();

            Transacao::fecha();
            new Mensagem('info', 'Dados armazenados com sucesso');
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }
    }

    public function aoEditar($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id'];

                # inicia transação com o banco de dados
                Transacao::abre($this->registroAtivo);

                $pessoa = Pessoa::localiza($id);
                if ($pessoa) {
                    $pessoa->ids_grupos = $pessoa->obtIdsGrupos();
                    $this->form->defDados($pessoa);
                }
                Transacao::fecha();
            }

        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }
    }
}