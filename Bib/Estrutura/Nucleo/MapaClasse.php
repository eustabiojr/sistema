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
        $caminhoClasse['Elemento']                  = 'Bib/Bugigangas/Base/Elemento.php';
        $caminhoClasse['Estilo']                    = 'Bib/Bugigangas/Base/Estilo.php';
        $caminhoClasse['Script']                    = 'Bib/Bugigangas/Base/Script.php';
        $caminhoClasse['Alerta']                    = 'Bib/Bugigangas/Dialogo/Alerta.php';
        $caminhoClasse['Mensagem']                  = 'Bib/Bugigangas/Dialogo/Mensagem.php';
        $caminhoClasse['Pergunta']                  = 'Bib/Bugigangas/Dialogo/Pergunta.php';
        $caminhoClasse['BotaoBuscaBD']              = 'Bib/Bugigangas/Embalagem/BotaoBuscaBD.php';
        $caminhoClasse['ComboBD']                   = 'Bib/Bugigangas/Embalagem/ComboBD.php';
        $caminhoClasse['EntradaBD']                 = 'Bib/Bugigangas/Embalagem/EntradaBD.php';
        $caminhoClasse['FormCadernoRapido']         = 'Bib/Bugigangas/Embalagem/FormCadernoRapido.php';
        $caminhoClasse['FormRapido']                = 'Bib/Bugigangas/Embalagem/FormRapido.php';
        $caminhoClasse['GradeRapida']               = 'Bib/Bugigangas/Embalagem/GradeRapida.php';
        $caminhoClasse['GrupoRadioBD']              = 'Bib/Bugigangas/Embalagem/GrupoRadioBD.php';
        $caminhoClasse['GrupoVerificacaoBD']        = 'Bib/Bugigangas/Embalagem/GrupoVerificacaoBD.php';
        $caminhoClasse['ListaClassificacaoBD']      = 'Bib/Bugigangas/Embalagem/ListaClassificacaoBD.php';
        $caminhoClasse['ListaVerificacaoBD']        = 'Bib/Bugigangas/Embalagem/ListaVerificacaoBD.php';
        $caminhoClasse['MultiBuscaBD']              = 'Bib/Bugigangas/Embalagem/MultiBuscaBD.php';
        $caminhoClasse['SelecionaBD']               = 'Bib/Bugigangas/Embalagem/SelecionaBD.php';
        $caminhoClasse['Arquivo']                   = 'Bib/Bugigangas/Form/Arquivo.php';
        $caminhoClasse['Botao']                     = 'Bib/Bugigangas/Form/Botao.php';
        $caminhoClasse['BotaoBusca']                = 'Bib/Bugigangas/Form/BotaoBusca.php';
        $caminhoClasse['BotaoRadio']                = 'Bib/Bugigangas/Form/BotaoRadio.php';
        $caminhoClasse['BotaoVerifica']             = 'Bib/Bugigangas/Form/BotaoVerifica.php';
        $caminhoClasse['BuscaUnica']                = 'Bib/Bugigangas/Form/BuscaUnica.php';
        $caminhoClasse['Campo']                     = 'Bib/Bugigangas/Form/Campo.php';
        $caminhoClasse['CampoLista']                = 'Bib/Bugigangas/Form/CampoLista.php';
        $caminhoClasse['Combo']                     = 'Bib/Bugigangas/Form/Combo.php';
        $caminhoClasse['Cor']                       = 'Bib/Bugigangas/Form/Cor.php';
        $caminhoClasse['Data']                      = 'Bib/Bugigangas/Form/Data.php';
        $caminhoClasse['DataTempo']                 = 'Bib/Bugigangas/Form/DataTempo.php';
        $caminhoClasse['Deslizante']                = 'Bib/Bugigangas/Form/Deslizante.php';
        $caminhoClasse['EditorHtml']                = 'Bib/Bugigangas/Form/EditorHtml.php';
        $caminhoClasse['Entrada']                   = 'Bib/Bugigangas/Form/Entrada.php';
        $caminhoClasse['Form']                      = 'Bib/Bugigangas/Form/Form.php';
        $caminhoClasse['GrupoRadio']                = 'Bib/Bugigangas/Form/GrupoRadio.php';
        $caminhoClasse['GrupoVerifica']             = 'Bib/Bugigangas/Form/GrupoVerifica.php';
        $caminhoClasse['Icone']                     = 'Bib/Bugigangas/Form/Icone.php';
        $caminhoClasse['InterfaceBugiganga']        = 'Bib/Bugigangas/Form/InterfaceBugiganga.php';
        $caminhoClasse['InterfaceElementoForm']     = 'Bib/Bugigangas/Form/InterfaceElementoForm.php';
        $caminhoClasse['ListaClassificacao']        = 'Bib/Bugigangas/Form/ListaClassificacao.php';
        $caminhoClasse['ListaClassificacao']        = 'Bib/Bugigangas/Form/ListaClassificacao.php';
        $caminhoClasse['MultiArquivo']              = 'Bib/Bugigangas/Form/MultiArquivo.php';
        $caminhoClasse['MultiBusca']                = 'Bib/Bugigangas/Form/MultiBusca.php';
        $caminhoClasse['MultiEntrada']              = 'Bib/Bugigangas/Form/MultiEntrada.php';
        $caminhoClasse['Numerico']                  = 'Bib/Bugigangas/Form/Numerico.php';
        $caminhoClasse['Oculto']                    = 'Bib/Bugigangas/Form/Oculto.php';
        $caminhoClasse['Rotulo']                    = 'Bib/Bugigangas/Form/Rotulo.php';
        $caminhoClasse['Seleciona']                 = 'Bib/Bugigangas/Form/Seleciona.php';
        $caminhoClasse['Senha']                     = 'Bib/Bugigangas/Form/Senha.php';
        $caminhoClasse['SeparadorForm']             = 'Bib/Bugigangas/Form/SeparadorForm.php';
        $caminhoClasse['Submete']                   = 'Bib/Bugigangas/Form/Submete.php';
        $caminhoClasse['Tempo']                     = 'Bib/Bugigangas/Form/Tempo.php';
        $caminhoClasse['Texto']                     = 'Bib/Bugigangas/Form/Texto.php';

        $caminhoClasse['Historico']                 = 'Bib/Bugigangas/Form/Historico.php';
        $caminhoClasse['Historico']                 = 'Bib/Bugigangas/Form/Historico.php';
        
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
