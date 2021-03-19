<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 16/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\CaixaV;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Embrulho\EmbrulhoGradedados;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;

use Estrutura\Tracos\TracoApaga;
use Estrutura\Tracos\TracoRecarrega;

class ListaProdutos extends Pagina
{
    private $form;
    private $gradedados;
    private $carregado;
    private $conexao;
    private $registroAtivo;
    private $filtros;

    use TracoApaga;
    use TracoRecarrega {
        aoRecarregar as tracoAoRecarregar;
    }

    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Produto';

        # instancia um formulário
        $this->form = new EmbrulhoForm(new Form('form_busca_produtos'));
        $this->form->defTitulo('Produto');

        # cria os campos do formulário
        $descricao = new Entrada('descricao');
        $this->form->adicCampo('Descrição', $descricao, '100%'); 
        $this->form->adicAcao('Buscar', new Acao(array($this, 'aoRecarregar')));
        $this->form->adicAcao('Cadastrar', new Acao(array(new FormFuncionario, 'aoEditar')));

        # instancia objeto grade de dados
        $this->gradedados = new EmbrulhoGradedados(new Gradedados);

        $codigo    = new ColunaGradedados('id',              'Código',     'center', '10%');
        $descricao = new ColunaGradedados('descricao',       'Descrição',  'left',   '30%');
        $fabrica   = new ColunaGradedados('nome_fabricante', 'Fabricante', 'left',   '30%');
        $estoque   = new ColunaGradedados('estoque',         'Estoque',    'right',   '15%');
        $preco     = new ColunaGradedados('preco_venda',     'Venda', 'right',   '15%');

        # adiciona as colunas à grade de dados
        $this->gradedados->adicColuna($codigo);
        $this->gradedados->adicColuna($descricao);
        $this->gradedados->adicColuna($fabrica);
        $this->gradedados->adicColuna($estoque);
        $this->gradedados->adicColuna($preco);

        $this->gradedados->adicAcao('Editar', new Acao([new FormProdutos, 'aoEditar']),
            'id', 'fa fa-edit la-lg blue');

        $this->gradedados->adicAcao('Excluir', new Acao([$this, 'aoApagar']),
            'id', 'fa fa-trash la-lg red');

        # monta a página por meio de uma caixa
        $caixa = new CaixaV;
        $caixa->style = 'display: block';
        $caixa->adic($this->form);
        $caixa->adic($this->gradedados);

        parent::adic($caixa);
    }

    public function aoRecarregar()
    {
        # obtém os dados do formulário de buscas
        $dados = $this->form->obtDados();

        # verifica se o usuário preencheu o formulário
        if ($dados->descricao) {
            # filtra pela descrição do produto
            $this->filtros[] = ['descricao', 'LIKE', "%{$dados->descricao}%"];
        }

        $this->tracoAoRecarregar();
        $this->carregado = TRUE;
    }

    public function exibe()
    {
        # se a listagem ainda não foi carregada
        if (!$this->carregado) {
            $this->aoRecarregar();
        }
        parent::exibe();
    }
}