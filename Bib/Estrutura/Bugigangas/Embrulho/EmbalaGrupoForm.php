<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 11/04/2021
 ********************************************************************************************/

 # Espaço de nomes
 namespace Estrutura\Bugigangas\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Form\ItensForm;

/**
 * Classe ItensAbasForm
 */
class EmbalaGrupoForm extends Elemento 
{
    private $opcoes_seleciona;
	private $div;

    /**
     * Método construtor
     */
    public function __construct(ItensForm $itens_form, array $parametros = array())
    {
        parent::__construct('div');

        if (isset($parametros['classe'])) {
        	$this->class  = 'row ' . $parametros['classe'];
        } 
		$this->id  = $parametros['id'] ?? NULL;
		
        $this->opcoes_seleciona = $itens_form->obtOpcoesSeleciona();
		$this->itensForm($itens_form->obtGrupoCampo());
    }

    /**
     * Método itensForm (Nota: Este método está sendo repetido em Forms, preciso evitar isso)
     */
    public function itensForm($linhaForm)
    {
        # Esse laço cria as DIVs externas de cada linha
        foreach ($linhaForm as $valor) {

			$campo = $valor[0];
			$param = $valor[1];

			# Elemento rótulo
			$rotulo = new Elemento('label');
	        $rotulo->class = $param['classe_rotulo']; # Classe do rótulo | classe_rotulo
	        $rotulo->adic($campo->obtRotulo());
			if (isset($param['id'])) {
				$rotulo->for = $param['id']; # id
			}

       		$div = new Elemento('div');
        	$div->class = $param['classe_grupo']; # classe do grupo. Ex.: col-md-4
        	$div->adic($rotulo); 

			$campo->class = $param['classe_entrada'];
			if (isset($param['id'])) {
				$campo->id = $param['id']; # id
			}
        	$div->adic($campo);

			$div_valida = new Elemento('div');
			$div_valida->class = 'valid-feedback';
			$div_valida->adic('Parece bom');

			$div_invalida = new Elemento('div');
			$div_invalida->class = 'invalid-feedback';
			$div_invalida->adic('Campo em branco ou inválido');

			$div->adic($div_valida);
			$div->adic($div_invalida);

			parent::adic($div);
       	} # Fim do foreach interno
    }
}