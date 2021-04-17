<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Form\Form;
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
    private $opcoes_seleciona;
    private $decorado;
    protected $acoes;
    
    /**
     * Método construtor
	 * 
	 * ItensForm|NULL 
     */
    public function __construct(Form $form, EmbalaGrupoForm|null $campos, $itens_form, array $parametros = array(), $abas = NULL)
    {
        parent::__construct('form');

        $this->decorado = $form;

        if (isset($parametros['classe'])) {
        	$this->class  = 'row ' . $parametros['classe'];
        } 
        $this->name    = $this->decorado->obtNome();
        $this->id      = $parametros['id'] ?? NULL;
        $this->enctype = $parametros['enctype'] ?? "multipart/form-data";
        $this->method  = $parametros['metodo'] ?? 'post';

		if ($abas !== NULL) {
			parent::adic($abas);
		} else {
			$this->opcoes_seleciona = $itens_form->obtOpcoesSeleciona();
			$campos->itensForm($itens_form->obtLinhasForm());
		}
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