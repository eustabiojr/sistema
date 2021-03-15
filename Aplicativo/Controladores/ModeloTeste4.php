<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Controle\Pagina;

class ModeloTeste4 extends Pagina
{
    public function exibe() 
    {
        try {
            Transacao::abre('exemplo');

            $p1 = Pessoa::localiza(1);
            print 'Valor total: ' . $p1->totalDebitos() . '<br/>';

            $contas = $p1->obtContasEmAberto();

            if ($contas) {
                foreach ($contas as $conta) {
                    print $conta->dt_emissao . ' - ';
                    print $conta->dt_vencimento . ' - ';
                    print $conta->valor . '<br/>';
                }
            }
            Transacao::fecha();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}