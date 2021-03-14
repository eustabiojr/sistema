<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Gravacao;
use Estrutura\BancoDados\Repositorio;

/**
 * Classe Venda
 */
class Venda extends Gravacao 
{
    private $itens;
    private $cliente;

    const NOMETABELA = 'venda';

    public function defCliente(Pessoa $c)
    {
        $this->cliente = $c;
        $this->id_cliente = $c->id;
    }

    public function obtCliente() 
    {
        if (empty($this->cliente)) {
            $this->cliente = new Pessoa($this->id_cliente);
        }
        return $this->cliente;
    }

    public function adicItem(Produto $p, $quantidade)
    {
        $item = new ItemVenda;
        $item->produto     = $p;
        $item->preco       = $p->preco_venda;
        $item->quantidade  = $quantidade;
        $this->itens[]     = $item;
        $this->valor_venda += ($item->preco * $quantidade);
    }

    public function grava() 
    {
        parent::grava();
        # percorre os itens da venda
        foreach ($this->itens as $item) {
            $item->id_venda = $this->id;
            $item->grava();
        }
    }

    public function obtItens()
    {
        # instancia um repositório de item
        $repositorio = new Repositorio('ItemVenda');
        # define o critério de filtro
        $criterio = new Criterio;
        $criterio->adic('id_venda', '=', $this->id);
        $this->itens = $repositorio->carrega($criterio);
        return $this->itens;
    }
}