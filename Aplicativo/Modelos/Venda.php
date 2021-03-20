<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Gravacao;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;

/**
 * Classe Venda
 */
class Venda extends Gravacao 
{
    private $itens;
    private $cliente;

    const NOMETABELA = 'movimento_estoque';

    /**
     * Classe def_cliente
     */
    public function def_cliente(Pessoa $c)
    {
        $this->cliente = $c;
        $this->id_cliente = $c->id;
    }

    /**
     * Classe obt_cliente
     */
    public function obt_cliente() 
    {
        if (empty($this->cliente)) {
            $this->cliente = new Pessoa($this->id_cliente);
        }
        return $this->cliente;
    }

    /**
     * Classe adicItem
     */
    public function adicItem(Produto $p, $quantidade)
    {
        $item = new ItemVenda;
        $item->produto     = $p;
        $item->preco       = $p->preco_venda;
        $item->quantidade  = $quantidade;
        $this->itens[]     = $item;
        $this->valor += ($item->preco * $quantidade);
    }

    /**
     * Classe grava
     */
    public function grava() 
    {
        parent::grava();
        # percorre os itens da venda
        foreach ($this->itens as $item) {
            $item->id_venda = $this->id;
            $item->grava();
        }
    }

    /**
     * Classe obtItens
     */
    public function obtItens()
    {
        # instancia um repositório de item
        $repositorio = new Repositorio('ItemVenda');
        # define o critério de filtro
        $criterio = new Criterio;
        $criterio->adic('id_movimento_estoque', '=', $this->id);
        $this->itens = $repositorio->carrega($criterio);
        return $this->itens;
    }

    /**
     * Classe obtVendasMes
     */
    public static function obtVendasMes()
    {
        $meses = array();
        $meses[1] = 'Janeiro';
        $meses[2] = 'Fevereiro';
        $meses[3] = 'Março';
        $meses[4] = 'Abril';
        $meses[5] = 'Maio';
        $meses[6] = 'Junho';
        $meses[7] = 'Julho';
        $meses[8] = 'Agosto';
        $meses[9] = 'Setembro';
        $meses[10] = 'Outubro';
        $meses[11] = 'Novembro';
        $meses[12] = 'Dezembro';

        $conexao = Transacao::obt();
        /*$resultado = $conexao->query("SELECT strftime('%m', data_movimento) AS mes, sum(valor_final) AS
            valor FROM movimento_estoque GROUP BY 1"); */

        $resultado = $conexao->query("SELECT date_part('MONTH',data_movimento) AS mes, sum(valor_final) AS valor
                                         FROM movimento_estoque GROUP BY 1");
        $grupodados = [];
        foreach ($resultado as $linha) {
            $mes = $meses[ (int) $linha['mes']];
            $grupodados[$mes] = $linha['valor'];
        }
        return $grupodados;

        # SELECT * FROM movimento_estoque GROUP BY 1;
        # SELECT valor_final FROM movimento_estoque GROUP BY 1;
        # SELECT valor_final AS valor FROM movimento_estoque GROUP BY 1;
        # SELECT sum(valor_final) AS valor FROM movimento_estoque GROUP BY 1;
        # SELECT id, sum(valor_final) AS valor FROM movimento_estoque GROUP BY 1;
        # SELECT date_part('MONTH',data_movimento) AS mes, sum(valor_final) AS valor FROM movimento_estoque GROUP BY 1;
    }
}