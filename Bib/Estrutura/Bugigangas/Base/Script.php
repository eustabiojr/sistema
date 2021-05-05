<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 04/05/2021
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Base;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe base para scripts
 *
 * @version    0.1
 * @package    bugigangas
 * @subpackage base
 * @author     Pablo Dall'Oglio (e alterado por Eustábio J. Silva Jr.)
 * @copyright  ??
 * @license    ??
 */
class Script
{
    /**
     * Cria um script
     * @param $codigo código fonte
     */
    public static function cria( $codigo, $exibe = TRUE, $expira = null )
    {
        if ($expira)
        {
            $codigo = "setTimeout( function() { $codigo }, $expira )";
        }
        
        $script = new Elemento('script');
        $script->{'language'} = 'JavaScript';
        $script->defUsaAspasSimples(TRUE); 
        $script->defUsaQuebraLinha(FALSE); 
        $script->adic( str_replace( ["\n", "\r"], [' ', ' '], $codigo) );
        if ($exibe)
        {
            $script->exibe();
        }
        return $script;
    }
    
    /**
     * Importa script
     * 
     * Pelo visto 'getScript' é um método JQuery. Preciso alterar isso para usar JavaScript puro.
     * 
     * @param $script nome do arquivo de Script
     */
    public static function importaDoArquivo( $script )
    {
        Script::cria('$.getScript("'.$script.'");');
    }
}
