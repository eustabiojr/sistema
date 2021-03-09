<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

/**
 * Interface InterfaceElementoForm
 */
interface InterfaceElementoForm {
    public function defNome($nome);
    public function obtNome();
    public function defValor($valor);
    public function obtValor();
    public function exibe();
}

