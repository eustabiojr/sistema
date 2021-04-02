<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe Forms
 */
class Forms extends Elemento
{
    #private $corpo;

    /**
     * Método construtor
     */
    public function __construct()
    {
        parent::__construct('div');

        $this->class = 'form';
    }
}