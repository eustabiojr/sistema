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
class ItensForm2
{
	private $grupo_campos;
	private $opcoes;

    /**
     * Método adicLinhaForm ($classe_linha, $rotulo, array $entrada, $valor = NULL) | $classe_linha, 
     */
    public function adicGrupoForm($rotulo, InterfaceElementoForm $objeto, array $parametros = array())
    {
        $objeto->defRotulo($rotulo);
        #$objeto->defClasseRotulo($parametros['classe_rotulo'] ?? NULL);
        
    	$this->grupo_campos[$objeto->obtNome()] = array($objeto, $parametros);
    }

    /**
     * Método obtLinhasForm
     */
    public function obtGruposCampo()
    {
    	return $this->grupo_campos;
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
