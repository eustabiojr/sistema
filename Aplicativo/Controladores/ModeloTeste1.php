<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Controle\Pagina;

class ModeloTeste1 extends Pagina
{
    public function exibe() 
    {
        try {
            Transacao::abre('exemplo');
            $c1 = Cidade::localiza(12);
            print($c1->nome) . '<br/>';
            print($c1->estado->nome) . '<br/>';
            print($c1->NomeEstado);
            $p1 = Pessoa::localiza(2);
            print($p1->nome) . '<br/>';
            print($p1->nome_cidade) . '<br/>';
            print($p1->cidade->nome) . '<br/>';
            print($p1->cidade->estado->nome) . '<br/>';
            Transacao::fecha();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}