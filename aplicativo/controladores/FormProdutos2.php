<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 15/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\GrupoRadio;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;

use Estrutura\Tracos\TracoSalva;
use Estrutura\Tracos\TracoEdita;

class FormProdutos extends Pagina
{
    private $form;
    private $conexao;
    private $registroAtivo;

    use TracoSalva;
    use TracoEdita;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Produto';

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_pessoas'));
        $this->form->defTitulo('Produto');

        # cria os campos do formulário
        $codigo      = new Entrada('id');
        $descricao   = new Entrada('descricao');
        $estoque     = new Entrada('estoque');
        $preco_custo = new Entrada('preco_custo_bruto');
        $preco_venda = new Entrada('preco_venda');
        $fabricante  = new Combo('id_fabricante');
        $tipo        = new GrupoRadio('id_tipo');
        $unidade     = new Combo('id_unidade');

        # carrega os fabricantes do banco de dados
        Transacao::abre($this->conexao);

        $fabricantes = Fabricante::todos();
        $itens = array();
        foreach ($fabricantes as $obj_fabricante) {
            $itens[$obj_fabricante->id] = $obj_fabricante->nome;
        }
        $fabricante->adicItens($itens);

        $tipos = Tipo::todos();
        $itens = array();
        foreach ($tipos as $obj_tipo) {
            $itens[$obj_tipo->id] = $obj_tipo->nome;
        }
        $tipo->adicItens($itens);

        $unidades = Unidade::todos();
        $itens = array();
        foreach ($unidades as $obj_unidade) {
            $itens[$obj_unidade->id] = $obj_unidade->nome;
        }
        $unidade->adicItens($itens);

        Transacao::fecha();

        # define alguns atributos para os campos do formulário
        $codigo->defEditavel(FALSE);

        $this->form->adicCampo('Código',    $codigo, '30%');
        $this->form->adicCampo('Descrição', $descricao, '70%');
        $this->form->adicCampo('Estoque', $estoque, '70%');
        $this->form->adicCampo('Preço Custo', $preco_custo, '70%');
        $this->form->adicCampo('Preço Venda', $preco_venda, '70%');
        $this->form->adicCampo('Fabricante', $fabricante, '70%');
        $this->form->adicCampo('Tipo', $tipo, '70%');
        $this->form->adicCampo('Unidade', $unidade, '70%');

        $this->form->adicAcao('Salvar', new Acao(array($this, 'aoSalvar')));

        # adiciona o formulário à página
        parent::adic($this->form);
    }
}