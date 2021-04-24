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

			#echo '<pre>';
			#	print_r($param);
	 		#echo '</pre>';
			/*
        	switch ($campo['entrada'][0]) {
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

			# elemento de formulário
	       	$entrada = new Elemento($tipo_entrada);
	       	$entrada->class   	  = $campo['entrada'][2]; # nome do campo. Ex.: cpf
	       	$nome = $campo['entrada'][1];
	       	if (isset($nome)) {
	       		$entrada->name = $nome;
	       	}

	       	switch ($campo['entrada'][0]) { # tipo do campo. Ex.: text
	       		case 'input': 
		       		$entrada->type  	 = $campo['entrada'][0] == 'submit' ? 'submit' : $tipo_entrada; 
		       		$entrada->value  	 = $campo['valor'] ?? '';
	       		break;
				case 'text': 
		       		$entrada->type  	 = 'text';
		       		$entrada->value  	 = $campo['valor'] ?? '';
	       		break;
				case 'textarea': 
		       		$entrada->type  	 = 'textarea';
		       		$entrada->adic($campo['valor']) ?? '';
	       		break;
				case 'password': 
		       		$entrada->type  	 = 'password';
		       		$entrada->value  	 = $campo['valor'] ?? '';
	       		break;
				case 'email': 
		       		$entrada->type  	 = 'email';
		       		$entrada->value  	 = $campo['valor'] ?? '';
	       		break;
	       		case 'button':
		       		$entrada->type  	 = 'submit';
		       		$entrada->adic($campo['valor']) ?? '';
	       		break;
	       		case 'submit':
		       		$entrada->type  	 = 'submit';
		       		$entrada->value  	 = $campo['valor'] ?? '';
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

	       	if (isset($campo['entrada'][3])) { # classe do campo. Ex.: form-control
	       		$entrada->id = $campo['entrada'][3];
	       	}
	       	if (isset($campo['entrada'][4]) AND ($campo['entrada'][0] == 'text')) {
	       		$entrada->placeholder = $campo['entrada'][4]; # id do campo. Ex.: inputCPF1
	       	}*/

			# Definimos aqui as demais propriedades
			/*
			if (isset($campo['entrada'][4]) AND is_array($campo['entrada'][4])) {
				$propriedades = $campo['entrada'][4];

				foreach ($propriedades[0] as $nome__propriedade => $valor_propriedade) {
					$propriedade = $nome__propriedade;
					$valor_prop = $valor_propriedade;
					$entrada->$propriedade = $valor_prop;
				}
			}*/

	    	#$com_rotulo = (count($campo['rotulo']) !== 0) ? true : false; # rótulo do campo. Ex.: CPF

			# Elemento rótulo
			$rotulo = new Elemento('label');

			#if ($com_rotulo) {
	        	#if (!empty($campo['rotulo'][1])) { # classe do rótulo. Ex.: form-label
	        		$rotulo->class = $param['classe_rotulo']; # Classe do rótulo | classe_rotulo
	        	#}
	        	$rotulo->adic($campo->obtRotulo());

				if (isset($param['id'])) {
					$rotulo->for = $param['id']; # id
				}
			#} 

       		$div = new Elemento('div');
        	$div->class = $param['classe_grupo']; # classe do grupo. Ex.: col-md-4

        	#if ($com_rotulo) { 
        		$div->adic($rotulo); 
        	#}
			$campo->class = $param['classe_entrada'];
			if (isset($param['id'])) {
				$campo->id = $param['id']; # id
			}
        	$div->adic($campo);
			parent::adic($div);
       	} # Fim do foreach interno
    }
}