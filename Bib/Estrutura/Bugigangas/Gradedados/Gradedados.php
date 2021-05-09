<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Bugigangas\Gradedados;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Form\Campo;
use Estrutura\Bugigangas\Form\Oculto;
use Estrutura\Bugigangas\Recipiente\Tabela;
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Bugigangas\Util\Suspenso;
use Estrutura\Controle\Acao;
use Estrutura\Controle\InterfaceAcao;
use Estrutura\Utilidades\AgeunetTratadorTemplate;
use Exception;
use Matematica\Analisador;

/**
 * Classe Gradedados
 */
class Gradedados extends Tabela
{
    #private $itens;
    protected $colunas;
    protected $acoes;
    protected $grupos_acoes;
    protected $contalinha;
    protected $cabecalho_tbl;
    protected $corpo_tbl;
    protected $rodape_tbl;
    protected $altura;
    protected $rolavel;
    protected $criadoModelo;
    protected $navegacaoPagina;
    protected $cliquePadrao;
    protected $colunaGrupo;
    protected $conteudoGrupo;
    protected $mascaraGrupo;
    protected $popSobre;
    protected $popTitulo;
    protected $popLateral;
    protected $popConteudo;
    protected $popCondicao;
    protected $objetos;
    protected $larguraAcao;
    protected $contaGrupo;
    protected $ContaLinhaGrupo;
    protected $valoresColuna;
    protected $ConversaoSaidaHTML;
    protected $buscaAtributos;
    protected $dadosSaida;
    protected $camposOcultos;
    protected $antecedeLinhas;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        parent::__construct();
        $this->criadoModelo   = FALSE;
        $this->cliquePadrao   = TRUE;
        $this->popSobre       = FALSE;
        $this->colunaGrupo    = NULL;
        $this->conteudoGrupo  = NULL;
        $this->mascaraGrupo   = NULL;
        $this->contaGrupo     = 0;
        $this->acoes          = array();
        $this->grupos_acoes   = array();
        $this->larguraAcao    = NULL;
        $this->objetos        = array();
        $this->valoresColuna  = array();
        $this->ConversaoSaidaHTML = TRUE;
        $this->buscaAtributos = [];
        $this->dadosSaida     = [];
        $this->camposOcultos  = FALSE;
        $this->antecedeLinhas = 0;

