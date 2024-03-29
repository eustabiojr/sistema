<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

/**
 * Interface Bugiganga
 */
interface InterfaceBugiganga
{
    public function defNome($nome);
    public function obtNome();
    public function defValor($valor);
    public function obtValor();
    public function defNomeForm($nome);
    public function exibe();
    
    # Testar se os protótipos abaixo são necessários (22-Mai-2021)
    //public function defTamanho($tamanho);
    //public function defRotulo($rotulo);
    //public function adicValidacao($valor_rotulo, $validador);

}