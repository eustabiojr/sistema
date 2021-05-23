<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;
/**
 * Base class for TCriteria and TFilter (composite pattern implementation)
 *
 * @version    7.1
 * @package    database
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
abstract class Expressao
{
    // logic operators
    const OPERATOR_E = 'AND ';
    const OPERADOR_OU  = 'OR ';
    
    // force method rewrite in child classes
    abstract public function despeja();
}
