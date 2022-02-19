<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 08/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Controle;

interface InterfaceAcao {
    public function defParametro($param, $valor);
    public function serializa();
}