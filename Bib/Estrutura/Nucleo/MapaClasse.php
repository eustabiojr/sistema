<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Nucleo;

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
        $caminhoClasse['Autenticador']              = 'Bib/Estrutura/Autenticacao/Autenticador.php';
        $caminhoClasse['Conexao']                   = 'Bib/Estrutura/BancoDados/Conexao.php';
        $caminhoClasse['Criterio']                  = 'Bib/Estrutura/BancoDados/Criterio.php';
        $caminhoClasse['DeclaracaoSql']             = 'Bib/Estrutura/BancoDados/DeclaracaoSql.php';
        $caminhoClasse['Expressao']                 = 'Bib/Estrutura/BancoDados/Expressao.php';
        $caminhoClasse['Filtro']                    = 'Bib/Estrutura/BancoDados/Filtro.php';
        $caminhoClasse['Gravacao']                  = 'Bib/Estrutura/BancoDados/Gravacao.php';
        $caminhoClasse['InterfaceGravacao']         = 'Bib/Estrutura/BancoDados/InterfaceGravacao.php';
        $caminhoClasse['Repositorio']               = 'Bib/Estrutura/BancoDados/Repositorio.php';
        $caminhoClasse['Transacao']                 = 'Bib/Estrutura/BancoDados/Transacao.php';
        $caminhoClasse['BuscaPadrao']               = 'Bib/Estrutura/Base/BuscaPadrao.php';
        $caminhoClasse['ListaPadrao']               = 'Bib/Estrutura/Base/ListaPadrao.php';
        $caminhoClasse['TracoColecaoPadrao']        = 'Bib/Estrutura/Base/TracoColecaoPadrao.php';
        $caminhoClasse['TracoListaPadrao']          = 'Bib/Estrutura/Base/TracoListaPadrao.php';
        $caminhoClasse['Acao']                      = 'Bib/Estrutura/Controle/Acao.php';
        $caminhoClasse['InterfaceAcao']             = 'Bib/Estrutura/Controle/InterfaceAcao.php';
        $caminhoClasse['Janela']                    = 'Bib/Estrutura/Controle/Janela.php';
        $caminhoClasse['Pagina']                    = 'Bib/Estrutura/Controle/Pagina.php';
        $caminhoClasse['ApagaSql']                  = 'Bib/Estrutura/BancoDados/ApagaSql.php';
        $caminhoClasse['AtualizaSql']               = 'Bib/Estrutura/BancoDados/AtualizaSql.php';
        $caminhoClasse['Conexao']                   = 'Bib/Estrutura/BancoDados/Conexao.php';
        $caminhoClasse['DeclaracaoSql']             = 'Bib/Estrutura/BancoDados/DeclaracaoSql.php';
        $caminhoClasse['Expressao']                 = 'Bib/Estrutura/BancoDados/Expressao.php';
        $caminhoClasse['Filtro']                    = 'Bib/Estrutura/BancoDados/Filtro.php';
        $caminhoClasse['Gravacao']                  = 'Bib/Estrutura/BancoDados/Gravacao.php';
        $caminhoClasse['InsereSql']                 = 'Bib/Estrutura/BancoDados/InsereSql.php';
        $caminhoClasse['InterfaceGravacao']         = 'Bib/Estrutura/BancoDados/InterfaceGravacao.php';
        $caminhoClasse['MultiInsereSql']            = 'Bib/Estrutura/BancoDados/MultiInsereSql.php';
        $caminhoClasse['Repositorio']               = 'Bib/Estrutura/BancoDados/Repositorio.php';
        $caminhoClasse['Transacao']                 = 'Bib/Estrutura/BancoDados/Transacao.php';
        $caminhoClasse['Historico']                 = 'Bib/Estrutura/Historico/Historico.php';
        $caminhoClasse['HistoricoHTML']             = 'Bib/Estrutura/Historico/HistoricoHTML.php';
        $caminhoClasse['HistoricoTXT']              = 'Bib/Estrutura/Historico/HistoricoTXT.php';
        $caminhoClasse['HistoricoXML']              = 'Bib/Estrutura/Historico/HistoricoXML.php';

        $caminhoClasse['Historico']                  = 'Bib/Estrutura/Historico/Historico.php';
        
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
