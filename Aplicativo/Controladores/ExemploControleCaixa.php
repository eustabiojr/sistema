<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\CaixaH;
use Estrutura\Bugigangas\Base\Recipiente\Painel;
use Estrutura\Controle\Pagina;

class ExemploControleCaixa extends Pagina
{
    public function __construct()
    {
        parent::__construct();

        $painel1 = new Painel('Painel 1');
        $painel1->style = 'margin: 10px';
        $painel1->adic(str_repeat('sdf sdf sdf sdf sdf sdf sdf sdf sdf sdf', 5));

       
        $painel2 = new Painel('Painel 2');
        $painel2->style = 'margin: 10px';
        $painel2->adic(str_repeat('sdf sdf sdf sdf sdf sdf sdf sdf sdf sdf', 5));

        $caixa = new CaixaH;
        $caixa->adic($painel1);
        $caixa->adic($painel2);
        $caixa->exibe();
    }
}