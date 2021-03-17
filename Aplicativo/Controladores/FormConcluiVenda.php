<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 16/03/2021
 ************************************************************************************/

use Estrutura\Controle\Pagina;
use Estrutura\Sessao\Sessao;

/**
 * Classe FormConcluiVenda
 */
class FormConcluiVenda extends Pagina
{
    private $form;

    public function __construct()
    {
        parent::__construct();
        new Sessao;

    }

    public function aoCarregar()
    {
        
    }
}