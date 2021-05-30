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
        $caminhoClasse['Autenticador']                = 'Bib/Estrutura/Autenticacao/Autenticador.php';
        $caminhoClasse['ApagaSql']                    = 'Bib/Estrutura/BancoDados/ApagaSql.php';
        $caminhoClasse['AtualizaSql']                 = 'Bib/Estrutura/BancoDados/AtualizaSql.php';
        $caminhoClasse['Conexao']                     = 'Bib/Estrutura/BancoDados/Conexao.php';
        $caminhoClasse['Criterio']                    = 'Bib/Estrutura/BancoDados/Criterio.php';
        $caminhoClasse['DeclaracaoSql']               = 'Bib/Estrutura/BancoDados/DeclaracaoSql.php';
        $caminhoClasse['Expressao']                   = 'Bib/Estrutura/BancoDados/Expressao.php';
        $caminhoClasse['Filtro']                      = 'Bib/Estrutura/BancoDados/Filtro.php';
        $caminhoClasse['Gravacao']                    = 'Bib/Estrutura/BancoDados/Gravacao.php';
        $caminhoClasse['InsereSql']                   = 'Bib/Estrutura/BancoDados/InsereSql.php';
        $caminhoClasse['InterfaceGravacao']           = 'Bib/Estrutura/BancoDados/InterfaceGravacao.php';
        $caminhoClasse['MultiInsereSql']              = 'Bib/Estrutura/BancoDados/MultiInsereSql.php';
        $caminhoClasse['Repositorio']                 = 'Bib/Estrutura/BancoDados/Repositorio.php';
        $caminhoClasse['Transacao']                   = 'Bib/Estrutura/BancoDados/Transacao.php';
        $caminhoClasse['BuscaPadrao']                 = 'Bib/Estrutura/Base/BuscaPadrao.php';
        $caminhoClasse['ListaPadrao']                 = 'Bib/Estrutura/Base/ListaPadrao.php';
        $caminhoClasse['ListaPadrao']                 = 'Bib/Estrutura/Base/TracoApaga.php';
        $caminhoClasse['TracoColecaoPadrao']          = 'Bib/Estrutura/Base/TracoColecaoPadrao.php';
        $caminhoClasse['ListaPadrao']                 = 'Bib/Estrutura/Base/TracoControlePadrao.php';
        $caminhoClasse['ListaPadrao']                 = 'Bib/Estrutura/Base/TracoEdita.php';
        $caminhoClasse['TracoListaPadrao']            = 'Bib/Estrutura/Base/TracoListaPadrao.php';
        $caminhoClasse['TracoRecarrega']              = 'Bib/Estrutura/Base/TracoRecarrega.php';
        $caminhoClasse['TracoSalva']                  = 'Bib/Estrutura/Base/TracoSalva.php';
        $caminhoClasse['Elemento']                    = 'Bib/Estrutura/Bugigangas/Base/Elemento.php';
        $caminhoClasse['Estilo']                      = 'Bib/Estrutura/Bugigangas/Base/Estilo.php';
        $caminhoClasse['Script']                      = 'Bib/Estrutura/Bugigangas/Base/Script.php';
        $caminhoClasse['Alerta']                      = 'Bib/Estrutura/Bugigangas/Dialogo/Alerta.php';
        $caminhoClasse['Mensagem']                    = 'Bib/Estrutura/Bugigangas/Dialogo/Mensagem.php';
        $caminhoClasse['Pergunta']                    = 'Bib/Estrutura/Bugigangas/Dialogo/Pergunta.php';
        $caminhoClasse['BotaoBuscaBD']                = 'Bib/Estrutura/Bugigangas/Embalagem/BotaoBuscaBD.php';
        $caminhoClasse['ComboBD']                     = 'Bib/Estrutura/Bugigangas/Embalagem/ComboBD.php';
        $caminhoClasse['EntradaBD']                   = 'Bib/Estrutura/Bugigangas/Embalagem/EntradaBD.php';
        $caminhoClasse['FormCadernoRapido']           = 'Bib/Estrutura/Bugigangas/Embalagem/FormCadernoRapido.php';
        $caminhoClasse['FormRapido']                  = 'Bib/Estrutura/Bugigangas/Embalagem/FormRapido.php';
        $caminhoClasse['GradeRapida']                 = 'Bib/Estrutura/Bugigangas/Embalagem/GradeRapida.php';
        $caminhoClasse['GrupoRadioBD']                = 'Bib/Estrutura/Bugigangas/Embalagem/GrupoRadioBD.php';
        $caminhoClasse['GrupoVerificacaoBD']          = 'Bib/Estrutura/Bugigangas/Embalagem/GrupoVerificacaoBD.php';
        $caminhoClasse['ListaClassificacaoBD']        = 'Bib/Estrutura/Bugigangas/Embalagem/ListaClassificacaoBD.php';
        $caminhoClasse['ListaVerificacaoBD']          = 'Bib/Estrutura/Bugigangas/Embalagem/ListaVerificacaoBD.php';
        $caminhoClasse['MultiBuscaBD']                = 'Bib/Estrutura/Bugigangas/Embalagem/MultiBuscaBD.php';
        $caminhoClasse['SelecionaBD']                 = 'Bib/Estrutura/Bugigangas/Embalagem/SelecionaBD.php';
        $caminhoClasse['Arquivo']                     = 'Bib/Estrutura/Bugigangas/Form/Arquivo.php';
        $caminhoClasse['Botao']                       = 'Bib/Estrutura/Bugigangas/Form/Botao.php';
        $caminhoClasse['BotaoBusca']                  = 'Bib/Estrutura/Bugigangas/Form/BotaoBusca.php';
        $caminhoClasse['BotaoRadio']                  = 'Bib/Estrutura/Bugigangas/Form/BotaoRadio.php';
        $caminhoClasse['BotaoVerifica']               = 'Bib/Estrutura/Bugigangas/Form/BotaoVerifica.php';
        $caminhoClasse['BuscaUnica']                  = 'Bib/Estrutura/Bugigangas/Form/BuscaUnica.php';
        $caminhoClasse['Campo']                       = 'Bib/Estrutura/Bugigangas/Form/Campo.php';
        $caminhoClasse['CampoLista']                  = 'Bib/Estrutura/Bugigangas/Form/CampoLista.php';
        $caminhoClasse['Combo']                       = 'Bib/Estrutura/Bugigangas/Form/Combo.php';
        $caminhoClasse['Cor']                         = 'Bib/Estrutura/Bugigangas/Form/Cor.php';
        $caminhoClasse['Data']                        = 'Bib/Estrutura/Bugigangas/Form/Data.php';
        $caminhoClasse['DataTempo']                   = 'Bib/Estrutura/Bugigangas/Form/DataTempo.php';
        $caminhoClasse['Deslizante']                  = 'Bib/Estrutura/Bugigangas/Form/Deslizante.php';
        $caminhoClasse['EditorHtml']                  = 'Bib/Estrutura/Bugigangas/Form/EditorHtml.php';
        $caminhoClasse['Entrada']                     = 'Bib/Estrutura/Bugigangas/Form/Entrada.php';
        $caminhoClasse['Form']                        = 'Bib/Estrutura/Bugigangas/Form/Form.php';
        $caminhoClasse['GrupoRadio']                  = 'Bib/Estrutura/Bugigangas/Form/GrupoRadio.php';
        $caminhoClasse['GrupoVerifica']               = 'Bib/Estrutura/Bugigangas/Form/GrupoVerifica.php';
        $caminhoClasse['Icone']                       = 'Bib/Estrutura/Bugigangas/Form/Icone.php';
        $caminhoClasse['InterfaceBugiganga']          = 'Bib/Estrutura/Bugigangas/Form/InterfaceBugiganga.php';
        $caminhoClasse['InterfaceElementoForm']       = 'Bib/Estrutura/Bugigangas/Form/InterfaceElementoForm.php';
        $caminhoClasse['ListaClassificacao']          = 'Bib/Estrutura/Bugigangas/Form/ListaClassificacao.php';
        $caminhoClasse['ListaClassificacao']          = 'Bib/Estrutura/Bugigangas/Form/ListaClassificacao.php';
        $caminhoClasse['MultiArquivo']                = 'Bib/Estrutura/Bugigangas/Form/MultiArquivo.php';
        $caminhoClasse['MultiBusca']                  = 'Bib/Estrutura/Bugigangas/Form/MultiBusca.php';
        $caminhoClasse['MultiEntrada']                = 'Bib/Estrutura/Bugigangas/Form/MultiEntrada.php';
        $caminhoClasse['Numerico']                    = 'Bib/Estrutura/Bugigangas/Form/Numerico.php';
        $caminhoClasse['Oculto']                      = 'Bib/Estrutura/Bugigangas/Form/Oculto.php';
        $caminhoClasse['Rotulo']                      = 'Bib/Estrutura/Bugigangas/Form/Rotulo.php';
        $caminhoClasse['Seleciona']                   = 'Bib/Estrutura/Bugigangas/Form/Seleciona.php';
        $caminhoClasse['Senha']                       = 'Bib/Estrutura/Bugigangas/Form/Senha.php';
        $caminhoClasse['SeparadorForm']               = 'Bib/Estrutura/Bugigangas/Form/SeparadorForm.php';
        $caminhoClasse['Submete']                     = 'Bib/Estrutura/Bugigangas/Form/Submete.php';
        $caminhoClasse['Tempo']                       = 'Bib/Estrutura/Bugigangas/Form/Tempo.php';
        $caminhoClasse['Texto']                       = 'Bib/Estrutura/Bugigangas/Form/Texto.php';
        $caminhoClasse['ColunaGradedados']            = 'Bib/Estrutura/Bugigangas/Gradedados/ColunaGradedados.php';
        $caminhoClasse['Gradedados']                  = 'Bib/Estrutura/Bugigangas/Gradedados/Gradedados.php';
        $caminhoClasse['GradeDadosAcao']              = 'Bib/Estrutura/Bugigangas/Gradedados/GradeDadosAcao.php';
        $caminhoClasse['GradedadosGrupoAcao']         = 'Bib/Estrutura/Bugigangas/Gradedados/GradedadosGrupoAcao.php';
        $caminhoClasse['AnalisadorMenu']              = 'Bib/Estrutura/Bugigangas/Menu/AnalisadorMenu.php';
        $caminhoClasse['BarraMenu']                   = 'Bib/Estrutura/Bugigangas/Menu/BarraMenu.php';
        $caminhoClasse['ItemMenu']                    = 'Bib/Estrutura/Bugigangas/Menu/ItemMenu.php';
        $caminhoClasse['Menu']                        = 'Bib/Estrutura/Bugigangas/Menu/Menu.php';
        $caminhoClasse['AbasConteudo']                = 'Bib/Estrutura/Bugigangas/Recipiente/AbasConteudo.php';
        $caminhoClasse['Caderno']                     = 'Bib/Estrutura/Bugigangas/Recipiente/Caderno.php';
        $caminhoClasse['CaixaH']                      = 'Bib/Estrutura/Bugigangas/Recipiente/CaixaH.php';
        $caminhoClasse['CaixaV']                      = 'Bib/Estrutura/Bugigangas/Recipiente/CaixaV.php';
        $caminhoClasse['Carrossel']                   = 'Bib/Estrutura/Bugigangas/Recipiente/Carrossel.php';
        $caminhoClasse['Cartao']                      = 'Bib/Estrutura/Bugigangas/Recipiente/Cartao.php';
        #$caminhoClasse['Cartao2']                    = 'Bib/Estrutura/Bugigangas/Recipiente/Cartao2.php';
        $caminhoClasse['CelulaTabela']                = 'Bib/Estrutura/Bugigangas/Recipiente/CelulaTabela.php';
        $caminhoClasse['ConteudoCartao']              = 'Bib/Estrutura/Bugigangas/Recipiente/ConteudoCartao.php';
        $caminhoClasse['DialogoJS']                   = 'Bib/Estrutura/Bugigangas/Recipiente/DialogoJS.php';
        $caminhoClasse['Expansor']                    = 'Bib/Estrutura/Bugigangas/Recipiente/Expansor.php';
        $caminhoClasse['GrupoCapa']                   = 'Bib/Estrutura/Bugigangas/Recipiente/GrupoCapa.php';
        $caminhoClasse['GrupoCartao']                 = 'Bib/Estrutura/Bugigangas/Recipiente/GrupoCartao.php';
        $caminhoClasse['GrupoCartao2']                = 'Bib/Estrutura/Bugigangas/Recipiente/GrupoCartao2.php';
        $caminhoClasse['LinhaTabela']                 = 'Bib/Estrutura/Bugigangas/Recipiente/LinhaTabela.php';
        $caminhoClasse['Moldura']                     = 'Bib/Estrutura/Bugigangas/Recipiente/Moldura.php';
        $caminhoClasse['NavItens']                    = 'Bib/Estrutura/Bugigangas/Recipiente/NavItens.php';
        $caminhoClasse['NavsAbas']                    = 'Bib/Estrutura/Bugigangas/Recipiente/NavsAbas.php';
        $caminhoClasse['Paginacao']                   = 'Bib/Estrutura/Bugigangas/Recipiente/Paginacao.php';
        $caminhoClasse['Paginacao2']                  = 'Bib/Estrutura/Bugigangas/Recipiente/Paginacao2.php';
        $caminhoClasse['Painel']                      = 'Bib/Estrutura/Bugigangas/Recipiente/Painel.php';
        $caminhoClasse['Rolagem']                     = 'Bib/Estrutura/Bugigangas/Recipiente/Rolagem.php';
        $caminhoClasse['Tabela']                      = 'Bib/Estrutura/Bugigangas/Recipiente/Tabela.php';
        $caminhoClasse['ExibeTexto']                  = 'Bib/Estrutura/Bugigangas/Util/ExibeTexto.php';
        $caminhoClasse['Imagem']                      = 'Bib/Estrutura/Bugigangas/Util/Imagem.php';
        $caminhoClasse['LinkAcao']                    = 'Bib/Estrutura/Bugigangas/Util/LinkAcao.php';
        $caminhoClasse['Suspenso']                    = 'Bib/Estrutura/Bugigangas/Util/Suspenso.php';
        $caminhoClasse['VisaoExcecao']                = 'Bib/Estrutura/Bugigangas/Util/VisaoExcecao.php';
        $caminhoClasse['Acao']                        = 'Bib/Estrutura/Controle/Acao.php';
        $caminhoClasse['InterfaceAcao']               = 'Bib/Estrutura/Controle/InterfaceAcao.php';
        $caminhoClasse['Janela']                      = 'Bib/Estrutura/Controle/Janela.php';
        $caminhoClasse['Pagina']                      = 'Bib/Estrutura/Controle/Pagina.php';
        $caminhoClasse['AgeunetPDF']                  = 'Bib/Estrutura/Embrulho/AgeunetPDF.php';
        $caminhoClasse['BootstrapConstrutorForm']     = 'Bib/Estrutura/Embrulho/BootstrapConstrutorForm.php';
        $caminhoClasse['EmbrulhoBootstrapCarderno']   = 'Bib/Estrutura/Embrulho/EmbrulhoBootstrapCarderno.php';
        $caminhoClasse['EmbrulhoBootstrapForm']       = 'Bib/Estrutura/Embrulho/EmbrulhoBootstrapForm.php';
        $caminhoClasse['EmbrulhoBootstrapGradedados'] = 'Bib/Estrutura/Embrulho/EmbrulhoBootstrapGradedados.php';
        $caminhoClasse['EmbrulhoForm']                = 'Bib/Estrutura/Embrulho/EmbrulhoForm.php';
        $caminhoClasse['EmbrulhoForms']               = 'Bib/Estrutura/Embrulho/EmbrulhoForms.php';
        $caminhoClasse['EmbrulhoGradedados']          = 'Bib/Estrutura/Embrulho/EmbrulhoGradedados.php';
        $caminhoClasse['EmbrulhoGrupoForm']           = 'Bib/Estrutura/Embrulho/EmbrulhoGrupoForm.php';
        $caminhoClasse['EmbrulhoItem']                = 'Bib/Estrutura/Embrulho/EmbrulhoItem.php';
        $caminhoClasse['Historico']                   = 'Bib/Estrutura/Historico/Historico.php';
        $caminhoClasse['HistoricoHTML']               = 'Bib/Estrutura/Historico/HistoricoHTML.php';
        $caminhoClasse['HistoricoTXT']                = 'Bib/Estrutura/Historico/HistoricoTXT.php';
        $caminhoClasse['HistoricoXML']                = 'Bib/Estrutura/Historico/HistoricoXML.php';
        $caminhoClasse['ClienteHttp']                 = 'Bib/Estrutura/Http/ClienteHttp.php';
        $caminhoClasse['AnalisadorTemplate']          = 'Bib/Estrutura/Nucleo/AnalisadorTemplate.php';
        #$caminhoClasse['AutoCarregadorAplic']         = 'Bib/Estrutura/Nucleo/AutoCarregadorAplic.php';
        #$caminhoClasse['AutoCarregadorEstrutura']     = 'Bib/Estrutura/Nucleo/AutoCarregadorEstrutura.php';
        $caminhoClasse['CarregadorAplicativo']        = 'Bib/Estrutura/Nucleo/CarregadorAplicativo.php';
        $caminhoClasse['CarregadorNucleo']            = 'Bib/Estrutura/Nucleo/CarregadorNucleo.php';
        $caminhoClasse['ConfigAplicativo']            = 'Bib/Estrutura/Nucleo/ConfigAplicativo.php';
        $caminhoClasse['MapaClasse']                  = 'Bib/Estrutura/Nucleo/MapaClasse.php';
        $caminhoClasse['NucleoAplicativo']            = 'Bib/Estrutura/Nucleo/NucleoAplicativo.php';
        $caminhoClasse['CPACache']                    = 'Bib/Estrutura/Registro/CPACache.php';
        $caminhoClasse['InterfaceRegistro']           = 'Bib/Estrutura/Registro/InterfaceRegistro.php';
        $caminhoClasse['Sessao']                      = 'Bib/Estrutura/Registro/Sessao.php';
        $caminhoClasse['AgeunetTratadorTemplate']     = 'Bib/Estrutura/Utilidades/AgeunetTratadorTemplate.php';
        $caminhoClasse['ConversaoString']             = 'Bib/Estrutura/Utilidades/ConversaoString.php';       
        $caminhoClasse['FichaSincronizadora']         = 'Bib/Estrutura/Validacao/FichaSincronizadora.php';
        $caminhoClasse['ValidadorCampo']              = 'Bib/Estrutura/Validacao/ValidadorCampo.php';
        $caminhoClasse['ValidadorCNPJ']               = 'Bib/Estrutura/Validacao/ValidadorCNPJ.php';
        $caminhoClasse['ValidadorComprimentoMax']     = 'Bib/Estrutura/Validacao/ValidadorComprimentoMax.php';
        $caminhoClasse['ValidadorComprimentoMin']     = 'Bib/Estrutura/Validacao/ValidadorComprimentoMin.php';
        $caminhoClasse['ValidadorCPF']                = 'Bib/Estrutura/Validacao/ValidadorCPF.php';
        $caminhoClasse['ValidadorEmail']              = 'Bib/Estrutura/Validacao/ValidadorEmail.php';
        $caminhoClasse['ValidadorNumerico']           = 'Bib/Estrutura/Validacao/ValidadorNumerico.php';
        $caminhoClasse['ValidadorObrigatorio']        = 'Bib/Estrutura/Validacao/ValidadorObrigatorio.php';
        $caminhoClasse['ValidadorValorMax']           = 'Bib/Estrutura/Validacao/ValidadorValorMax.php';
        $caminhoClasse['ValidadorValorMin']           = 'Bib/Estrutura/Validacao/ValidadorValorMin.php';

        return $caminhoClasse;
    }
    
    /**
     * Return classes allowed to be directly executed
     */
    public static function obtClassesPermitidas() 
    {
        return array('ServicoAutocompletar', 'ServicoMultiBusca', 'ServicoUploader', 'BuscaPadrao');
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
        $apelidoClasse['GNucleoTradutor'] = 'NucleoTradutor';
        $apelidoClasse['GIUConstrutor']   = 'IUConstrutor';
        $apelidoClasse['GDesenhadorPDF']  = 'DesenhadorPDF';
        return $apelidoClasse;
    }
}
