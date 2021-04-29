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
    public function adicGrupoForm($rotulo, InterfaceElementoForm $objeto, $props_grupo, array $props_rotulo, array $props_entrada, array $props_validacao = array())
    {
        $tamanho = $parametros['tamanho'] ?? '100%';
        $objeto->defTamanho($tamanho);
        $objeto->defRotulo($rotulo);

        $grupo     = array('grupo'   => array('class' => $props_grupo));
        $rotulo    = array('rotulo'  => array_merge(array('class' => 'form-label'), $props_rotulo));
        #$validacao = array('validacao'  => array_merge(array('class' => 'valid-feedback'), $props_validacao'));

        # Propriedades entrada
        if (!key_exists('class', $props_entrada)) {
            $entrada   = array('entrada' => array_merge(array('class' => 'form-control'), $props_entrada));
        } else {
            $entrada   = array('entrada' => $props_entrada);
        }

        #$validacao = array('validacao' => $props_entrada);

        /*if (isset($validacao)) {
            $parametros = array_merge($grupo, $rotulo, $entrada, $validacao);
        } else {*/
            $parametros = array_merge($grupo, $rotulo, $entrada);
        #}
        
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
