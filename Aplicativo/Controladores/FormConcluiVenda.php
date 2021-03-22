<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 16/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;
use Estrutura\Sessao\Sessao;

/**
 * Classe FormConcluiVenda
 */
class FormConcluiVenda extends Pagina
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
        new Sessao;

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Produto';

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_conclui_venda')); 
        $this->form->defTitulo('Conclui Venda');

        # cria os campos do formulário
        $cliente     = new Entrada('id_cliente');
        $valor_venda = new Entrada('valor_venda');
        $desconto    = new Entrada('desconto');
        $acrescimos  = new Entrada('acrescimos');
        $valor_final = new Entrada('valor_final');
        $parcelas    = new Combo('parcelas');
        $obs         = new Entrada('obs');

        $parcelas->adicItens(array(1 => 'Uma', 2 => 'Duas', 3 => 'Três'));
        $parcelas->defValor(1);

        # define uma ação de cálculo JavaScript
        $desconto->onBlur = "$('[name=valor_final]').val(Number($('[name=valor_venda]') . 
            val()) + Number($('[name=acrescimos]').val()) - Number($('[name=desconto]').
            val()) )";

        $acrescimos->onBlur = $desconto->onBlur;
        
        $valor_venda->defEditavel(FALSE);
        $valor_final->defEditavel(FALSE);

        $this->form->adicCampo('Código',    $cliente, '50%');
        $this->form->adicCampo('Valor', $valor_venda, '50%');
        $this->form->adicCampo('Desconto', $desconto, '50%');
        $this->form->adicCampo('Acréscimos', $acrescimos, '50%');
        $this->form->adicCampo('Final', $valor_final, '50%');
        $this->form->adicCampo('Parcelas', $parcelas, '50%');
        $this->form->adicCampo('Obs', $obs, '50%');

        $this->form->adicAcao('Salvar', new Acao(array($this, 'aoGravarVenda')));

        parent::adic($this->form);
    }

    public function aoCarregar($param)
    {
        $total = 0;
        $itens = Sessao::obtValor('lista');
        if ($itens) {
            # percorre os itens
            foreach ($itens as $item) {
                $total += $item->preco * $item->quantidade;
            }
        }

        $dados = new stdClass;
        $dados->valor_venda = $total;
        $dados->valor_final = $total;
        $this->form->defDados($dados);
    }

    public function aoGravarVenda()
    {
        try {
            Transacao::abre($this->conexao);
            $dados = $this->form->obtDados();

            $cliente = Pessoa::localiza($dados->id_cliente);
            if (!$cliente) {
                throw new Exception('Cliente não encontrado!');
            }

            # verifica débitos
            if ($cliente->totalDebitos() > 0) {
                throw new Exception('Débitos impedem esta operação');
            }

            # inicia gravação da venda
            $venda = new Venda;
            $venda->cliente     = $dados->cliente;
            $venda->data_venda  = date('Y-m-d');
            $venda->desconto    = $dados->desconto;
            $venda->acrescimos  = $dados->acrescimos;
            $venda->valor_final = $dados->valor_final;
            $venda->observacoes = $dados->obs;

            # lê a variável lista da sessão
            $itens = Sessao::obtValor('lista');
            if ($itens) {
                # percorre os itens
                foreach ($itens as $item) {
                    # adiciona item na venda
                    $venda->adicItem(new Produto($item->id_produto), $item->quantidade);
                }
            }
            # armazena venda no banco de dados
            $venda->grava();

            # gera o financeiro
            Conta::geraParcelas($dados->id_cliente, 2, $dados->valor_final, $dados->parcelas);

            Transacao::fecha();

            Sessao::defValor('lista', array());

            # exibe mensagem de sucesso
            new Mensagem('info', 'Venda registrada com sucesso!');
        }  catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }
}