<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Controle\Pagina;

class ModeloTeste3 extends Pagina
{
    public function exibe() 
    {
        try {
            Transacao::abre('exemplo');

            # define atributos da venda
            $venda = new Venda;
            $venda->cliente = new Pessoa(3);
            $venda->data_movimento = date('Y-m-d');
            $venda->valor = 0;
            $venda->desconto = 0;
            $venda->acrescimos = 0;
            $venda->observacoes = 'Obs';

            # adiciona itens
            $venda->adicItem(new Produto(3), 2);
            $venda->adicItem(new Produto(4), 1);

            # atualiza valor
            $venda->valor_final = $venda->valor * $venda->acrescimos - $venda->desconto;

            # grava venda e itens
            $venda->grava();
            Transacao::fecha();


        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}