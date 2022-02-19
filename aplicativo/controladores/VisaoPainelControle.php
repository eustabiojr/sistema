<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 18/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\CaixaH;
use Estrutura\Controle\Pagina;

/**
 * Classe VisaoPainelControle
 */
class VisaoPainelControle extends Pagina
{
    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        $caixa_hor = new CaixaH;
        $caixa_hor->adic(new GraficoVendasMes)->style .= ';width: 48%';
        $caixa_hor->adic(new GraficoVendasTipo)->style .= ';width: 48%';

        parent::adic($caixa_hor);
    }
}