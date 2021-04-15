<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 11/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe ItensAbasForm
 */
class ItensAbasForm extends Elemento 
{
    private $opcoes_seleciona;

    /**
     * Método construtor
     */
    public function __construct(ItensForm $itens_form, array $parametros = array())
    {
        parent::__construct('div');

        if (isset($parametros['classe'])) {
        	$this->class  = 'row ' . $parametros['classe'];
        } 
        if (isset($parametros['id']) OR (is_null($parametros['id']))) {
        	$this->id  = $parametros['id'];
        }
		
        $this->opcoes_seleciona = $itens_form->obtOpcoesSeleciona();
		$this->itensForm($itens_form->obtLinhasForm());
    }

    /**
     * Método itensForm (Nota: Este método está sendo repetido em Forms, preciso evitar isso)
     */
    public function itensForm($linhaForm)
    {
		# A variável não está sendo usada
        foreach ($linhaForm as $chave => $valor) {

        	# Esse laço cria as DIVs externas de cada linha
        	foreach ($valor as $ch => $vl) {

        		switch ($vl['entrada'][0]) {
        			case 'select':
        				$tipo_entrada = 'select';
        			break;
        			case 'button':
        				$tipo_entrada = 'button';
        			break;
					case 'text':
        				$tipo_entrada = 'input';
        			break;
					case 'textarea':
        				$tipo_entrada = 'textarea';
        			break;
					case 'password':
        				$tipo_entrada = 'input';
        			break;
					case 'email':
        				$tipo_entrada = 'input';
        			break;
        			default:
        				$tipo_entrada = 'input';
        			break;
        		}

	        	$entrada = new Elemento($tipo_entrada);
	        	$entrada->class   	  = $vl['entrada'][2];
	        	$nome = $vl['entrada'][1];
	        	if (isset($nome)) {
	        		$entrada->name = $nome;
	        	}

	        	switch ($vl['entrada'][0]) {
	        		case 'input': 
		        		$entrada->type  	 = $vl['entrada'][0] == 'submit' ? 'submit' : $tipo_entrada;
		        		$entrada->value  	 = $vl['valor'] ?? '';
	        		break;
					case 'text': 
		        		$entrada->type  	 = 'text';
		        		$entrada->value  	 = $vl['valor'] ?? '';
	        		break;
					case 'textarea': 
		        		$entrada->type  	 = 'textarea';
		        		$entrada->adic($vl['valor']) ?? '';
	        		break;
					case 'password': 
		        		$entrada->type  	 = 'password';
		        		$entrada->value  	 = $vl['valor'] ?? '';
	        		break;
					case 'email': 
		        		$entrada->type  	 = 'email';
		        		$entrada->value  	 = $vl['valor'] ?? '';
	        		break;
	        		case 'button':
		        		$entrada->type  	 = 'submit';
		        		$entrada->adic($vl['valor']) ?? '';
	        		break;
	        		case 'submit':
		        		$entrada->type  	 = 'submit';
		        		$entrada->value  	 = $vl['valor'] ?? '';
	        		break;
	        		case 'select':          
			        	if(isset($this->opcoes_seleciona[$nome])) {

				        	foreach ($this->opcoes_seleciona[$nome] as $chave_opcao => $valor_opcao) {

				        		$opcao = new Elemento('option');
				        		$opcao->value = $chave_opcao;
				        		$opcao->adic($valor_opcao);
				        		$entrada->adic($opcao);
				        	}
			        	}
	        		break;
	        	}

	        	if (isset($vl['entrada'][3])) {
	        		$entrada->id = $vl['entrada'][3];
	        	}
	        	if (isset($vl['entrada'][4]) AND ($vl['entrada'][0] == 'text')) {
	        		$entrada->placeholder = $vl['entrada'][4];
	        	}

				if (isset($vl['entrada'][4]) AND is_array($vl['entrada'][4])) {
					$propriedades = $vl['entrada'][4];

					# Acho que é melhor usar um laço aqui.
					$nome__propriedade = array_keys($propriedades[0]);
					$valor_propriedade = array_values($propriedades[0]);
					$propriedade = $nome__propriedade[0];
					$valor_prop = $valor_propriedade[0];
					$entrada->$propriedade = $valor_prop;
				}

		    	$com_rotulo = (count($vl['rotulo']) !== 0) ? true : false;

				$rotulo = new Elemento('label');

				if ($com_rotulo) {
		        	if (!empty($vl['rotulo'][1])) {
		        		$rotulo->class = $vl['rotulo'][1];
		        	}
		        	$rotulo->adic($vl['rotulo'][0]);

					if (isset($vl['entrada'][3])) {
						$rotulo->for = $vl['entrada'][3];
					}
				} 

        		$div = new Elemento('div');
	        	$div->class = $ch;

	        	if ($com_rotulo) { 
	        		$div->adic($rotulo); 
	        	}
	        	$div->adic($entrada);
        	} # Fim do foreach interno
			parent::adic($div);
        } # Fim do foreach externo
    }
}