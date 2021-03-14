<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Gravacao;

class ItemVenda extends Gravacao
{
    const NOMETABELA = 'item_venda';

    private $produto;

    public function defProduto(Produto $p)
    {
        $this->produto = $p;
        $this->id_produto = $p->id;
    }

    public function obtProduto()
    {
        if (empty($this->produto)) {
            $this->produto = new Produto($this->id_produto);
        }
        return $this->produto;
    }
}