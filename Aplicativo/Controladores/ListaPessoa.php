<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 15/03/2021
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
 * Classe ListaPessoa
 */
class ListaPessoa extends Pagina
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

        # instancia um formulário de buscas
        $this->form = new EmbrulhoForm(new Form('form_busca_pessoas'));
        $this->form->defTitulo('Pessoas');

        $nome = new Entrada('nome');
        
        $this->form->adicCampo('Nome',   $nome, '100%');
        $this->form->adicAcao('Buscar', new Acao(array($this, 'aoRecarregar')));
        $this->form->adicAcao('Novo',   new Acao(array(new FormPessoas, 'aoEditar')));      

        # instancia objeto grade de dados
        $this->gradedados = new EmbrulhoGradedados(new Gradedados);

        $codigo   = new ColunaGradedados('id',          'Código',   'center', '10%');
        $nome     = new ColunaGradedados('nome',        'Nome',     'left',   '40%');
        $endereco = new ColunaGradedados('endereco',    'Endereço', 'left',   '30%');
        $cidade   = new ColunaGradedados('nome_cidade', 'Cidade',   'left',   '20%');

        # adiciona as colunas à grade de dados
        $this->gradedados->adicColuna($codigo);
        $this->gradedados->adicColuna($nome);
        $this->gradedados->adicColuna($endereco);
        $this->gradedados->adicColuna($cidade);

        $this->gradedados->adicAcao('Editar', new Acao([new FormPessoas, 'aoEditar']),
            'id'); # , 'fa fa-edit la-lg blue'

        $this->gradedados->adicAcao('Excluir', new Acao([$this, 'aoApagar']),
            'id'); # , 'fa fa-trash la-lg red'

        # monta a página por meio de uma caixa
        $caixa = new CaixaV;
        $caixa->style = 'display: block';
        $caixa->adic($this->form);
        $caixa->adic($this->gradedados);

        parent::adic($caixa);
    }

    /**
     * Método aoRecarregar
     */
    public function aoRecarregar()
    {
        Transacao::abre('exemplo');
        $repositorio = new Repositorio('Pessoa');

        # cria um critério de seleção de dados
        $criterio = new Criterio;
        $criterio->defPropriedade('ORDER', 'id');

        # obtém os dados dos formulário de buscas
        $dados = $this->form->obtDados();

        # verifica se o usuário preencheu o formulário
        if ($dados->nome) {
            # filtra pelo nome da pessoa
            $criterio->adic('nome', 'LIKE', "%{$dados->nome}%");
        }

        # carrega os produtos que satisfazem o critério
        $pessoas = $repositorio->carrega($criterio);
        $this->gradedados->limpa();

        if ($pessoas) {
            foreach ($pessoas as $pessoa) {
                # adiciona o objeto à grade de dados
                $this->gradedados->adicItem($pessoa);
            }
        }

        # finaliza a transação
        Transacao::fecha();
        $this->carregado = TRUE;
    }

    /**
     * Método aoApagar
     */
    public function aoApagar($param)
    {
        $id = $param['id'];
        $acao1 = new Acao(array($this, 'Apaga'));
        $acao1->defParametro('id', $id);

        new Pergunta('Deseja realmente excluir o registro?', $acao1);
    }

    /**
     * Método Apaga
     */
    public function Apaga($param)
    {
        try {
            $id = $param['id'];
            Transacao::abre('exemplo');

            $pessoa = Pessoa::localiza($id);
            $pessoa->apaga();
            Transacao::fecha();
            $this->aoRecarregar();
            new Mensagem('info', 'Registro excluído com sucesso');
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