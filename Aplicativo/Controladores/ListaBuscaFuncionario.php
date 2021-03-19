<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\CaixaV;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Dialogo\Pergunta;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Embrulho\EmbrulhoGradedados;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;

/**
 * Class ListaBuscaFuncionario
 */
class ListaBuscaFuncionario extends Pagina
{
    private $form;
    private $gradedados;
    private $carregado;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        # instancia um formulário
        $this->form = new EmbrulhoForm(new Form('form_busca_funcionarios'));
        
        # cria os campos do formulário
        $nome = new Entrada('nome');
        $this->form->adicCampo('Nome', $nome, 300); 
        $this->form->adicAcao('Buscar', new Acao(array($this, 'aoRecarregar')));
        $this->form->adicAcao('Novo', new Acao(array(new FormFuncionario, 'aoEditar')));

        # instancia o objeto de grade de dados
        $this->gradedados = new EmbrulhoGradedados(new Gradedados);
        
        # instancia as colunas da grade de dados
        $codigo   = new ColunaGradedados('id',      'Código',  'center', '10%');
        $nome     = new ColunaGradedados('nome',    'Nome',    'left', '30%');
        $endereco = new ColunaGradedados('endereco', 'Endereço', 'left', '30%');
        $email    = new ColunaGradedados('email',   'Email',   'left', '30%');

        $ordem_codigo = new Acao(array($this, 'aoRecarregar'));
        $ordem_codigo->defParametro('ORDER', 'id');
        $codigo->defAcao($ordem_codigo);

        $ordem_nome = new Acao(array($this, 'aoRecarregar'));
        $ordem_nome->defParametro('ORDER', 'nome');
        $codigo->defAcao($ordem_nome);

        # adiciona as colunas à grade de dados
        $this->gradedados->adicColuna($codigo);
        $this->gradedados->adicColuna($nome);
        $this->gradedados->adicColuna($endereco);
        $this->gradedados->adicColuna($email);

        $this->gradedados->adicAcao('Editar', new Acao(array(new FormFuncionario, 'aoEditar')), 'id');
        $this->gradedados->adicAcao('Excluir', new Acao(array($this, 'Exclui')), 'id');

        # monta a página através de uma caixa
        $caixa = new CaixaV;
        $caixa->style = 'display: block; margin: 20px';
        $caixa->adic($this->form);
        $caixa->adic($this->gradedados);

        parent::adic($caixa);
    }

    public function aoRecarregar($param = NULL)
    {
        Transacao::abre('exemplo');
        $repositorio = new Repositorio('Funcionario');

        # cria um critério de seleção de dados
        $criterio = new Criterio;
        $criterio->defPropriedade('ORDER', $param['ORDER'] ?? 'id');

        # obtém os dados do formulário de buscas
        $dados = $this->form->obtDados();

        # verifica se o usuário preencheu o formulário
        if ($dados->nome) {
            # filtra pelo nome da pessoa
            $criterio->adic('nome', 'LIKE', "%{$dados->nome}%");
        }

        # carrega os produtos que satisfazem o critério
        $funcionarios = $repositorio->carrega($criterio);
        $this->gradedados->limpa();

        if ($funcionarios) {
            foreach ($funcionarios as $funcionario) {
                # adiciona o objeto à grade de dados
                $this->gradedados->adicItem($funcionario);
            }
        }
        # finaliza a transação
        Transacao::fecha();
        $this->carregado = TRUE;
    }

    /**
     * Método aoExcluir
     */
    public function aoExcluir($param) {
        $id = $param['id'];
        $acao1 = new Acao(array($this, 'Exclui'));
        $acao1->defParametro('id', $id);

        new Pergunta('Deseja realmente excluir o registro?', $acao1);
    }

    /**
     * Método Exclui
     */
    public function Exclui($param)
    {
        try {
            $id = $param['id'];
            Transacao::abre('exemplo');

            $funcionario = Funcionario::localiza($id);
            if ($funcionario) {
                $funcionario->apaga();
            }

            Transacao::fecha();
            $this->aoRecarregar();
            new Mensagem('info', "Registro excluído com sucesso");
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        # se a listagem ainda não foi carregada
        if (!$this->carregado) {
            $this->aoRecarregar();
        }
        parent::exibe();
    }
}