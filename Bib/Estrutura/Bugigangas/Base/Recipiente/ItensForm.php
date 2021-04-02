<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

/**
 * Classe ItensForm
 */
class ItensForm
{
	private $linha;
	private $opcoes;

	# 'rotulo' => array($classe, $conteudo)
	# 'entrada' => array($tipo, $nome, $classe_entrada, $marcador)
    public function adicLinhaForm($classe_linha, array $rotulo, array $entrada, $valor = NULL)
    {
    	$this->linha[][$classe_linha] = array('rotulo' => $rotulo, 'entrada' => $entrada, 'valor' => $valor);
    }

    public function obtLinhasForm()
    {
    	return $this->linha;
    }

    public function adicGrupoForm()
    {
        
    }

    public function defOpcoesSeleciona($campo,array $opcoes)
    {
    	$this->opcoes[$campo] = $opcoes;
    }

    public function obtOpcoesSeleciona()
    {
    	return $this->opcoes;
    }
}