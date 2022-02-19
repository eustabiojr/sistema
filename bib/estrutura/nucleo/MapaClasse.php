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
        $caminhoClasse['Autenticador']                = 'bib/estrutura/autenticacao/Autenticador.php';
        $caminhoClasse['ApagaSql']                    = 'bib/estrutura/bancodados/ApagaSql.php';
        $caminhoClasse['AtualizaSql']                 = 'bib/estrutura/bancodados/AtualizaSql.php';
        $caminhoClasse['Conexao']                     = 'bib/estrutura/bancodados/Conexao.php';
        $caminhoClasse['Criterio']                    = 'bib/estrutura/bancodados/Criterio.php';
        $caminhoClasse['DeclaracaoSql']               = 'bib/estrutura/bancodados/DeclaracaoSql.php';
        $caminhoClasse['Expressao']                   = 'bib/estrutura/bancodados/Expressao.php';
        $caminhoClasse['Filtro']                      = 'bib/estrutura/bancodados/Filtro.php';
        $caminhoClasse['Gravacao']                    = 'bib/estrutura/bancodados/Gravacao.php';
        $caminhoClasse['InsereSql']                   = 'bib/estrutura/bancodados/InsereSql.php';
        $caminhoClasse['InterfaceGravacao']           = 'bib/estrutura/bancodados/InterfaceGravacao.php';
        $caminhoClasse['MultiInsereSql']              = 'bib/estrutura/bancodados/MultiInsereSql.php';
        $caminhoClasse['Repositorio']                 = 'bib/estrutura/bancodados/Repositorio.php';
        $caminhoClasse['Transacao']                   = 'bib/estrutura/bancodados/Transacao.php';
        $caminhoClasse['FormPadrao']                  = 'bib/estrutura/base/FormPadrao.php';
        $caminhoClasse['ListaFormPadrao']             = 'bib/estrutura/base/ListaFormPadrao.php';
        $caminhoClasse['ListaPadrao']                 = 'bib/estrutura/base/ListaPadrao.php';
        $caminhoClasse['BuscaPadrao']                 = 'bib/estrutura/base/BuscaPadrao.php';
        $caminhoClasse['Elemento']                    = 'bib/estrutura/bugigangas/Base/Elemento.php';
        $caminhoClasse['Estilo']                      = 'bib/estrutura/bugigangas/Base/Estilo.php';
        $caminhoClasse['Script']                      = 'bib/estrutura/bugigangas/Base/Script.php';
        $caminhoClasse['Alerta']                      = 'bib/estrutura/bugigangas/Dialogo/Alerta.php';
        $caminhoClasse['Mensagem']                    = 'bib/estrutura/bugigangas/Dialogo/Mensagem.php';
        $caminhoClasse['Pergunta']                    = 'bib/estrutura/bugigangas/Dialogo/Pergunta.php';
        $caminhoClasse['BotaoBuscaBD']                = 'bib/estrutura/bugigangas/Embalagem/BotaoBuscaBD.php';
        $caminhoClasse['ComboBD']                     = 'bib/estrutura/bugigangas/Embalagem/ComboBD.php';
        $caminhoClasse['EntradaBD']                   = 'bib/estrutura/bugigangas/Embalagem/EntradaBD.php';
        $caminhoClasse['FormCadernoRapido']           = 'bib/estrutura/bugigangas/Embalagem/FormCadernoRapido.php';
        $caminhoClasse['FormRapido']                  = 'bib/estrutura/bugigangas/Embalagem/FormRapido.php';
        $caminhoClasse['GradeRapida']                 = 'bib/estrutura/bugigangas/Embalagem/GradeRapida.php';
        $caminhoClasse['GrupoRadioBD']                = 'bib/estrutura/bugigangas/Embalagem/GrupoRadioBD.php';
        $caminhoClasse['GrupoVerificacaoBD']          = 'bib/estrutura/bugigangas/Embalagem/GrupoVerificacaoBD.php';
        $caminhoClasse['ListaClassificacaoBD']        = 'bib/estrutura/bugigangas/Embalagem/ListaClassificacaoBD.php';
        $caminhoClasse['ListaVerificacaoBD']          = 'bib/estrutura/bugigangas/Embalagem/ListaVerificacaoBD.php';
        $caminhoClasse['MultiBuscaBD']                = 'bib/estrutura/bugigangas/Embalagem/MultiBuscaBD.php';
        $caminhoClasse['SelecionaBD']                 = 'bib/estrutura/bugigangas/Embalagem/SelecionaBD.php';
        $caminhoClasse['Arquivo']                     = 'bib/estrutura/bugigangas/Form/Arquivo.php';
        $caminhoClasse['Botao']                       = 'bib/estrutura/bugigangas/Form/Botao.php';
        $caminhoClasse['BotaoBusca']                  = 'bib/estrutura/bugigangas/Form/BotaoBusca.php';
        $caminhoClasse['BotaoRadio']                  = 'bib/estrutura/bugigangas/Form/BotaoRadio.php';
        $caminhoClasse['BotaoVerifica']               = 'bib/estrutura/bugigangas/Form/BotaoVerifica.php';
        $caminhoClasse['BuscaUnica']                  = 'bib/estrutura/bugigangas/Form/BuscaUnica.php';
        $caminhoClasse['Campo']                       = 'bib/estrutura/bugigangas/Form/Campo.php';
        $caminhoClasse['CampoLista']                  = 'bib/estrutura/bugigangas/Form/CampoLista.php';
        $caminhoClasse['Combo']                       = 'bib/estrutura/bugigangas/Form/Combo.php';
        $caminhoClasse['Cor']                         = 'bib/estrutura/bugigangas/Form/Cor.php';
        $caminhoClasse['Data']                        = 'bib/estrutura/bugigangas/Form/Data.php';
        $caminhoClasse['DataTempo']                   = 'bib/estrutura/bugigangas/Form/DataTempo.php';
        $caminhoClasse['Deslizante']                  = 'bib/estrutura/bugigangas/Form/Deslizante.php';
        $caminhoClasse['EditorHtml']                  = 'bib/estrutura/bugigangas/Form/EditorHtml.php';
        $caminhoClasse['Entrada']                     = 'bib/estrutura/bugigangas/Form/Entrada.php';
        $caminhoClasse['Form']                        = 'bib/estrutura/bugigangas/Form/Form.php';
        $caminhoClasse['GrupoRadio']                  = 'bib/estrutura/bugigangas/Form/GrupoRadio.php';
        $caminhoClasse['GrupoVerifica']               = 'bib/estrutura/bugigangas/Form/GrupoVerifica.php';
        $caminhoClasse['Icone']                       = 'bib/estrutura/bugigangas/Form/Icone.php';
        $caminhoClasse['InterfaceBugiganga']          = 'bib/estrutura/bugigangas/Form/InterfaceBugiganga.php';
        $caminhoClasse['InterfaceElementoForm']       = 'bib/estrutura/bugigangas/Form/InterfaceElementoForm.php';
        $caminhoClasse['ListaClassificacao']          = 'bib/estrutura/bugigangas/Form/ListaClassificacao.php';
        $caminhoClasse['ListaClassificacao']          = 'bib/estrutura/bugigangas/Form/ListaClassificacao.php';
        $caminhoClasse['MultiArquivo']                = 'bib/estrutura/bugigangas/Form/MultiArquivo.php';
        $caminhoClasse['MultiBusca']                  = 'bib/estrutura/bugigangas/Form/MultiBusca.php';
        $caminhoClasse['MultiEntrada']                = 'bib/estrutura/bugigangas/Form/MultiEntrada.php';
        $caminhoClasse['Numerico']                    = 'bib/estrutura/bugigangas/Form/Numerico.php';
        $caminhoClasse['Oculto']                      = 'bib/estrutura/bugigangas/Form/Oculto.php';
        $caminhoClasse['Rotulo']                      = 'bib/estrutura/bugigangas/Form/Rotulo.php';
        $caminhoClasse['Seleciona']                   = 'bib/estrutura/bugigangas/Form/Seleciona.php';
        $caminhoClasse['Senha']                       = 'bib/estrutura/bugigangas/Form/Senha.php';
        $caminhoClasse['SeparadorForm']               = 'bib/estrutura/bugigangas/Form/SeparadorForm.php';
        $caminhoClasse['Submete']                     = 'bib/estrutura/bugigangas/Form/Submete.php';
        $caminhoClasse['Tempo']                       = 'bib/estrutura/bugigangas/Form/Tempo.php';
        $caminhoClasse['Texto']                       = 'bib/estrutura/bugigangas/Form/Texto.php';
        $caminhoClasse['ColunaGradedados']            = 'bib/estrutura/bugigangas/Gradedados/ColunaGradedados.php';
        $caminhoClasse['Gradedados']                  = 'bib/estrutura/bugigangas/Gradedados/Gradedados.php';
        $caminhoClasse['GradeDadosAcao']              = 'bib/estrutura/bugigangas/Gradedados/GradeDadosAcao.php';
        $caminhoClasse['GradedadosGrupoAcao']         = 'bib/estrutura/bugigangas/Gradedados/GradedadosGrupoAcao.php';
        $caminhoClasse['AnalisadorMenu']              = 'bib/estrutura/bugigangas/Menu/AnalisadorMenu.php';
        $caminhoClasse['BarraMenu']                   = 'bib/estrutura/bugigangas/Menu/BarraMenu.php';
        $caminhoClasse['ItemMenu']                    = 'bib/estrutura/bugigangas/Menu/ItemMenu.php';
        $caminhoClasse['Menu']                        = 'bib/estrutura/bugigangas/Menu/Menu.php';
        $caminhoClasse['AbasConteudo']                = 'bib/estrutura/bugigangas/Recipiente/AbasConteudo.php';
        $caminhoClasse['Caderno']                     = 'bib/estrutura/bugigangas/Recipiente/Caderno.php';
        $caminhoClasse['CaixaH']                      = 'bib/estrutura/bugigangas/Recipiente/CaixaH.php';
        $caminhoClasse['CaixaV']                      = 'bib/estrutura/bugigangas/Recipiente/CaixaV.php';
        $caminhoClasse['Carrossel']                   = 'bib/estrutura/bugigangas/Recipiente/Carrossel.php';
        $caminhoClasse['Cartao']                      = 'bib/estrutura/bugigangas/Recipiente/Cartao.php';
        #$caminhoClasse['Cartao2']                    = 'bib/estrutura/bugigangas/Recipiente/Cartao2.php';
        $caminhoClasse['CelulaTabela']                = 'bib/estrutura/bugigangas/Recipiente/CelulaTabela.php';
        $caminhoClasse['ConteudoCartao']              = 'bib/estrutura/bugigangas/Recipiente/ConteudoCartao.php';
        $caminhoClasse['DialogoJS']                   = 'bib/estrutura/bugigangas/Recipiente/DialogoJS.php';
        $caminhoClasse['Expansor']                    = 'bib/estrutura/bugigangas/Recipiente/Expansor.php';
        $caminhoClasse['GrupoCapa']                   = 'bib/estrutura/bugigangas/Recipiente/GrupoCapa.php';
        $caminhoClasse['GrupoCartao']                 = 'bib/estrutura/bugigangas/Recipiente/GrupoCartao.php';
        $caminhoClasse['GrupoCartao2']                = 'bib/estrutura/bugigangas/Recipiente/GrupoCartao2.php';
        $caminhoClasse['LinhaTabela']                 = 'bib/estrutura/bugigangas/Recipiente/LinhaTabela.php';
        $caminhoClasse['Moldura']                     = 'bib/estrutura/bugigangas/Recipiente/Moldura.php';
        $caminhoClasse['NavItens']                    = 'bib/estrutura/bugigangas/Recipiente/NavItens.php';
        $caminhoClasse['NavsAbas']                    = 'bib/estrutura/bugigangas/Recipiente/NavsAbas.php';
        $caminhoClasse['Paginacao']                   = 'bib/estrutura/bugigangas/Recipiente/Paginacao.php';
        $caminhoClasse['Paginacao2']                  = 'bib/estrutura/bugigangas/Recipiente/Paginacao2.php';
        $caminhoClasse['Painel']                      = 'bib/estrutura/bugigangas/Recipiente/Painel.php';
        $caminhoClasse['Rolagem']                     = 'bib/estrutura/bugigangas/Recipiente/Rolagem.php';
        $caminhoClasse['Tabela']                      = 'bib/estrutura/bugigangas/Recipiente/Tabela.php';
        $caminhoClasse['ExibeTexto']                  = 'bib/estrutura/bugigangas/Util/ExibeTexto.php';
        $caminhoClasse['Imagem']                      = 'bib/estrutura/bugigangas/Util/Imagem.php';
        $caminhoClasse['LinkAcao']                    = 'bib/estrutura/bugigangas/Util/LinkAcao.php';
        $caminhoClasse['Suspenso']                    = 'bib/estrutura/bugigangas/Util/Suspenso.php';
        $caminhoClasse['VisaoExcecao']                = 'bib/estrutura/bugigangas/Util/VisaoExcecao.php';
        $caminhoClasse['Acao']                        = 'bib/estrutura/controle/Acao.php';
        $caminhoClasse['InterfaceAcao']               = 'bib/estrutura/controle/InterfaceAcao.php';
        $caminhoClasse['Janela']                      = 'bib/estrutura/controle/Janela.php';
        $caminhoClasse['Pagina']                      = 'bib/estrutura/controle/Pagina.php';
        $caminhoClasse['AgeunetPDF']                  = 'bib/estrutura/embrulho/AgeunetPDF.php';
        $caminhoClasse['BootstrapConstrutorForm']     = 'bib/estrutura/embrulho/BootstrapConstrutorForm.php';
        $caminhoClasse['EmbrulhoBootstrapCarderno']   = 'bib/estrutura/embrulho/EmbrulhoBootstrapCarderno.php';
        $caminhoClasse['EmbrulhoBootstrapForm']       = 'bib/estrutura/embrulho/EmbrulhoBootstrapForm.php';
        $caminhoClasse['EmbrulhoBootstrapGradedados'] = 'bib/estrutura/embrulho/EmbrulhoBootstrapGradedados.php';
        $caminhoClasse['EmbrulhoForm']                = 'bib/estrutura/embrulho/EmbrulhoForm.php';
        $caminhoClasse['EmbrulhoForms']               = 'bib/estrutura/embrulho/EmbrulhoForms.php';
        $caminhoClasse['EmbrulhoGradedados']          = 'bib/estrutura/embrulho/EmbrulhoGradedados.php';
        $caminhoClasse['EmbrulhoGrupoForm']           = 'bib/estrutura/embrulho/EmbrulhoGrupoForm.php';
        $caminhoClasse['EmbrulhoItem']                = 'bib/estrutura/embrulho/EmbrulhoItem.php';
        $caminhoClasse['Historico']                   = 'bib/estrutura/historico/Historico.php';
        $caminhoClasse['HistoricoHTML']               = 'bib/estrutura/historico/HistoricoHTML.php';
        $caminhoClasse['HistoricoTXT']                = 'bib/estrutura/historico/HistoricoTXT.php';
        $caminhoClasse['HistoricoXML']                = 'bib/estrutura/historico/HistoricoXML.php';
        $caminhoClasse['ClienteHttp']                 = 'bib/estrutura/Http/ClienteHttp.php';
        $caminhoClasse['AnalisadorTemplate']          = 'bib/estrutura/nucleo/AnalisadorTemplate.php';
        #$caminhoClasse['AutoCarregadorAplic']         = 'bib/estrutura/nucleo/AutoCarregadorAplic.php';
        #$caminhoClasse['AutoCarregadorEstrutura']     = 'bib/estrutura/nucleo/AutoCarregadorEstrutura.php';
        $caminhoClasse['CarregadorAplicativo']        = 'bib/estrutura/nucleo/CarregadorAplicativo.php';
        $caminhoClasse['CarregadorNucleo']            = 'bib/estrutura/nucleo/CarregadorNucleo.php';
        $caminhoClasse['ConfigAplicativo']            = 'bib/estrutura/nucleo/ConfigAplicativo.php';
        $caminhoClasse['MapaClasse']                  = 'bib/estrutura/nucleo/MapaClasse.php';
        $caminhoClasse['NucleoAplicativo']            = 'bib/estrutura/nucleo/NucleoAplicativo.php';
        $caminhoClasse['CPACache']                    = 'bib/estrutura/registro/CPACache.php';
        $caminhoClasse['InterfaceRegistro']           = 'bib/estrutura/registro/InterfaceRegistro.php';
        $caminhoClasse['Sessao']                      = 'bib/estrutura/registro/Sessao.php';
        $caminhoClasse['AgeunetTratadorTemplate']     = 'bib/estrutura/utilidades/AgeunetTratadorTemplate.php';
        $caminhoClasse['ConversaoString']             = 'bib/estrutura/utilidades/ConversaoString.php';       
        $caminhoClasse['FichaSincronizadora']         = 'bib/estrutura/validacao/FichaSincronizadora.php';
        $caminhoClasse['ValidadorCampo']              = 'bib/estrutura/validacao/ValidadorCampo.php';
        $caminhoClasse['ValidadorCNPJ']               = 'bib/estrutura/validacao/ValidadorCNPJ.php';
        $caminhoClasse['ValidadorComprimentoMax']     = 'bib/estrutura/validacao/ValidadorComprimentoMax.php';
        $caminhoClasse['ValidadorComprimentoMin']     = 'bib/estrutura/validacao/ValidadorComprimentoMin.php';
        $caminhoClasse['ValidadorCPF']                = 'bib/estrutura/validacao/ValidadorCPF.php';
        $caminhoClasse['ValidadorEmail']              = 'bib/estrutura/validacao/ValidadorEmail.php';
        $caminhoClasse['ValidadorNumerico']           = 'bib/estrutura/validacao/ValidadorNumerico.php';
        $caminhoClasse['ValidadorObrigatorio']        = 'bib/estrutura/validacao/ValidadorObrigatorio.php';
        $caminhoClasse['ValidadorValorMax']           = 'bib/estrutura/validacao/ValidadorValorMax.php';
        $caminhoClasse['ValidadorValorMin']           = 'bib/estrutura/validacao/ValidadorValorMin.php';

        return $caminhoClasse;
    }
    
    /**
     * Return classes allowed to be directly executed
     */
    public static function obtClassesPermitidas() 
    {
        return array('ServicoAutocompletar', 'ServicoMultiBusca', 'ServicoSubidaArquivo', 'BuscaPadrao');
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
        #$apelidoClasse['GNucleoTradutor'] = 'NucleoTradutor';
        #$apelidoClasse['GIUConstrutor']   = 'IUConstrutor';
        #$apelidoClasse['GDesenhadorPDF']  = 'DesenhadorPDF';
        return $apelidoClasse;
    }
}
