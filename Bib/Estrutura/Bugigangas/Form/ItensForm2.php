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
	private $grupo_campos;
	private $opcoes;

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
     * Método recuperaCampos
     * 
     * Esse método recupera os objetos em cada linha do array grupo_campos. Elimina o array de 
     * configuração de HTML e CSS
     */
    public function recuperaCampos1()
    {
        $r = $this->obtGrupoCampo();

        foreach ($r as $indice => $obj_grupos) {
            $obj_c[$indice] = $obj_grupos[0];
            #unset($obj_grupos[1]);
        }
        #echo '<pre>';
        #    print_r($obj_c);
        #echo '</pre>';

        return $obj_c; 
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
