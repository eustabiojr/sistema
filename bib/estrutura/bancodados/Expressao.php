<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 16/01/2022
 ***************************************************************************************/

namespace Estrutura\BancoDados;

abstract class Expressao {
    # operadores lógicos
    const OPERADOR_E = 'AND ';
    const OPERADOR_OU = 'OR ';

    abstract public function despeja();
}