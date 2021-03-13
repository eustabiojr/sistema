<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\Pagina;

class ExemploControleElemento extends Pagina {

    public function __construct()
    {
        parent::__construct();
        $div = new Elemento('div');
        $div->style  = 'text-align: center;';
        $div->style .= 'font-weight: bold;';
        $div->style .= 'font-size: 14pt;';

        $p = new Elemento('p');
        $p->adic('Sport Clube Bahia');
        $div->adic($p);
        
        $img = new Elemento('img');
        $img->src = 'Aplicativo/Imagens/bahia.png';
        $div->adic($img);

        $p = new Elemento('p');
        $p->adic('Cludo dos baianos');
        $div->adic($p);
        
        parent::adic($div);
    }
}