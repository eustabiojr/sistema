<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 11/04/2021
 ********************************************************************************************/

 # Espaço de nomes
 namespace Estrutura\Bugigangas\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Form\ItensForm2;

/**
 * Classe ItensAbasForm
 */
class EmbalaGrupoForm2 extends Elemento 
{
    private $opcoes_seleciona;
	private $div;

    /**
     * Método construtor
     */
    public function __construct(ItensForm2 $itens_form, array $parametros = array())
    {
        parent::__construct('div');

		#echo "HHHHHHHHHHHHHHHHHH";

        if (isset($parametros['classe'])) {
        	$this->class  = 'row ' . $parametros['classe'];
        } 
		$this->id  = $parametros['id'] ?? NULL;
		
        $this->opcoes_seleciona = $itens_form->obtOpcoesSeleciona();
		$this->itensForm($itens_form->obtGruposCampo());
    }

    /**
     * Método itensForm (Nota: Este método está sendo repetido em Forms, preciso evitar isso)
     */
    public function itensForm($linhaForm)
    {
		echo '<pre>';
			#print_r($linhaForm);
	 	echo '</pre>';

		#echo "XXXXXXX";
		foreach ($linhaForm as $chave => $campos) {
			#echo "ZZZZZZZZZZZZ--" . $chave;

			#echo '<pre>';
				#print_r($campos);
		 	#echo '</pre>';

			#foreach($campos[0] as $valor) {

				#echo "YYYYYYYYYYYY";
				$this->div = new Elemento('div');
				$this->div->class = 'class_boot';

				$rotulo = new Elemento('label');
				$rotulo->class = $campos[1]['classe_rotulo'];
				$rotulo->adic('Etiqueta');

				# elemento de formulário
	        	$entrada = new Elemento('input');
	        	$entrada->class = 'col-md-6'; # $campos[1]['classe_entrada']; # 'col-md-6';
	        	#$nome = $vl['entrada'][1];
				$entrada->name = 'JJJJJ'; # $valor->nome;
	        	#if (isset($nome)) {
	        		#$entrada->name = $nome;
	        	#}

				$this->div->adic($rotulo);
				$this->div->adic($campos[0]);
				#$this->div->exibe();
			#}
			parent::adic($this->div);
		}
    }

	/**
	 * 		# A variável não está sendo usada
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

				# elemento de formulário
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

				# Definimos aqui as demais propriedades
				if (isset($vl['entrada'][4]) AND is_array($vl['entrada'][4])) {
					$propriedades = $vl['entrada'][4];

					foreach ($propriedades[0] as $nome__propriedade => $valor_propriedade) {
						$propriedade = $nome__propriedade;
						$valor_prop = $valor_propriedade;
						$entrada->$propriedade = $valor_prop;
					}
				}

		    	$com_rotulo = (count($vl['rotulo']) !== 0) ? true : false;

				# Elemento rótulo
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

			# Estou pensando em incluir o resultado em um Cartao.
			parent::adic($div);
        } # Fim do foreach externo
	 */
}