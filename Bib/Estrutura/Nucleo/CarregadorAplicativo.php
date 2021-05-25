<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Nucleo;

use Estrutura\Bugigangas\Dialogo\Mensagem;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

use Exception;

/**
 * Application loader
 *
 * @version    7.1
 * @package    core
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CarregadorAplicativo
{
    public static function autocarregador($classe)
    {
        // echo "&nbsp;&nbsp;App loader $classe<br>";
        $pastas = array();
        $pastas[] = 'Aplicativo/Modelos';
        $pastas[] = 'Aplicativo/Controladores';
        $pastas[] = 'Aplicativo/Visoes';
        $pastas[] = 'Aplicativo/Bibs';
        $pastas[] = 'Aplicativo/Assistentes';
        $pastas[] = 'Aplicativo/Servicos';
        
        // search in app root
        if (file_exists("{$classe}.class.php"))
        {
            require_once "{$classe}.class.php";
            return TRUE;
        }
        
        // search in app root
        if (file_exists("{$classe}.php"))
        {
            require_once "{$classe}.php";
            return TRUE;
        }
        
        foreach ($pastas as $pasta)
        {
            if (file_exists("{$pasta}/{$classe}.class.php"))
            {
                require_once "{$pasta}/{$classe}.class.php";
                return TRUE;
            }
            if (file_exists("{$pasta}/{$classe}.php"))
            {
                require_once "{$pasta}/{$classe}.php";
                return TRUE;
            }
            else if (file_exists("{$pasta}/{$classe}.iface.php"))
            {
                require_once "{$pasta}/{$classe}.iface.php";
                return TRUE;
            }
            else
            {
                try
                {
                    if (file_exists($pasta))
                    {
                        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pasta),
                                                               RecursiveIteratorIterator::SELF_FIRST) as $entrada)
                        {
                            if (is_dir($entrada))
                            {
                                if (file_exists("{$entrada}/{$classe}.class.php"))
                                {
                                    require_once "{$entrada}/{$classe}.class.php";
                                    return TRUE;
                                }
                                else if (file_exists("{$entrada}/{$classe}.php"))
                                {
                                    require_once "{$entrada}/{$classe}.php";
                                    return TRUE;
                                }
                                else if (file_exists("{$entrada}/{$classe}.iface.php"))
                                {
                                    require_once "{$entrada}/{$classe}.iface.php";
                                    return TRUE;
                                }
                            }
                        }
                    }
                }
                catch(Exception $e)
                {
                    new Mensagem('error', $e->getMessage());
                }
            }
        }
    }
}
