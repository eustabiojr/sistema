<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 16/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\CaixaV;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Embrulho\EmbrulhoGradedados;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;
use Estrutura\Tracos\TracoApaga;
use Estrutura\Tracos\TracoEdita;
use Estrutura\Tracos\TracoRecarrega;
use Estrutura\Tracos\TracoSalva;

/**
 * Classe ListaFormCidades
 */
class ListaFormCidades extends Pagina
{
    private $form, $gradedados, $carregado, $conexao, $registroAtivo;

    use TracoEdita; 
    use TracoApaga;
    use TracoRecarrega {
        aoRecarregar as tracoAoRecarregar;
    }
    use TracoSalva { 
        aoSalvar as tracoAoSalvar;
    }

    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Cidade';

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_cidades'));
        $this->form->defTitulo('Cidades');

        # cria os campos do formulário
        $codigo = new Entrada('id');
        $descricao   = new Entrada('nome');
        $estado = new Combo('id_estado');

        $codigo->defEditavel(FALSE);

        Transacao::abre($this->conexao);

        $estados = Estado::todos();
        $itens = array();
        foreach ($estados as $obj_estado) {
            $itens[$obj_estado->id] = $obj_estado->nome;
        }
        $estado->adicItens($itens);

        Transacao::fecha();

        $this->form->adicCampo('Código',    $codigo, '30%');
        $this->form->adicCampo('Descrição', $descricao, '70%');
        $this->form->adicCampo('Estado', $estado, '70%');

        $this->form->adicAcao('Salvar', new Acao(array($this, 'aoSalvar')));
        $this->form->adicAcao('Limpar', new Acao(array($this, 'aoEditar')));

        # instancia objeto grade de dados
        $this->gradedados = new EmbrulhoGradedados(new Gradedados);

        $codigo = new ColunaGradedados('id',          'Código', 'center', '10%');
        $nome   = new ColunaGradedados('nome',        'Nome',   'left',   '50%');
        $estado = new ColunaGradedados('nome_estado', 'Estado', 'left',   '40%');

        # adiciona as colunas à grade de dados
        $this->gradedados->adicColuna($codigo);
        $this->gradedados->adicColuna($nome);
        $this->gradedados->adicColuna($estado);

        $this->gradedados->adicAcao('Editar', new Acao([$this, 'aoEditar']),
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

    /**
     * Método aoSalvar
     */
    public function aoSalvar()
    {
        $this->tracoAoSalvar();
        $this->aoRecarregar();
    }

    /**
     * Método aoRecarregar
     */
    public function aoRecarregar()
    {
        $this->tracoAoRecarregar();
        $this->carregado = TRUE;
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