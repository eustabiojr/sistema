<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

/**
 * Classe ItensForm
 */
class ItensForm
{
	private $linha;
	private $opcoes;

    /**
     * Método adicLinhaForm
     */
    public function adicLinhaForm($classe_linha, $rotulo, array $entrada, $valor = NULL)
    {
    	if (is_string($rotulo)) {
    		$rotulo = array($rotulo, '');
    	}
    	$this->linha[][$classe_linha] = array('rotulo' => $rotulo, 'entrada' => $entrada, 'valor' => $valor);
    }

    /**
     * Método obtLinhasForm
     */
    public function obtLinhasForm()
    {
    	return $this->linha;
    }

    /**
     * Método defOpcoesSeleciona
     */
    public function defOpcoesSeleciona($campo,array $opcoes)
    {
    	$this->opcoes[$campo] = $opcoes;
    }

    /**
     * Método obtOpcoesSeleciona
     */
    public function obtOpcoesSeleciona()
    {
    	return $this->opcoes;
    }
}
