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
 * Classe CaixaV
 */
class CaixaV extends Elemento {

    /**
     * Método __construct()
     */
    public function __construct()
    {   
        parent::__construct('div');
        $this->{'style'} = 'display: inline-block';
    }

    public function adic($filho)
    {
        $embalagem = new Elemento('div');
        $embalagem->{'style'} = 'clear: both';
        $embalagem->adic($filho);
        parent::adic($embalagem);
        return $embalagem;
    }
}