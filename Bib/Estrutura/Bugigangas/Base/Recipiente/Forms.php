<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe Forms
 * 
 * Argumento 1: Os campos são criados na classe ItensAbasForm
 * Argumento 2: É onde os campos são armazenados
 * Argumento 3: Nome do formulário
 * Argumento 4: Parâmetros de cada campo
 * Argumento 5: As abas
 */
class Forms extends Elemento
{
    private $opcoes_seleciona;

    /**
     * Método construtor
	 * 
	 * ItensForm|NULL 
     */
    public function __construct(ItensAbasForm|null $campos, $itens_form, $nome_form = 'meu_formulario', array $parametros = array(), $abas = NULL)
    {
        parent::__construct('form');

        if (isset($parametros['classe'])) {
        	$this->class  = 'row ' . $parametros['classe'];
        } 
		if (isset($nome_form) OR $nome_form !== NULL) {
        	$this->name  = $nome_form;
        } 
        if (isset($parametros['id']) OR (is_null($parametros['id']))) {
        	$this->id  = $parametros['id'];
        } 
        if (isset($parametros['metodo'])) {
        	$this->method  = $parametros['metodo'];
        } 

		if ($abas !== NULL) {
			parent::adic($abas);
		} else {
			$this->opcoes_seleciona = $itens_form->obtOpcoesSeleciona();
			$campos->itensForm($itens_form->obtLinhasForm());
		}
	}
}