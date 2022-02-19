<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 16/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Registro;

/**
 * Registry interface
 *
 * @version    7.1
 * @package    registry
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
interface InterfaceRegistro
{
    public static function ativado();
    public static function defValor($chave, $valor);
    public static function obtValor($chave);
    public static function apagValor($chave);
    public static function limpa();
}
