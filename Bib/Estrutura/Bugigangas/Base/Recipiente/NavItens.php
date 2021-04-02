<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

/**
 * Classe Cartao
 */
class NavItens
{
	private $params;

    /**
     * Método adicLink
     */
    public function adicItem($tipo, $nome, $subclasse)
    {
    	if ($tipo == 'links') {
    		$this->params[$tipo][] = array($nome, $subclasse);
    	} else {
        	$this->params[$tipo][$nome] = $subclasse;
    	}
    }

    /**
     * Método adicLink
     */
    public function obtItens()
    {
        return isset($tipo) ? $this->params[$tipo] : $this->params;
    }
}