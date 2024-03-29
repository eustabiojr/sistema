<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Embrulho;

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
			if (isset($param['rotulo'])) {
				foreach ($param['rotulo'] as $nome_prop_rotulo => $valor_prop_rotulo) {
					$rotulo->$nome_prop_rotulo = $valor_prop_rotulo;
				}
			}
	        $rotulo->adic($campo->obtRotulo());
			if (isset($param['entrada']['id'])) {
				$rotulo->for = $param['entrada']['id']; # id
			}

       		$div_grupo = new Elemento('div');
			if (isset($param['grupo'])) {
				foreach ($param['grupo'] as $nome_prop_grupo => $valor_prop_grupo) {
					$div_grupo->$nome_prop_grupo = $valor_prop_grupo; # classe do grupo. Ex.: col-md-4
				}
			}
        	$div_grupo->adic($rotulo); 

			if (isset($param['entrada'])) {
				foreach ($param['entrada'] as $prop => $valor_prop) {
					if ($valor_prop !== NULL) {
						$campo->defPropriedade($prop, $valor_prop);
					} else {
						$campo->defPropriedade($prop, NULL);
					}
				}
			}
        	$div_grupo->adic($campo);

			if (isset($param['validacao'])) {

				$div_valida = new Elemento('div');
				$div_valida->class = 'valid-feedback';
				$msg_ok = $param['validacao']['feedback_ok'] ?? 'Parece bom';
				$div_valida->adic($msg_ok);
	
				$div_invalida = new Elemento('div');
				$div_invalida->class = 'invalid-feedback';
				$msg_erro = $param['validacao']['feedback_erro'] ?? 'Campo em branco ou inválido';
				$div_invalida->adic($msg_erro);
	
				$div_grupo->adic($div_valida);
				$div_grupo->adic($div_invalida);	
			}

			parent::adic($div_grupo);
       	} # Fim do foreach interno
    }
}