        $this->contalinha = 0;
        $this->{'class'}  = 'tabela_gradedados';
        $this->{'id'}     = 'gradedados_' . mt_rand(1000000000, 1999999999);       
    }

    /**
     * Configura ID
     */
    public function defId($id)
    {
        $this->{'id'} = $id;
    }

    /**
     * Gera campos oculto
     */
    public function geraCamposOcultos()
    {
        $this->camposOcultos = TRUE;
    }

    /**
     * Desabilita caracteres especiais na saída
     */
    public function desabilitaConversaoHtml()
    {
        $this->ConversaoSaidaHTML = FALSE;
    }

    /**
     * Obtém dados crús de saída processados
     */
    public function obtDadosSaida()
    {
        return $this->dadosSaida;
    }

    /**
     * Habilita popSobre
     * @param $titulo Titulo
     * @param $conteudo Conteúdo
     */
    public function habilitaPopOver($titulo, $conteudo, $poplateral = null, $popcondicao = null) 
    {
        $this->popSobre     = TRUE;
        $this->popTitulo    = $titulo;
        $this->popConteudo  = $conteudo;
        $this->popLateral   = $poplateral;
        $this->popCondicao  = $popcondicao;
    }

    /**
     * Faz grade de dados rolável
     */
    public function tornaRolavel()
    {
        $this->rolavel = TRUE;
        if (isset($this->tcabecalho)) {
            $this->tcabecalho->style = 'display:block';
        }
    }

    /**
     * Retorna se a grade dados é rolável
     */
    public function ehRolavel()
    {
        return $this->rolavel;
    }

    /**
     * Retorna se verdadeiro tem largura personalizada
     */
    private function possuiLargPersonalizada()
    {
        return ((strpos($this->obtPropriedade('style'), 'width') !== false) OR !empty($this->obtPropriedade('width')));
    }

    /**
     * Configura a largura da coluna de ação
     */
    public function defLargAcao($largura)
    {
        $this->larguraAcao = $largura;
    }

    /**
     * Desabilita a ação clique padrão
     */
    public function desabilitaCliquePadrao()
    {
        $this->cliquePadrao = FALSE;
    }

    /**
     * Define a altura
     * @param $altura Um inteiro contendo a altura
     */
    public function defAltura($altura)
    {
        $this->altura = $altura;
    }

    /**
     * Adiciona uma coluna a grade de dados
     * @param $objeto Um objeto ColunaGradedados
     */
    public function adicColuna(ColunaGradedados $objeto, Acao $acao = null)
    {
        if ($this->criadoModelo) {
            throw new Exception("Você deve executar {__METHOD__} antes de modeloCriado");
        } else {
            $this->colunas[] = $objeto;

            if (!empty($acao)) {
                $objeto->defAcao($acao);
            }
        }
        return $objeto;
    }

    /**
     * Retorna um array ColunaGradedados
     */
    public function obtColunas()
    {
        return $this->colunas;
    }

    /**
     * Adiciona um ação a grade de dados
     * @param $objeto AcaoGradedados
     */
    public function adicAcao(GradeDadosAcao $acao, $rotulo = null, $imagem = NULL)
    {
        if (!$acao->campoDefinido()) {
            throw new Exception("Você deve definir o campo para a ação {$acao->paraString()}");
        }

        if ($this->modeloCriado) {
            throw new Exception("Você deve executar {__METHOD__} antes de modeloCriado");
        } else {
            $this->acoes[] = $acao;

            if (!empty($rotulo)) {
                $acao->defRotulo($rotulo);
            }

            if (!empty($imagem)) {
                $acao->defImagem($imagem);
            }
        }
    }

    /**
     * Prepara para imprimir
     */
    public function preparaParaImprimir()
    {
        parent::limpaFilhos(); # ***
        $this->acoes = [];
        $this->antecedeLinhas = 0;

        if ($this->colunas) {
            foreach($this->colunas as $coluna) {
                $coluna->removeAcao();
            }
        }
        $this->criaModelo();
    }

    /**
     * Adiciona uma grupo de ação a grade de dados
     * @param $objeto Um objeto AcaoGrupoGradedados
     */
    public function adicAcaoGrupo(GradedadosGrupoAcao $objeto) {
        if ($this->criadoModelo) {
            throw new Exception("Vcoê deve chamar {__METHOD__} antes de modeloCriado");
        } else {
            $this->acao_grupos[] = $objeto;
        }
    }

    /**
     * Retorna o total de colunas
     */
    public function obtTotalColunas()
    {
        return count($this->colunas) + count($this->acoes) + count($this->acao_grupos);
    }

    /**
     * Configura a coluna de grupo para quebra
     */
    public function defColunaGrupo($coluna, $mascara) {
        $this->colunaGrupo = $coluna;
        $this->mascaraGrupo = $mascara;
    }

    /**
     * Limpa o conteúdo da grade de dados
     */
    public function limpa($preservaCabecalho = TRUE, $linhas = 0) 
    {
        if ($this->antecedeLinhas > 0) {
            $linhas = $this->antecedeLinhas;
        }

        if ($this->criadoModelo) {
            # copia cabeçalhos
            $cabecalho_corrente = $this->filhos[0];
            $corpo_corrente     = $this->filhos[1];

            if ($preservaCabecalho) {
                # Redefine o array linha
                $this->filho = array();
                # Adiciona o cabeçalho novamente
                $this->filhos[] = $cabecalho_corrente;
            } else {
                # Redefine o array linha
                $this->filho = array();
            }

            # Adiciona um corpo vazio
            $this->tcorpo = new Elemento('tbody');
            $this->tcorpo->{'class'} = 'corpo_gradedados';
            if ($this->rolavel) {
                $this->tcorpo->{'style'} = "height: {$this->altura}px; display: block; overflow-y: scroll; overflow-x: hidden;";
            }
            parent::adic($this->tbody);

            if ($linhas) {
                for ($n = 0; $n < $linhas; $n++) {
                    $this->tcorpo->adic($corpo_corrente->obtFilhos()[$n]);
                }
            }

            # Reinicializa o contador de linhas
            $this->contalinha = 0;
            $this->objetos    = array();
            $this->valoresColuna = array();
            $this->conteudoGrupo = NULL;
        }
    }

    /**
     * Cria a estrutura da grade de dados
     */
    public function criaModelo($cria_cabecalho = true) 
    {
        if (!$this->colunas) {
            return;
        }

        if ($cria_cabecalho) {
            $this->tcabecalho = new Elemento('thead');
            $this->tcabecalho->{'class'} = 'cabecalho_gradedados';
            parent::adic($this->tcabecalho);

            $linha = new Elemento('tr');
            if ($this->rolavel) {
                $this->tcabecalho->{'style'} = 'display: block';
                if ($this->possuiLargPersonalizada()) {
                    $linha->{'style'} = 'display: inline-table; width: calc(100% - 20px)';
                }
            }
            $this->tcabecalho->adic($linha);

            $conta_acoes = count($this->acoes) + count($this->grupos_acoes);

            if ($conta_acoes > 0) {
                for ($n = 0; $n < $conta_acoes; $n++) {
                    $celula = new Elemento('th');
                    $linha->adic($celula);
                    $celula->adic('<span style="min-width: calc('.$this->larguraAcao.'); display: block"></span>');
                    $celula->{'class'} = 'acao_gradedados';
                    $celula->{'style'} = 'padding: 0';
                    $celula->{'width'} = $this->larguraAcao;
                }
            }

            # Adiciona algumas células para os dados
            if ($this->colunas) {
                $linha_saida = [];
                # Itera as colunas da grade de dados
                foreach ($this->colunas as $coluna) {
                    # obtem as propriedades da coluna
                    $nome    = $coluna->obtNome();
                    $rotulo  = $coluna->obtRotulo();
                    $alinh   = $coluna->obtAlinhamento();
                    $largura = $coluna->obtLargura();
                    $props   = $coluna->obtPropriedades();

                    $acao_coluna = $coluna->obtAcao();
                    if ($acao_coluna) {
                        $params_acao = $acao_coluna->obtParametros();
                    } else {
                        $params_acao = null;
                    }

                    $linha_saida[] = $coluna->obtRotulo();

                    if (isset($_GET['pedido'])) {
                        if ($_GET['pedido'] == $nome || (isset($params_acao['pedido']) && $params_acao['pedido'] == $_GET['pedido'])) {
                            if (isset($_GET['direcao']) AND $_GET['direcao'] == 'asc') {
                                # Esta pode não está atualizada para Bootstrap 4 e 5 principalmente
                                $rotulo .= '<span class="fa fa-checron-down blue" aria-hidden="true"></span>';
                            } else {
                                $rotulo .= '<span class="fa fa-checron-up blue" aria-hidden="true"></span>';
                            }
                        }
                    }
                    # Adiciona uma célula com rótulo de coluna
                    $linha->adic($celula);
                    $celula->adic($rotulo);

                    $celula->{'class'} = 'col_gradedados';
                    $celula->{'style'} = "text-align: $alinh; user-select: none";

                    if ($props) {
                        foreach ($props as $nome_prop => $valor_prop) {
                            $celula->$nome_prop = $valor_prop;
                        }
                    }

                    if ($largura) {
                        $celula->{'width'} = (strpos($largura, '%') !== false || strpos($largura, 'px') !== false) ? $largura : ($largura + 8).'px';
                    }

                    # Verifica se a coluna tem um ação anexada
                    if ($coluna->obtAcao()) {
                        $acao = $coluna->obtAcao();
                        if (isset($_GET['direcao']) AND $_GET['direcao'] = 'asc' AND isset($_GET['pedido']) AND ($_GET['pedido'] == $nome 
                        || (isset($params_acao['pedido']) && $params_acao['pedido'] == $_GET['pedido']))) {
                            $acao->defParametro('direcao', 'desc');
                        } else {
                            $acao->defParametro('direcao', 'asc');
                        }
                        $url      = $acao->serialize();
                        $celula->{'href'}       = \htmlspecialchars($url);
                        $celula->{'style'}     .= ";cursor: pointer;";
                        $celula->{'generator'}  = 'ageunet';
                    }
                }
                $this->dadosSaida[] = $linha_saida;
            }
        }

        # Adiciona uma linha para a grade de dados
        $this->tcorpo = new Elemento('tbody');
        $this->tcorpo->{'class'} = 'corpo_gradedados';
        if ($this->rolavel) {
            $this->tbody->{'style'} = "height: {$this->altura}px; display: block; overflow-y: scroll; overflow-x: scroll;";
        }
        parent::adic($this->tcorpo);
        $this->criadoModelo = TRUE;
    }

    /**
     * Retorna cabeçalho da tabela (thead)
     */
    public function obtCabecalho()
    {
        return $this->thead;
    }

    /**
     * Retorna o corpo da tabela (tbody)
     */
    public function obtCorpo()
    {
        return $this->tbody;
    }

    /**
     * Antecede linha
     */
    public function antecedeLinha($linha)
    {
        $this->obtCorpo()->adic($linha);
        $this->obtCabecalho()->{'noborder'} = '1';
        $this->antecedeLinhas++;
    }

    /**
     * Insere Conteúdo
     */
    public function insere($posicao, $conteudo) 
    {
        $this->tcorpo->insere($posicao, $conteudo);
    }

    /**
     * Adiciona objetos a grade de dados
     * @param $objetos Um array de objetos
     */
    public function adicItens($objetos) 
    {
        if ($objetos) {
            foreach ($objetos as $objeto) {
                $this->adicItem($objeto);
            }
        }
    }

    /**
     * Adiciona um objeto a grade de dados
     * @param $objeto Um objeto Registro Ativo
     * 
     * Aqui '$objeto' contém os dados. Cada nome de coluna contém o nome de cada coluna
     * do banco de dados (se os dados vierem de uma consulta a banco de dados).
     */
    public function adicItem($objeto)
    { 
        if ($this->criadoModelo) {
            if ($this->colunaGrupo AND (is_numeric($this->conteudoGrupo) OR $this->conteudoGrupo !== $objeto->{$this->colunaGrupo})) {
                $linha = new Elemento('tr');
                $linha->{'class'} = 'grupo_gradedados';
                $linha->{'nivel'} = $this->contaGrupo;
                $this->contaLinhaGrupo = 0;
                if ($this->ehRolavel() AND $this->possuiLargPersonalizada()) { # Verificar o nome deste método
                    $linha->{'style'} = 'display: inline-table; width: 100%';
                }
                $this->tcorpo->adic($linha);
                $celula = new Elemento('td');
                $celula->adic(AgeunetTratadorTemplate::substitui($this->mascaraGrupo, $objeto)); # Verificar este 
                $celula->colspan = count($this->acoes) + count($this->grupos_acoes) + count($this->colunas);
                $linha->adic($celula);
                $this->conteudoGrupo = $objeto->{$this->colunaGrupo};
            }

            # Define a cor de fundo para esta linha
            $nomeclasse = ($this->contalinha % 2) == 0 ? 'gradedados_linha_impar' : 'gradedados_linha_par';

            $linha = new Elemento('tr');
            $this->tcorpo->adic($linha);
            $linha->{'class'} = $nomeclasse;

            if ($this->ehRolavel() AND $this->possuiLargPersonalizada()) {
                $linha->{'style'} = 'display: inline-table; width: 100%';
            }

            if ($this->colunaGrupo) {
                $this->ContaLinhaGrupo++;
                $linha->{'filhode'} = $this->contaGrupo;
                $linha->{'nivel'}   = $this->contaGrupo . '.' . $this->ContaLinhaGrupo;
            }

            if ($this->acoes) {
                # Itera as acoes
                foreach ($this->acoes as $template_acao) {
                    # Valida, duplica, e injeta parametros objeto
                    $acao = $template_acao->prepara($objeto);

                    # Obtem as propriedades da ação
                    $rotulo      = $acao->obtRotulo();
                    $imagem      = $acao->obtImagem();
                    $condicao    = $acao->obtExibeCondicao();

                    if (empty($condicao) OR \call_user_func($condicao, $objeto)) {
                        $url          = $acao->serialize();
                        $primeira_url = $primeira_url ?? $url;

                        # Cria um link
                        $link = new Elemento('a');
                        $link->{'href'}      = \htmlspecialchars($url);
                        $link->{'generator'} = 'ageunet';

                        # Verifica se o link possui um icone ou rótulo
                        if ($imagem) {
                            $tag_imagem = is_object($imagem) ? clone $imagem : new Imagem($imagem);
                            $tag_imagem->{'title'} = $rotulo;

                            if ($acao->obtUsaBotao()) {
                                # Adiciona o rótulo ao link
                                $span = new Elemento('span');
                                $span->{'class'} = $acao->obtClasseBotao() ? $acao->obtClasseBotao() : 'btn btn-default';
                                $span->adic($tag_imagem);
                                $span->adic($rotulo);
                                $link->adic($span);
                            } else {
                                $link->adic($tag_imagem);
                            }
                        } else {
                            # Adiciona o rótulo ao link
                            $span = new Elemento('span');
                            $span->{'class'} = $acao->obtClasseBotao() ? $acao->obtClasseBotao() : 'btn btn-default';
                            $span->adic($rotulo);
                            $link->adic($span);
                        }
                    } else {
                        $link = '';
                    }

                    # Adiciona a célula ao linha
                    $celula = new Elemento('td');
                    $linha->adic($celula);
                    $celula->adic($link);
                    $celula->{'style'} = 'min-width: '. $this->larguraAcao;
                    $celula->{'class'} = 'cel_gradedados acao';
                }
            }
            if ($this->grupos_acoes) {
                foreach ($this->grupos_acoes as $grupo_acao) {
                    $acoes       = $grupo_acao->obtAcoes();
                    $cabecalhos  = $grupo_acao->obtCabecalhos(); # Verificar
                    $separadores = $grupo_acao->obtSeparadores();

                    if ($acoes) {
                        $dropdown = new Suspenso($grupo_acao->obtRotulo(), $grupo_acao->obtIcone());
                        $ultimo_indice = 0;
                        foreach ($acoes as $indice => $template_acao) {
                            $acao = $template_acao->prepara($objeto);

                            # Adiciona cabeçalhos inermediários e separadores
                            for ($n = $ultimo_indice; $n < $indice; $n++) {
                                if (isset($cabecalhos[$n])) {
                                    $dropdown->adicCabecalho($cabecalhos[$n]);
                                }
                                if (isset($separadores[$n])) {
                                    $dropdown->adicSeparador();
                                }
                            }

                            # Obtém as propriedades da ação
                            $rotulo   = $acao->obtRotulo();
                            $imagem   = $acao->obtImagem();
                            $condicao = $acao->obtCondicaoDisplay();

                            if (empty($condicao) OR call_user_func($condicao, $objeto)) {
                                $url          =  $acao->serialize();
                                $primeira_url = $primeira_url ?? $url;
                                $dropdown->adicAcao($rotulo, $acao, $imagem);
                            }
                            $ultimo_indice = $indice;
                        }
                        # Adiciona a célula a linha
                        $celula = new Elemento('td');
                        $linha->adic($celula);
                        $celula->adic($dropdown);
                        $celula->{'class'} = 'celula_gradedados acao';
                    }
                }
            }
            $linha_saida  = [];
            $oculto_usado = [];

            if ($this->colunas) {
                # Itera as colunas da grade de dados
                foreach ($this->colunas as $coluna) {
                    # Obtém as propriedades da coluna
                    $nome       = $coluna->obtNome();
                    $alin       = $coluna->obtAlinhamento();
                    $largura    = $coluna->obtLargura();
                    $funcao     = $coluna->obtTransformador();
                    $props      = $coluna->obtPropriedadesDados();

                    # coluna calculada
                    if (substr($nome, 0, 1) == '=') {
                        $conteudo = AgeunetTratadorTemplate::substitui($nome, $objeto, 'float'); # Verificar
                        $conteudo = \str_replace('+', ' + ', $conteudo);
                        $conteudo = \str_replace('-', ' - ', $conteudo);
                        $conteudo = \str_replace('*', ' * ', $conteudo);
                        $conteudo = \str_replace('/', ' / ', $conteudo);
                        $conteudo = \str_replace('(', ' ( ', $conteudo);
                        $conteudo = \str_replace(')', ' ) ', $conteudo);
                        $analisador = new Analisador();
                        $conteudo   = $analisador->avalia(substr($conteudo, 1));
                        $objeto->$nome = $conteudo;
                    } else {
                        try {
                            @$conteudo = $objeto->$nome; # Dispara eventos especiais

                            if (is_null($conteudo)) {
                                $conteudo = AgeunetTratadorTemplate::substitui($nome, $objeto);

                                if ($conteudo === $nome) {
                                    $conteudo = '';
                                }
                            }
                        } catch(Exception $e) {
                            $conteudo = AgeunetTratadorTemplate::substitui($nome, $objeto);

                            if (empty(trim($conteudo)) OR $conteudo === $nome) {
                                $conteudo = $e->getMessage();
                            }
                        }
                    }

                    if (isset($this->valoresColuna[$nome])) {
                        $this->valoresColuna[$nome][] = $conteudo;
                    } else {
                        $this->valoresColuna[$nome] = $conteudo;
                    }

                    $dados = is_null($conteudo) ? '' : $conteudo;
                    $dados_crus = $dados;

                    if ($this->ConversaoSaidaHTML && \is_scalar($dados)) {
                        $dados = \htmlspecialchars($dados, ENT_QUOTES | ENT_HTML5, 'UTF-8'); # Valor da tag
                    }

                    $celula = new Elemento('td');

                    # Verifica se existe uma função transformador
                    if ($funcao) {
                        $ultima_linha = $this->objetos[$this->contalinha - 1] ?? null;
                        # Aplica funções transformadoras sobre os dados
                        $dados = call_user_func($funcao, $dados_crus, $objeto, $linha, $celula, $ultima_linha);
                    }

                    $linha_saida[] = \is_scalar($dados) ?? '';

                    if ($acaoedita = $coluna->obtAcaoEdita()) {
                        $campo_acaoedita = $acaoedita->obtCampo();
                        $div = new Elemento('div');
                        $div->{'class'}  = 'editandoemlinha';
                        $div->{'style'}  = 'padding-left: 5px; padding-right: 5px';
                        $div->{'acao'}   = $acaoedita->serialize();
                        $div->{'campo'}  = $nome;
                        $div->{'chave'}  = $objeto->{$campo_acaoedita} ?? NULL;
                        $div->{'pchave'} = $campo_acaoedita;
                        $div->adic($dados);

                        $linha->adic($celula); # Potencial de erro
                        $celula->adic($div);
                        $celula->{'class'} = 'celula_gradedados';
                    } else {
                        # Adiciona um a célula a linha
                        $linha->adic($celula);
                        $celula->adic($dados);

                        if ($this->camposOcultos AND !isset($oculto_usado[$nome])) {
                            $oculto = new Oculto($this->id . '_' . $nome.'[]');
                            $oculto->defValor($dados_crus);
                            $celula->adic($oculto);
                            $oculto_usado[$nome] = true;
                        }

                        $celula->{'class'} = 'cel_gradedados';
                        $celula->{'align'} = $alin;

                        if (isset($primeira_url) AND $this->cliquePadrao) {
                            $celula->{'href'}       = $primeira_url;
                            $celula->{'generator'}  = 'ageunet';
                            $celula->{'class'}       = 'cel_gradedados';
                        }
                    }

                    if ($props) {
                        foreach ($props as $nome_prop => $valor_prop) {
                            $celula->$nome_prop = $valor_prop;
                        }
                    }

                    if ($largura) {
                        $celula->{'width'} = (strpos($largura, '%') !== false || strpos($largura, 'px') !== false) ? $largura : ($largura + 8).'px';
                    }
                }

                $this->dadosSaida[] = $linha_saida;
            }
            
            if ($this->popSobre && (empty($this->popCondicao) OR call_user_func($this->popCondicao, $objeto))) {
                $poptitulo   = $this->popTitulo;
                $popconteudo = $this->popConteudo; # ???
                $poptitulo   = AgeunetTratadorTemplate::substitui($poptitulo, $objeto); # ???
                $popconteudo = AgeunetTratadorTemplate::substitui($popconteudo, $objeto, null, true); # ???

                $linha->{'popover'}      = 'true';
                $linha->{'poptitulo'}    = $poptitulo;
                $linha->{'popconteudo'}  = \htmlspecialchars(\str_replace("\n", '', nl2br($popconteudo)));

                if ($this->popLateral) {
                    $linha->{'popside'} = $this->popLateral; # ???
                }
            }

            if (count($this->buscaAtributos) > 0) {
                $linha->{'id'} = 'linha_' . mt_rand(1000000000, 1999999999);

                foreach ($this->buscaAtributos as $busca_atrib) {
                    @$busca_conteudo = $objeto->$busca_atrib; # dispara métodos especiais
                    if (!empty($conteudo)) {
                        $linha_dom_busca_atrib = 'busca_' . \str_replace(['-', '>'], ['_', ''], $busca_atrib);
                        $linha->$linha_dom_busca_atrib = $busca_conteudo;
                    }
                }
            }

            $this->objetos[$this->contalinha] = $objeto;

            # Incrementa o contador de linha
            $this->contalinha++;

            return $linha;
        } else {
            throw new Exception("Você deve executar modeloCriado antes de {__METHOD__}");
        }
    }

    /**
     * Anexa linha da tabel por meio de Javascript
     */
    public static function anexaLinha($id_tabela, $linha)
    {
        $linha64 = base64_encode($$linha->obtConteudos());
        Script::cria(" adic_linha_tabela('{$id_tabela}', 'body', '{$linha64}')");
    }

    /**
     * Substitui linha pelo id
     */
    public static function substituiLinhaPeloId($id_tabela, $id, $linha)
    {
        $linha64 = base64_encode($$linha->obtConteudos());
        Script::cria(" subs_linha_pelo_id_tabela('{$id_tabela}', '{$id}', '{$linha64}')");
    }

    /**
     * Retorna os itens da grade de dados
     */
    public function obtItens()
    {
        return $this->objetos;
    }

    /**
     * Processa total de colunas
     */
    private function processaTotais()
    {
        if (count($this->objetos) == 0) {
            return;
        }

        $possui_total = false;

        $this->trodape = new Elemento('trodape');
        $this->trodape->{'class'} = 'gradedados_rodape';

        if ($this->rolavel) {
            $this->trodape->{'style'} = 'display: block';
        }

        $linha = new Elemento('tr');

        if ($this->ehRolavel() AND $this->possuiLargPersonalizada()) {
            $linha->{'style'} = 'display: inline-table; width: 100%';
        }

        $this->trodape->adic($linha);

        if ($this->acoes) {
            foreach ($this->acoes as $acao) {
                $celula = new Elemento('td');
                $linha->adic($celula);
            }
        }

        if($this->grupo_acoes) {
            foreach ($this->grupo_acoes as $grupo_acao) {
                $celula = new Elemento('td');
                $linha->adic($celula);
            }
        }

        if ($this->colunas) {
            # Itera as colunas da grade de dados
            foreach ($this->colunas as $coluna) {
                $celula = new Elemento('td');
                $linha->adic($celula);

                # Obtém a coluna função total
                $funcaoTotal   = $coluna->obtFuncaoTotal();
                $transformador = $coluna->obtTransformador();
                $nome          = $coluna->obtNome();
                $alinhamento   = $coluna->obtAlinhamento();
                $largura       = $coluna->obtLargura();
                $celula->{'style'} = "text-align: $alinhamento";

                if ($largura) {
                    $celula->{'width'} = (strpos($largura, '%') !== false || strpos($largura, 'px') !== false) ? $largura : ($largura + 8) . 'px';
                }

                if ($funcaoTotal) {
                    $tem_total = true;
                    $conteudo  = $funcaoTotal($this->valoresColuna[$nome], $this->objetos);

                    if ($transformador && $coluna->totalTransformado()) {
                        # Aplica as funções de  transformação sobre os dados
                        $conteudo = call_user_func($transformador, $conteudo, null, null, null, null);
                    }
                    $celula->adic($conteudo);
                } else {
                    $celula->adic('&nbsp;');
                }
            }
        }

        if ($tem_total) {
            parent::adic($this->trodape);
        }
    }

    /**
     * Localiza o índice da linha por meio do atributo do objeto
     * @param $atributo atributo objeto
     * @param $valor Valor do objeto
     */
    public function obtIndiceLinha($atributo, $valor) 
    {
        foreach ($this->objetos as $posicao => $objeto) {
            if ($objeto->$atributo == $valor) {
                return $posicao;
            }
            return NULL;
        }
    }

    /**
     * Retorna a linha por meio da posição
     * @param $posicao Posição da linha
     */
    public function obtLinha($posicao)
    {
        return $this->tcorpo->obt($posicao);
    }

    /**
     * Retorna a largura da grade de dados
     * @return Um inteiro contendo a largura da grade de dados
     */
    public function obtLargura()
    {
        $largura = 0;
        if ($this->acoes) {
            # Itera as ações da grade de dados
            foreach ($this->acoes as $acao) {
                $largura += 22;
            }
        }

        if ($this->colunas) {
            # Itera as colunas da grade de dados
            foreach ($this->colunas as $coluna) {
                if (\is_numeric($coluna->obtLargura())) {
                    $largura += $coluna->obtLargura();
                }
            }
        }
        return $largura;
    }

    /**
     * Atribui um objeto de navegação de página
     * @param $navegacaoPagina objeto
     */
    public function defNavegacaoPagina($navegacaoPagina) {
        $this->navegacaoPagina = $navegacaoPagina;
    }

    /**
     * Habilita busca espoleta (fusível)
     * @param $entrada Campo de entrada para busca
     * @param $atributos Nome do atributo
     */
    public function habilitaBusca(Campo $entrada, $atributos) {
        if (count($this->objetos) > 0) {
            throw new Exception("Você deve executar {habilitaBusca()} antes de {adicItem()}");
        }

        $id_entrada     = $entrada->obtId();
        $id_gradedados  = $this->{'id'};
        $nomes_atrib    = explode(',', $atributos);
        $atributos_dom  = [];

        if ($nomes_atrib) {
            foreach ($nomes_atrib as $nome_atrib) {
                $nome_atrib = trim($nome_atrib);
                $busca_atribs_dom[] = \str_replace(['-', '>'], ['_', ''], "busca_{$nome_atrib}");
            }

            $string_atrib_dom = implode(',', $busca_atribs_dom);
            Script::cria("__ageunet_entrada_busca_espoleta('#{$id_entrada}', '{$string_atrib_dom}, '#{$id_gradedados} tr')");
        }
    }
    
    /**
     * Exibe a grade de dados
     */
    public function exibe()
    {
        $this->processaTotais();

        if (!$this->possuiLargPersonalizada()) {
            $this->{'style'} .= ';width: unset';
        }

        # exibe a grade de dados
        parent::exibe();

        $params = $_REQUEST;
        unset($params['classe']);
        unset($params['metodo']);
        # Para continuar pesquisando por parâmetros (ordem, pagina, primeira_pagina, ...)
        $paramsurl = '&' . \http_build_query($params);

        # tratamento de edição em linha
        Script::cria(" gradedados_editaemlinha( '{$paramsurl}');");
        Script::cria(" gradedados_habilita_grupos();");
    }
}