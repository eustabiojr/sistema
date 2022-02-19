<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Dialogo;

use Estrutura\Bugigangas\Base\Elemento;

/**
  * Classe Mensagem
  */
class Mensagem {

    /**
     * Classe __construct
     */
    public function __construct($tipo, $mensagem)
    {
        $div = new Elemento('div');
        if ($tipo == 'info') {
            $div->class = 'alert alert-info';
        } else if ($tipo == 'erro') {
            $div->class = 'alert alert-danger';
        }
        $div->adic($mensagem);
        $div->exibe();
    }
}