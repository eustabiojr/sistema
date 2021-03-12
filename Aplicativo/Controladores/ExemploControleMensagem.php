<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 11/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Controle\Pagina;

/**
 * Class ExemploControleTwig
 */
class ExemploControleMensagem extends Pagina
{
    public function __construct()
    {
        parent::__construct();

        new Mensagem('info', 'Mensagem informativa');
        #parent::adic($msg);
    }
}