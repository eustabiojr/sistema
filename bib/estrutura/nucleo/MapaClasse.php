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
        $caminhoClasse['Elemento']                    = 'bib/estrutura/bugigangas/base/Elemento.php';
        $caminhoClasse['Estilo']                      = 'bib/estrutura/bugigangas/base/Estilo.php';
        $caminhoClasse['Script']                      = 'bib/estrutura/bugigangas/base/Script.php';
        $caminhoClasse['Alerta']                      = 'bib/estrutura/bugigangas/dialogo/Alerta.php';
        $caminhoClasse['Mensagem']                    = 'bib/estrutura/bugigangas/dialogo/Mensagem.php';
        $caminhoClasse['Pergunta']                    = 'bib/estrutura/bugigangas/dialogo/Pergunta.php';
        $caminhoClasse['BotaoBuscaBD']                = 'bib/estrutura/bugigangas/embalagem/BotaoBuscaBD.php';
        $caminhoClasse['ComboBD']                     = 'bib/estrutura/bugigangas/embalagem/ComboBD.php';
        $caminhoClasse['EntradaBD']                   = 'bib/estrutura/bugigangas/embalagem/EntradaBD.php';
        $caminhoClasse['FormCadernoRapido']           = 'bib/estrutura/bugigangas/embalagem/FormCadernoRapido.php';
        $caminhoClasse['FormRapido']                  = 'bib/estrutura/bugigangas/embalagem/FormRapido.php';
        $caminhoClasse['GradeRapida']                 = 'bib/estrutura/bugigangas/embalagem/GradeRapida.php';
        $caminhoClasse['GrupoRadioBD']                = 'bib/estrutura/bugigangas/embalagem/GrupoRadioBD.php';
        $caminhoClasse['GrupoVerificacaoBD']          = 'bib/estrutura/bugigangas/embalagem/GrupoVerificacaoBD.php';
        $caminhoClasse['ListaClassificacaoBD']        = 'bib/estrutura/bugigangas/embalagem/ListaClassificacaoBD.php';
        $caminhoClasse['ListaVerificacaoBD']          = 'bib/estrutura/bugigangas/embalagem/ListaVerificacaoBD.php';
        $caminhoClasse['MultiBuscaBD']                = 'bib/estrutura/bugigangas/embalagem/MultiBuscaBD.php';
        $caminhoClasse['SelecionaBD']                 = 'bib/estrutura/bugigangas/embalagem/SelecionaBD.php';
        $caminhoClasse['Arquivo']                     = 'bib/estrutura/bugigangas/form/Arquivo.php';
        $caminhoClasse['Botao']                       = 'bib/estrutura/bugigangas/form/Botao.php';
        $caminhoClasse['BotaoBusca']                  = 'bib/estrutura/bugigangas/form/BotaoBusca.php';
        $caminhoClasse['BotaoRadio']                  = 'bib/estrutura/bugigangas/form/BotaoRadio.php';
        $caminhoClasse['BotaoVerifica']               = 'bib/estrutura/bugigangas/form/BotaoVerifica.php';
        $caminhoClasse['BuscaUnica']                  = 'bib/estrutura/bugigangas/form/BuscaUnica.php';
        $caminhoClasse['Campo']                       = 'bib/estrutura/bugigangas/form/Campo.php';
        $caminhoClasse['CampoLista']                  = 'bib/estrutura/bugigangas/form/CampoLista.php';
        $caminhoClasse['Combo']                       = 'bib/estrutura/bugigangas/form/Combo.php';
        $caminhoClasse['Cor']                         = 'bib/estrutura/bugigangas/form/Cor.php';
        $caminhoClasse['Data']                        = 'bib/estrutura/bugigangas/form/Data.php';
        $caminhoClasse['DataTempo']                   = 'bib/estrutura/bugigangas/form/DataTempo.php';
        $caminhoClasse['Deslizante']                  = 'bib/estrutura/bugigangas/form/Deslizante.php';
        $caminhoClasse['EditorHtml']                  = 'bib/estrutura/bugigangas/form/EditorHtml.php';
        $caminhoClasse['Entrada']                     = 'bib/estrutura/bugigangas/form/Entrada.php';
        $caminhoClasse['Form']                        = 'bib/estrutura/bugigangas/form/Form.php';
        $caminhoClasse['GrupoRadio']                  = 'bib/estrutura/bugigangas/form/GrupoRadio.php';
        $caminhoClasse['GrupoVerifica']               = 'bib/estrutura/bugigangas/form/GrupoVerifica.php';
        $caminhoClasse['Icone']                       = 'bib/estrutura/bugigangas/form/Icone.php';
        $caminhoClasse['InterfaceBugiganga']          = 'bib/estrutura/bugigangas/form/InterfaceBugiganga.php';
        $caminhoClasse['InterfaceElementoForm']       = 'bib/estrutura/bugigangas/form/InterfaceElementoForm.php';
        $caminhoClasse['ListaClassificacao']          = 'bib/estrutura/bugigangas/form/ListaClassificacao.php';
        $caminhoClasse['ListaClassificacao']          = 'bib/estrutura/bugigangas/form/ListaClassificacao.php';
        $caminhoClasse['MultiArquivo']                = 'bib/estrutura/bugigangas/form/MultiArquivo.php';
        $caminhoClasse['MultiBusca']                  = 'bib/estrutura/bugigangas/form/MultiBusca.php';
        $caminhoClasse['MultiEntrada']                = 'bib/estrutura/bugigangas/form/MultiEntrada.php';
        $caminhoClasse['Numerico']                    = 'bib/estrutura/bugigangas/form/Numerico.php';
        $caminhoClasse['Oculto']                      = 'bib/estrutura/bugigangas/form/Oculto.php';
        $caminhoClasse['Rotulo']                      = 'bib/estrutura/bugigangas/form/Rotulo.php';
        $caminhoClasse['Seleciona']                   = 'bib/estrutura/bugigangas/form/Seleciona.php';
        $caminhoClasse['Senha']                       = 'bib/estrutura/bugigangas/form/Senha.php';
        $caminhoClasse['SeparadorForm']               = 'bib/estrutura/bugigangas/form/SeparadorForm.php';
        $caminhoClasse['Submete']                     = 'bib/estrutura/bugigangas/form/Submete.php';
        $caminhoClasse['Tempo']                       = 'bib/estrutura/bugigangas/form/Tempo.php';
        $caminhoClasse['Texto']                       = 'bib/estrutura/bugigangas/form/Texto.php';
        $caminhoClasse['ColunaGradedados']            = 'bib/estrutura/bugigangas/gradedados/ColunaGradedados.php';
        $caminhoClasse['Gradedados']                  = 'bib/estrutura/bugigangas/gradedados/Gradedados.php';
        $caminhoClasse['GradeDadosAcao']              = 'bib/estrutura/bugigangas/gradedados/GradeDadosAcao.php';
        $caminhoClasse['GradedadosGrupoAcao']         = 'bib/estrutura/bugigangas/gradedados/GradedadosGrupoAcao.php';
        $caminhoClasse['AnalisadorMenu']              = 'bib/estrutura/bugigangas/menu/AnalisadorMenu.php';
        $caminhoClasse['BarraMenu']                   = 'bib/estrutura/bugigangas/menu/BarraMenu.php';
        $caminhoClasse['ItemMenu']                    = 'bib/estrutura/bugigangas/menu/ItemMenu.php';
        $caminhoClasse['Menu']                        = 'bib/estrutura/bugigangas/menu/Menu.php';
        $caminhoClasse['AbasConteudo']                = 'bib/estrutura/bugigangas/recipiente/AbasConteudo.php';
        $caminhoClasse['Caderno']                     = 'bib/estrutura/bugigangas/recipiente/Caderno.php';
        $caminhoClasse['CaixaH']                      = 'bib/estrutura/bugigangas/recipiente/CaixaH.php';
        $caminhoClasse['CaixaV']                      = 'bib/estrutura/bugigangas/recipiente/CaixaV.php';
        $caminhoClasse['Carrossel']                   = 'bib/estrutura/bugigangas/recipiente/Carrossel.php';
        $caminhoClasse['Cartao']                      = 'bib/estrutura/bugigangas/recipiente/Cartao.php';
        #$caminhoClasse['Cartao2']                    = 'bib/estrutura/bugigangas/recipiente/Cartao2.php';
        $caminhoClasse['CelulaTabela']                = 'bib/estrutura/bugigangas/recipiente/CelulaTabela.php';
        $caminhoClasse['ConteudoCartao']              = 'bib/estrutura/bugigangas/recipiente/ConteudoCartao.php';
        $caminhoClasse['DialogoJS']                   = 'bib/estrutura/bugigangas/recipiente/DialogoJS.php';
        $caminhoClasse['Expansor']                    = 'bib/estrutura/bugigangas/recipiente/Expansor.php';
        $caminhoClasse['GrupoCapa']                   = 'bib/estrutura/bugigangas/recipiente/GrupoCapa.php';
        $caminhoClasse['GrupoCartao']                 = 'bib/estrutura/bugigangas/recipiente/GrupoCartao.php';
        $caminhoClasse['GrupoCartao2']                = 'bib/estrutura/bugigangas/recipiente/GrupoCartao2.php';
        $caminhoClasse['LinhaTabela']                 = 'bib/estrutura/bugigangas/recipiente/LinhaTabela.php';
        $caminhoClasse['Moldura']                     = 'bib/estrutura/bugigangas/recipiente/Moldura.php';
        $caminhoClasse['NavItens']                    = 'bib/estrutura/bugigangas/recipiente/NavItens.php';
        $caminhoClasse['NavsAbas']                    = 'bib/estrutura/bugigangas/recipiente/NavsAbas.php';
        $caminhoClasse['Paginacao']                   = 'bib/estrutura/bugigangas/recipiente/Paginacao.php';
        $caminhoClasse['Paginacao2']                  = 'bib/estrutura/bugigangas/recipiente/Paginacao2.php';
        $caminhoClasse['Painel']                      = 'bib/estrutura/bugigangas/recipiente/Painel.php';
        $caminhoClasse['Rolagem']                     = 'bib/estrutura/bugigangas/recipiente/Rolagem.php';
        $caminhoClasse['Tabela']                      = 'bib/estrutura/bugigangas/recipiente/Tabela.php';
        $caminhoClasse['ExibeTexto']                  = 'bib/estrutura/bugigangas/util/ExibeTexto.php';
        $caminhoClasse['Imagem']                      = 'bib/estrutura/bugigangas/util/Imagem.php';
        $caminhoClasse['LinkAcao']                    = 'bib/estrutura/bugigangas/util/LinkAcao.php';
        $caminhoClasse['Suspenso']                    = 'bib/estrutura/bugigangas/util/Suspenso.php';
        $caminhoClasse['VisaoExcecao']                = 'bib/estrutura/bugigangas/util/VisaoExcecao.php';
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
