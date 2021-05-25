<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Exception;
use ReflectionClass;

/**
 * Standard Control Trait
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
trait TracoControlePadrao
{
    protected $bancodados; // Database name
    protected $registroAtivo;    // Active Record class name
    
    /**
     * method setDatabase()
     * Define the database
     */
    public function defBancoDados($bancodados)
    {
        $this->bancodados = $bancodados;
    }
    
    /**
     * method defRegistroAtivo()
     * Define wich Active Record class will be used
     */
    public function defRegistroAtivo($registroAtivo) 
    {
        if (class_exists($registroAtivo))
        {
            if (is_subclass_of($registroAtivo, 'Gravacao'))
            {
                $this->registroAtivo = $registroAtivo;
            }
            else
            {
                
                throw new Exception("A classe {$registroAtivo} não foi aceita como parâmetro. A classe informada como parâmetro deve ser subclasse de Gravacao");
            }
        }
        else
        {
            throw new Exception("A classe {$registroAtivo} não foi localizada. Verifique o nome ou nome do arquivo. Eles devem casar");
        }
    }
}
