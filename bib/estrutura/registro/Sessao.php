<?php
/** ***********************************************************************************
 * Sistema Agenet
 * 
 * Data: 16/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Registro;

use SessionHandlerInterface;

/**
 * Classe Sessao 
 */
class Sessao implements InterfaceRegistro
{
    /**
     * Método Construtor
     */
    public function __construct(SessionHandlerInterface $tratador = NULL, $caminho = NULL)
    {
        if ($caminho)
        {
            session_save_path($caminho);
        }

        if ($tratador)
        {
            session_set_save_handler($tratador, true);
        }

        // caso não exista sessão aberta
        if (!session_id()) 
        {
            session_start();
        }
    }

    /**
     * Retorna se o serviço está ativo
     */
    public static function ativado()
    {   
        if (!session_id())
        {
            return session_start();
        }
        return TRUE;
    }

    /**
     * Define o valor para a variável
     * @param $var Nome da variável
     * @param $valor Valor da variável
     */
    public static function defValor($var, $valor)
    {
        if (defined('NOME_APLICATIVO'))
        {
            $_SESSION[NOME_APLICATIVO][$var] = $valor;
        } else {
            $_SESSION[$var] = $valor;
        }
    }

    /**
     * Método obtValor
     * 
     * Retorna o valor para uma variável
     * @param $var Nome variável
     */
    public static function obtValor($var)
    {
        if (defined('NOME_APLICATIVO'))
        {
            if (isset($_SESSION[NOME_APLICATIVO][$var]))
            {
                return $_SESSION[NOME_APLICATIVO][$var];
            } 
        } else {
            if (isset($_SESSION[$var])) {
                return $_SESSION[$var];
            }
        }
    }

    /**
     * Limpa o valor para uma variável
     * @param $var Nome variável
     */
    public static function apagValor($var)
    {
        if (defined('NOME_APLICATIVO'))
        {
            unset($_SESSION[NOME_APLICATIVO][$var]);
        } else {
            unset($_SESSION[$var]);
        }
    }

    /**
     * Regenera id
     */
    public static function regenera()
    {
        session_regenerate_id();
    }

    /*********
     * Limpa sessão
     */
    public static function limpa()
    {
        self::liberaSessao();
    }

    /**
     * Método liberaSessao
     */
    public static function liberaSessao()
    {
        if (defined('NOME_APLICATIVO'))
        {
            $_SESSION[NOME_APLICATIVO] = array();
        } else {
            $_SESSION[] = array();
        }       
    }
}