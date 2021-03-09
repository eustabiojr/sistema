<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe CaixaH
 */
class CaixaH extends Elemento {

    /**
     * Método __construct()
     */
    public function __construct()
    {   
        parent::__construct('div');
    }

    public function adic($filho)
    {
        $embalagem = new Elemento('div');
        $embalagem->{'style'} = 'display: inline-block';
        $embalagem->adic($filho);
        parent::adic($embalagem);
        return $embalagem;
    }
}