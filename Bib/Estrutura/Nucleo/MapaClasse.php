<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Util;

/**
 * Class map
 *
 * @version    7.1
 * @package    core
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MapaClasse
{
    public static function obtMapa()
    {
        $caminhoClasse = array();
        $caminhoClasse['TStandardForm']              = 'lib/adianti/base/TStandardForm.php';
        $caminhoClasse['TStandardFormList']          = 'lib/adianti/base/TStandardFormList.php';
        $caminhoClasse['TStandardList']              = 'lib/adianti/base/TStandardList.php';

        
        return $caminhoClasse;
    }
    
    /**
     * Return classes allowed to be directly executed
     */
    public static function obtClassesPermitidas() 
    {
        return array('AdiantiAutocompleteService', 'AdiantiMultiSearchService', 'AdiantiUploaderService', 'TStandardSeek');
    }
    
    /**
     * Return internal classes
     */
    public static function obtClassesInternas() 
    {
        return array_diff( array_keys(self::obtMapa()), self::obtClassesPermitidas() );
    }
    
    /**
     * Aliases for backward compatibility
     */
    public static function obtApelidos()
    {
        $apelidoClasse = array();
        $apelidoClasse['TAdiantiCoreTranslator'] = 'AdiantiCoreTranslator';
        $apelidoClasse['TUIBuilder']             = 'AdiantiUIBuilder';
        $apelidoClasse['TPDFDesigner']           = 'AdiantiPDFDesigner';
        return $apelidoClasse;
    }
}
