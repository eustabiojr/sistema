<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 16/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Registro;

/**
 * Adianti APC Record Cache
 *
 * @version    7.1
 * @package    registry
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CPACache implements InterfaceRegistro
{
    /**
     * Returns if the service is active
     */
    public static function ativado()
    {
        return extension_loaded('apcu');
    }
    
    /**
     * Store a variable in cache
     * @param $chave    Key
     * @param $valor  Value
     */
    public static function defValor($chave, $valor)
    {
        return apcu_store(NOME_APLICATIVO . '_' . $chave, serialize($valor));
    }
    
    /**
     * Get a variable from cache
     * @param $chave    Key
     */
    public static function obtValor($chave)
    {
        return unserialize(apcu_fetch(NOME_APLICATIVO . '_' . $chave));
    }
    
    /**
     * Delete a variable from cache
     * @param $chave    Key
     */
    public static function apagValor($chave)
    {
        return apcu_delete(NOME_APLICATIVO . '_' . $chave);
    }
    
    /**
     * Clear cache
     */
    public static function limpa()
    {
        return apcu_clear_cache();
    }
}
