<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 16/03/2021
 ************************************************************************************/

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

    public function __construct()
    {
        parent::__construct();
        new Sessao;

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

        }  catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }
}