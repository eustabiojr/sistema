<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/

 # Espaço de nomes
 namespace Estrutura\Bugigangas\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\ItensForm;
use Estrutura\Bugigangas\Form\Submete;

/**
 * Classe Forms
 * 
 * Argumento 1: Os campos são criados na classe ItensAbasForm
 * Argumento 2: É onde os campos são armazenados
 * Argumento 3: Nome do formulário
 * Argumento 4: Parâmetros de cada campo
 * Argumento 5: As abas
 */
class EmbalaForms extends Elemento
{
    private $decorado;
    private $campos;
    private $abas;
    private $parametros;
    protected $acoes;
        
    /**
     * Método construtor
	 * 
	 * ItensForm|NULL 
     */
    public function __construct(Form $form, EmbalaGrupoForm|null $campos, $itens_form, array $parametros = array(), $abas = NULL)
    {
        #parent::__construct('form');
        $this->decorado = $form;   

        $this->campos     = $campos;
        $this->parametros = $parametros;
        $this->abas       = $abas;
	}

    /**
     * Liga/Desliga validação de cliente
     */
    public function defValidacaoCliente($bool) 
    {
        if ($bool) {
            unset($this->elemento->{'novalidate'});
        } else {
            $this->elemento->{'novalidate'}  = '';
        }
    }

    public function exibe()
    {
        if (isset($this->parametros['classe'])) {
        	$this->elemento->class  = 'row ' . $this->parametros['classe'];
        } 

        $this->elemento = new Elemento('form');
        $this->elemento->name           = $this->decorado->obtNome();
        $this->elemento->id             = $this->parametros['id'] ?? NULL;
        $this->elemento->class          = $this->parametros['classe_form'] ?? NULL;
        $this->elemento->{'novalidate'} = $this->parametros['naovalida'] ?? NULL;
        $this->elemento->enctype        = $this->parametros['enctype'] ?? "multipart/form-data";
        $this->elemento->method         = $this->parametros['metodo'] ?? 'post';

        $itens_form = $itens_form ?? new ItensForm(NULL, NULL, []);

		$grupo = new Elemento('div');
        $i = 0;

        # Os botões abaixo 
        foreach ($this->decorado->obtAcoes() as $rotulo => $acao) {
            $nome = strtolower(str_replace(' ', '_', $rotulo));
            $botao = new Botao($nome);
            #$botao = new Submete($nome);
            $botao->defNomeForm($this->decorado->obtNome());
            $botao->defAcao($acao, $rotulo);
            # 
            $botao->class = 'btn ' . ( ($i==0) ? 'btn-primary' : 'btn-success');  
            $grupo->adic($botao);
            $i++;
        }

		if ($this->abas !== NULL) {
            $this->elemento->adic($this->abas);
            # $this->elemento->adic($grupo);
		} else {
			$this->opcoes_seleciona = $itens_form->obtOpcoesSeleciona();
			$this->campos->itensForm($itens_form->obtGrupoCampo());
		}

        //--------------------------------------------------------------------------------------------------------------------
        $cartao_form = new Cartao($this->parametros['params_cartao'], 'div', [], $this->parametros['links_abas']); # links_abas
        $cartao_form->adic($this->elemento);

        $cartao = new Cartao($this->decorado->obtTitulo(), 'h5', []);
        $cartao->adic($cartao_form);
        $cartao->adicRodape($grupo);
        $cartao->exibe();
    }

    /**
     * Método __call
     * 
     * Este método é acionado quando um método inexistente na classe é chamado. Com isso, o método estranho
     * é chamado no objeto decorado. Os seus respectivos parâmetros também são passados. Na prática ele
     * chama métodos da classe form.
     */
    public function __call($metodo, $parametros)
    {
        return call_user_func_array(array($this->decorado,  $metodo), $parametros);
    }
}