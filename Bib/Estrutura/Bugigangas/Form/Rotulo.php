<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Form\Campo;
use Estrutura\Bugigangas\Form\InterfaceElementoForm;

 /**
  * Class Rotulo
  */
class Rotulo extends Campo implements InterfaceElementoForm
{
    /**
     * Método __construct
     */
    public function __construct($valor)
    {
        $this->defValor($valor);
        $this->tag = new Elemento('label');
    }

    /**
     * Método adic
     */
    public function adic($filho)
    {
        $this->tag->adic($filho);
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        $this->tag->adic($this->valor);
        $this->tag->exibe();
    }
}