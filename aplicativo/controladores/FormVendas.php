<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 16/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\CaixaV;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Embrulho\EmbrulhoGradedados;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;
use Estrutura\Sessao\Sessao;

/**
 * Classe FormVendas
 */
class FormVendas extends Pagina
{
    private $form;
    private $conexao;
    private $registroAtivo;
    private $carregado;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();
        new Sessao;

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Produto';

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_vendas')); 
        $this->form->defTitulo('Venda');

        # cria os campos do formulário
        $codigo     = new Entrada('id');
        $quantidade = new Entrada('quantidade');

        $this->form->adicCampo('Código',    $codigo, '50%');
        $this->form->adicCampo('Quantidade', $quantidade, '50%');

        $this->form->adicAcao('Adicionar', new Acao(array($this, 'aoAdicionar')));
        $this->form->adicAcao('Concluir',  new Acao(array(new FormConcluiVenda, 'aoCarregar')));

        # instancia objeto grade de dados
        $this->gradedados = new EmbrulhoGradedados(new Gradedados);

        $codigo     = new ColunaGradedados('id', 'Código',    'center', '20%');
        $descricao  = new ColunaGradedados('descricao',  'Descrição', 'left',   '40%');
        $quantidade = new ColunaGradedados('quantidade', 'Estado',    'right',  '20%');
        $preco      = new ColunaGradedados('preco',      'Preço',     'right',  '20%');

        # define um transformador para a coluna preço
        $preco->defTransformador(array($this, 'formata_moeda'));

        # adiciona as colunas à grade de dados
        $this->gradedados->adicColuna($codigo);
        $this->gradedados->adicColuna($descricao);
        $this->gradedados->adicColuna($quantidade);
        $this->gradedados->adicColuna($preco);

        $this->gradedados->adicAcao('Excluir', new Acao([$this, 'aoApagar']),
            'id', 'fa fa-trash la-lg red');

        # monta a página por meio de uma caixa 
        $caixa = new CaixaV;
        $caixa->style = 'display: block';
        $caixa->adic($this->form);
        $caixa->adic($this->gradedados);

        parent::adic($caixa);
    }

    /**
     * Método aoAdicionar
     */
    public function aoAdicionar()
    {
        try {
            # obtém os dados do formulário
            $item = $this->form->obtDados();
            Transacao::abre($this->conexao);
            $produto = Produto::localiza($item->id);
            if ($produto) {
                # busca mais informações do produto
                $item->descricao = $produto->descricao;
                $item->preco     = $produto->preco_venda;

                $lista = Sessao::obtValor('lista');
                $lista[$item->id] = $item;
                Sessao::defValor('lista', $lista);
            }
            Transacao::fecha();
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
        $this->aoRecarregar();
    }

    /**
     * Método aoApagar
     */
    public function aoApagar($param)
    {
        # lê variável $lista da sessão
        $lista = Sessao::obtValor('lista');

        # exclui a posição que armazena o produto de código
        unset($lista[$param['id']]);

        # grava variável $lista de volta à sessão
        Sessao::defValor('lista', $lista);

        # recarrega a listagem
        $this->aoRecarregar();
    }

    /**
     * Método aoRecarregar
     */
    public function aoRecarregar()
    {
        # obtém a variável de sessão $lista
        $lista = Sessao::obtValor('lista');

        # limpa a grade de dados
        $this->gradedados->limpa();
        if($lista) {
            foreach ($lista as $item) {
                $this->gradedados->adicItem($item);
            }
        }
        $this->carregado = TRUE;
    }

    /**
     * Método formata_moeda
     */
    public function formata_moeda($valor)
    {
        return number_format($valor, 2, ',', '.');
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        # caso a listagem ainda não tenha sido carregada
        if (!$this->carregado) {
            $this->aoRecarregar();
        }
        parent::exibe();
    }
}