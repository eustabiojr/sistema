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
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\ItensForm;
use Estrutura\Controle\InterfaceAcao;

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

    public function exibe()
    {
        if (isset($this->parametros['classe'])) {
        	$this->elemento->class  = 'row ' . $this->parametros['classe'];
        } 

        $this->elemento = new Elemento('form');
        $this->elemento->name    = $this->decorado->obtNome();
        $this->elemento->id      = $this->parametros['id'] ?? NULL;
        $this->elemento->enctype = $this->parametros['enctype'] ?? "multipart/form-data";
        $this->elemento->method  = $this->parametros['metodo'] ?? 'post';

        $itens_form = $itens_form ?? new ItensForm(NULL, NULL, []);

		if ($this->abas !== NULL) {
			#parent::adic($abas);
            $this->elemento->adic($this->abas);
		} else {
			$this->opcoes_seleciona = $itens_form->obtOpcoesSeleciona();
			$this->campos->itensForm($itens_form->obtLinhasForm());
		}

        # É bem provável que eu resolva colocar esse cartão dentro da classe EmbalaGrupoForm
        $parametros = array('titulo_cartao' => " ", 'id' => 'idAbaPessoa', 'role' => 'tablist');
        $cartao_form = new Cartao($parametros, 'div', [], $this->parametros['links_abas']); # links_abas
        $cartao_form->adic($this->elemento);

        $cartao = new Cartao("Pessoas", 'h5', []);
        $cartao->adic($cartao_form);
        $cartao->adicRodape('Botao');
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