<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/

 # Espaço de nomes
 namespace Estrutura\Bugigangas\Gradedados;

use Estrutura\Controle\InterfaceAcao;

/**
 * Classe Gradedados
 */
class Gradedados 
{
    private $colunas;
    private $itens;
    private $acoes;

    /**
     * Método adicColuna
     */
    public function adicColuna(ColunaGradedados $objeto)
    {
        $this->colunas[] = $objeto;
    }

    /**
     * Método adicAcao
     */
    public function adicAcao($rotulo, InterfaceAcao $acao, $campo, $imagem = NULL)
    {
        $this->acoes[] = ['label' => $rotulo, 'action' => $acao, 'field' => $campo, 'image' => $imagem];
    }

    /**
     * Método adicItem
     */
    public function adicItem($objeto)
    {
        $this->itens[] = $objeto;

        foreach ($this->colunas as $coluna) {
            $nome = $coluna->obtNome();
            if (!isset($objeto->$nome)) {
                # chama o método de acesso
                $objeto->$nome;
            }
        }
    }

    /**
     * Método obtColunas
     */
    public function obtColunas() 
    {
        return $this->colunas;
    }

    /**
     * Método obtItens
     */
    public function obtItens() 
    {
        return $this->itens;
    }

    /**
     * Método obtAcoes
     */
    public function obtAcoes() 
    {
        return $this->acoes;
    }

    /**
     * Método limpa
     */
    public function limpa() 
    {
        $this->itens = [];
    }
}