<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Nucleo;

/**
 * Classe ConfigAplicativo
 */
class ConfigAplicativo
{
    private static $config;

    /**
     * Carrega configuração do array
     */
    public static function carrega($config)
    {
        if (is_array($config)) {
            self::$config = $config;
        }
    }

    /**
     * Aplica algumas configurações que muda vars env
     */
    public static function aplica()
    {
        if (!empty(self::$config['geral']['depuracao']) && self::$config['geral']['depuracao'] == '1') {
            ini_set('display_errors', '1');
            ini_set('error_reporting', E_ALL);
            ini_set('html_errors', 1);
            ini_set('error_prepend_string', '<pre>');
            ini_set('error_append_string', '</pre>');
        }
    }

    /**
     * Exporta configuração
     */
    public static function obt()
    {
        return self::$config;
    }
}