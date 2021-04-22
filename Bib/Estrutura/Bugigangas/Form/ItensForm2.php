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
    private $nome_aba;
	private $opcoes;
    public $grupo_campos;

    /**
     * Método construtor
     */
    public function __construct($nome_aba)
    {
        $this->defNomeAba($nome_aba);
    }

    public function defNomeAba($nome_aba)
    {
        $this->nome_aba = $nome_aba;
    }

    public function obtNomeAba()
    {
        return $this->nome_aba;
    }

    /**
     * Método adicLinhaForm ($classe_linha, $rotulo, array $entrada, $valor = NULL) | $classe_linha, 
     */
    public function adicGrupoForm($rotulo, InterfaceElementoForm $objeto, array $parametros = array())
    {
        $tamanho = $parametros['tamanho'] ?? '100%';
        $objeto->defTamanho($tamanho);
        $objeto->defRotulo($rotulo);
        #$objeto->defClasseRotulo($parametros['classe_rotulo'] ?? NULL);
        
    	$this->grupo_campos[$objeto->obtNome()] = array($objeto, $parametros);
    }

    /**
     * Método obtLinhasForm
     */
    public function obtGrupoCampo()
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
