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
    public function adicCampo(InterfaceBugiganga $campo);
    public function apagCampos(InterfaceBugiganga $campo);
    public function defCampos($campos);
    public function obtCampo($nome);
    public function obtCampos();
    public function limpa();
    public function defDados($objeto);
    public function obtDados($classe = 'StdClass');
    public function valida();
    public function exibe();
}

