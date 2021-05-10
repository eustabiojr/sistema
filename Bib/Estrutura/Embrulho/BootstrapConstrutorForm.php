<?php

namespace Estrutura\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\Campo;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\GrupoCheck;
use Estrutura\Bugigangas\Form\GrupoRadio;
use Estrutura\Bugigangas\Form\InterfaceBugiganga;
use Estrutura\Bugigangas\Form\InterfaceElementoForm;
use Estrutura\Bugigangas\Form\Oculto;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Controle\Acao;
use Estrutura\Sessao\Sessao;
use FontLib\Table\Type\name;


use staClass;
use Exception;
use stdClass;

/**
 * Contrutor de formulário Bootstrap para Framework Ageunet 
 * 
 * @version 0.1
 * @package embalagem
 * @author Eustábio J. Silva Jr.
 * @author Pablo Dall'Oglio
 * @license http://www.adianti.com.br/framework-license
 */
class BootstrapConstrutorFormulario implements InterfaceElementoForm
{
    private $id;
    private $decorado;
    private $tabconteudo;
    private $tabcorrente;
    private $pagina_corrente;
    private $propriedades;
    private $acoes;
    private $acoes_cabecalho;
    private $titulo;
    private $classes_coluna;
    private $propriedades_cabecalho;
    private $espacamento;
    private $nome;
    private $funcaoTab;
    private $acaoTab;
    private $tamanho_campos;
    private $aria_automatico;
    private $oculto;
    private $painel;
    private $validacao_cliente;
    private $validacao_csrf;

    /**
     * Método construtor
     * 
     * @param $nome - nome formulário
     */
    public function __construct($nome = 'meu_form')
    {
        $this->decorado          = new Form($nome); 
        $this->tabcorrente       = NULL; 
        $this->pagina_corrente   = 0;
        $this->acoes_cabecalho   = array();
        $this->acoes             = array();
        $this->espacamento       = 10;
        $this->nome              = $nome;
        $this->id                = 'bform_' . mt_rand(1000000000, 1999999999);
        $this->tamanho_campos    = null;
        $this->aria_automatico   = false;
        $this->validacao_cliente = false; 
        $this->validacao_csrf    = false;  
        
        $this->classes_coluna     = array();
        $this->classes_coluna[1]  = ['col-sm-12'];
        $this->classes_coluna[2]  = ['col-sm-4 col-lg-2', 'col-sm-8 col-lg-10'];
        $this->classes_coluna[3]  = ['col-sm-4 col-lg-2', 'col-sm-4', 'col-sm-2'];
        $this->classes_coluna[4]  = ['col-sm-4 col-lg-2', 'col-sm-8 col-lg-4', 'col-sm-4 col-lg-2', 'col-sm-8 col-lg-4'];
        $this->classes_coluna[5]  = ['col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2'];
        $this->classes_coluna[6]  = ['col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2'];
        $this->classes_coluna[7]  = ['col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1'];
        $this->classes_coluna[8]  = ['col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1'];
        $this->classes_coluna[9]  = ['col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1'];
        $this->classes_coluna[10] = ['col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1'];
        # Nota: Na oitava posição o array seguinte estava assim: 'col-sm-2''col-sm-1' ??
        $this->classes_coluna[11] = ['col-sm-1', 'col-sm-1', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-1', 'col-sm-1',
            'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1'];
        $this->classes_coluna[12] = ['col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1', 'col-sm-1',
            'col-sm-1', 'col-sm-1'];
    }

    /**
     * Desliga e liga a validação cliente
     */
    public function defValidacaoCliente($bool)
    {
        $this->validacao_cliente = $bool;
    }

    /**
     * Habilitação Proteção CSRF
     */
    public function habilitaProtecaoCSRF()
    {
        $this->validacao_csrf = true;

        Sessao::defValor('ficha_csrf_' . $this->nome . '_antes', Sessao::obtValor('ficha_csrf_' . $this->nome));
        Sessao::defValor('ficha_csrf_' . $this->nome, bin2hex(random_bytes(32)));

        $ficha_csrf = new Oculto('ficha_csrf');
        $this->adicCampos([$ficha_csrf]);
        $ficha_csrf->defValor(Sessao::obtValor('ficha_csrf' . $this->nome));
        $this->decorado->campoSilencioso('ficha_csrf');
    }

    /**
     * Oculto
     */
    public function oculto()
    {
        $this->oculta = true;
    }

    /**
     * Gera rótulos aria automáticos
     */
    public function geraAria()
    {
        $this->aria_automatico = true;
    }

    /**
     * Retorna o ID formulário
     */
    public function obtId()
    {
        return $this->id;
    }

    /**
     * Define o tamanho dos campos
     */
    public function defTamanhoCampos($tamanho)
    {
        $this->tamanho_campos = $tamanho;
    }

    /**
     * Adiciona um título formulário 
     * @param $titulo Título formulário
     */
    public function defTituloForm($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Define espaçamento
     * @param $espacamento
     */
    public function defEspacamento($espacamento)
    {
        $this->espacamento = $espacamento;
    }

    /**
     * Define a página corrente para ser exibida
     * @param $i - Um inteiro representado o página número (começa com 0)
     */
    public function defPaginaCorrente($i)
    {
        $this->pagina_corrente = $i;
    }

    /**
     * Redireciona as chamadas para o objeto decorado
     */
    public function __call($metodo, $parametros)
    {
        return call_user_func_array(array($this->decorado, $metodo), $parametros);
    }

    /**
     * Redireciona as atribuições do objeto decorado
     */
    public function __set($propriedade, $valor)
    {
        return $this->decorado->$propriedade = $valor;
    }

    /**
     * Define uma propriedade de estilo 
     * @param $nome - Nome propriedade
     * @param $valor - Valor propriedade
     */
    public function defPropriedade($nome, $valor)
    {
        $this->propriedades[$nome] = $valor;
    }

    /**
     * Define uma propriedade de estilo do cabeçalho
     * @param $nome - Nome propriedade
     * @param $valor - Valor propriedade
     */
    public function defPropriedadeCabecalho($nome, $valor)
    {
        $this->propriedades_cabecalho[$nome] = $valor;
    }

    /**
     * Define o nome do formulário
     * @param $nome - Nome do formulário
     */
    public function defNome($nome)
    {
        return $this->decorado->defNome($nome);
    }

    /**
     * Obtém o nome do formulário
     */
    public function obtNome()
    {
        return $this->decorado->obtNome();
    }

    /**
     * Adiciona um campo de formulário
     * @param $campo - Campo de formulário
     */
    public function adicCampo(InterfaceBugiganga $campo)
    {
        return $this->decorado->adicCampo($campo);
    }

    /**
     * Apaga um campo de formulário
     * @param $campo - Campo de formulário
     */
    public function apagCampo(InterfaceBugiganga $campo)
    {
        return $this->decorado->apagCampo($campo);
    }

    /**
     * Define campos de formulário
     * @param $campos - Array de Campos de formulário
     */
    public function defCampos($campos)
    {
        return $this->decorado->defCampos($campos);
    }

    /**
     * Obtém campo de formulário
     * @param $nome - nome de campo
     */
    public function obtCampo($nome)
    {
        return $this->decorado->obtCampo($nome);
    }

    /**
     * Retorna os campos do formulário
     */
    public function obtCampos()
    {
        return $this->decorado->obtCampos();
    }

    /**
     * Limpa formulário
     */
    public function limpa($mantemPadroes = FALSE)
    {
        return $this->decorado->limpa($mantemPadroes);
    }

    /**
     * Define dados do formulário
     * @param $objeto - Objeto dados
     */
    public function defDados($objeto)
    {
        return $this->decorado->defDados($objeto);
    }

    /**
     * Obtém dados do formulário
     * @param $classe - Tipo de objeto de dados de retorno
     */
    public function obtDados($classe = 'StdClass') 
    {
        return $this->decorado->obtDados($classe);
    }

    /**
     * Valida dados do formulário
     */
    public function valida()
    {
        if ($this->validacao_csrf) 
        {
            if (!hash_equals($_POST['ficha_csrf'], Sessao::obtValor('ficha_csrf_'. $this->nome.'_antes'))) {
                throw new Exception('Erro de CSRF');
            }
        }

        return $this->decorado->valida();
    }

    /**
     * Anexa uma página do bloco de notas
     * @param $titulo Titulo tab
     */
    public function anexaPagina($titulo)
    {
        $this->tabcorrente = $titulo;
        $this->conteudotab[$titulo] = array();
    }

    /**
     * Define uma função clique tab
     */
    public function defFuncaoTab($funcao)
    {
        $this->funcaoTab = $funcao;
    }

    /**
     * Define a ação para o tab do bloco de notas
     * @param $acao - Ação a ser tomada quando o usuário 
     * clica no tab do Bloco de notas
     */
    public function defAcaoTab(Acao $acao)
    {
        $this->acaoTab = $acao;
    }

    /**
     * Adiciona campos de formulário
     * @param mixed $campos,... Campos de formulário
     */
    public function adicCampos()
    {
        $args = func_get_args();

        $this->validaArgumentosEmLinha($args, 'adicCampos');

        # objeto que representa uma linha
        $linha = new stdClass;
        $linha->{'content'} = $args;
        $linha->{'type'}    = 'campos';

        if ($args) {
            $this->conteudotab[$this->tabcorrente][] = $linha;

            foreach ($args as $slot) {
                foreach ($slot as $conteudo) {
                    if ($conteudo instanceof InterfaceBugiganga) {
                        $this->decorado->adicCampo($conteudo);
                    }

                    if ($conteudo instanceof BootstrapConstrutorFormulario) {
                        if ($conteudo->obtCampos()) {
                            foreach ($conteudo->obtCampmos() as $campo) {
                                $this->decorado->adicCampo($campo);
                            }
                        }
                    }
                }
            }
        }

        # retorno, porque o usuário pode preencher atributos adicionais
        return $linha;
    }

    /**
     * Adiciona um conteúdo ao formulário
     * @param mixed $conteudo,... Conteúdo do formulário
     */
    public function adicConteudo()
    {
        $args = func_get_args();

        $this->validaArgumentosEmLinha($args, 'adicConteudo');

        # objeto que representa uma linha
        $linha = new stdClass;
        $linha->{'content'} = $args; ###
        $linha->{'type'}    = 'content'; ###

        if ($args) {
            $this->conteudotab[$this->tabcorrente][] = $linha;

            foreach ($args as $arg) {
                foreach ($arg as $slot) {
                    if (!empty($slot) && $slot instanceof BootstrapConstrutorFormulario) {
                        if ($slot->obtCampos()) {
                            foreach ($slot->obtCampos() as $campo) {
                                $this->adicCampo($campo);
                            }
                        }
                    }
                }
            }
        }

        # retorno, porque o usuário pode preencher atributos adicionais
        return $linha;
    }

    /**
     * Valida o tipo do argumento
     * @param $args - Argumentos do array
     * @param $metodo - Método gerador
     */
    public function validaArgumentosEmLinha($args, $metodo) {
        if ($args) {
            foreach ($args as $arg) {
                if (!is_array($arg)) {
                    throw new Exception("Método {$metodo} deve receber um parâmetro do tipo Array");
                }
            }
        }
    }

    /**
     * Adiciona uma ação ao formulário
     * 
     * @param $rotulo - Rótulo do botão
     * @param $acao - Ação do botão
     * @param $icone - Ícone do botão
     */
    public function adicAcao($rotulo, Acao $acao, $icone = 'fa:save')
    {
        $info_rotulo = ($rotulo instanceof Rotulo) ? $rotulo->obtValor() : $rotulo;
        $nome        = 'btn_'.strtolower(str_replace(' ', '_', $info_rotulo));
        $botao       = new Botao($nome);
        $this->decorado->adicCampo($botao);

        // define a ação do botão
        $botao->defAcao($acao, $rotulo);
        $botao->defImagem($icone);

        $this->acoes[] = $botao;
        return $botao;
    }

    /**
     * Adiciona um link de ação ao formulário
     * 
     * @param $rotulo - Rótulo do botão
     * @param $acao - Ação do botão
     * @param $icone - Icone do botão
     */
    public function adicLinkAcao($rotulo, Acao $acao, $icone = 'fa:save')
    {
        $info_rotulo = ($rotulo instanceof Rotulo) ? $rotulo->obtValor() : $rotulo;
        $botao = new AcaoLink($info_rotulo, $acao, null, null, null, $icone);
        $botao->{'class'} = 'btn btn-sm btn-default';
        $this->acoes[] = $botao;
        return $botao;
    }

    /**
     * Adiciona uma ação de cabeçalho ao formulário
     * 
     * @param $rotulo - Rótulo do botão
     * @param $acao - Ação do botão
     * @param $icone - Icone do botão
     */
    public function adicAcaoCabecalho($rotulo, Acao $acao, $icone = 'fa:save')
    {
        $info_rotulo = ($rotulo instanceof Rotulo) ? $rotulo->obtValor() : $rotulo;
        $nome        = strtolower(str_replace(' ', '_', $info_rotulo));
        $botao       = new Botao($nome);
        $this->decorado->adicCampo($botao);

        // define a ação do botão
        $botao->defAcao($acao, $rotulo);
        $botao->defImagem($icone);

        $this->acoes_cabecalho[] = $botao;
        return $botao;
    }

    /**
     * Adiciona um widget de cabeçalho ao formulário
     * 
     * @param $widget - Widget
     */
    public function adicWidgetCabecalho($widget)
    {
        $this->acoes_cabecalho[] = $widget;
        return $widget;
    }

    /**
     * Adiciona uma ação de cabeçalho ao formulário
     * 
     * @param $rotulo - Rótulo do botão
     * @param $acao - Ação do botão
     * @param $icone - Icone do botão
     */
    public function adicLinkAcaoCabecalho($rotulo, Acao $acao, $icone = 'fa:save')
    {
        $info_rotulo = ($rotulo instanceof Rotulo) ? $rotulo->obtValor() : $rotulo;
        $botao = new AcaoLink($info_rotulo, $acao, null, null, null, $icone);
        $botao->{'class'} = 'btn btn-sm btn-default';
        $this->acoes_cabecalho[] = $botao;
        return $botao;
    }

            /**
     * Adiciona um botão ao formulário
     * 
     * @param $rotulo - Rótulo do botão
     * @param $acao - Ação do botão
     * @param $icone - Icone do botão
     */
    public function adicBotao($rotulo, $acao, $icone = 'fa:save')
    {
        $info_rotulo = ($rotulo instanceof Rotulo) ? $rotulo->obtValor() : $rotulo;
        $nome        = strtolower(str_replace(' ', '_', $info_rotulo));
        $botao       = new Botao($nome);
        
        if (strstr($icone, '#') !== FALSE) {
            $partes = explode('#', $icone);
            $cor    = $partes[1];
            $botao->{'style'} = "color: #{$cor}";
        }

        // define a ação do botão
        $botao->adicFuncao($acao);
        $botao->defRotulo($rotulo);
        $botao->defImagem($icone);

        $this->acoes[] = $botao;
        return $botao;
    }

    /**
     * Limpa linha de ações
     */
    public function apagAcoes()
    {
        if ($this->acoes) {
            foreach ($this->acoes as $chave => $botao) {
                unset($this->acoes[$chave]);
            }
        }
    }

    /**
     * Retorna um array com botão de ações
     */
    public function obtBotoesAcoes()
    {
        return $this->acoes;
    }

    /**
     * 
     */
    public function defClassesColuna($chave, $classes)
    {
        $this->classes_coluna[$chave] = $classes;
    }

    /**
     * Renderização do formulário
     */
    public function renderiza()
    {
        if ($this->oculto) {
            return;
        }

        $this->decorado->{'class'} = 'form-horizontal';
        $this->decorado->{'type'}  = 'bootstrap';

        $painel = new Elemento('div');
        $painel->{'class'}  = 'card panel';
        $painel->{'style'}  = 'width: 100%';
        $painel->{'widget'} = 'bootstrapconstrutorform';
        $painel->{'form'}   = $this->nome;
        $painel->{'id'}     = $this->id;

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                $painel->$propriedade = $valor;
            }
        }

        if (!empty($this->titulo)) {
            $cabecalho = new Elemento('div');
            $cabecalho->{'class'} = 'card-header panel-heading';
            $cabecalho->adic(Elemento::tag('div', $this->titulo, ['class'=>'panel-title card-title']));

            if ($this->propriedades_cabecalho) {
                foreach ($this->propriedades_cabecalho as $propriedade => $valor) {
                    if (isset($cabecalho->$propriedade)) {
                        $cabecalho->$propriedade .= ' ' . $valor;
                    } else {
                        $cabecalho->$propriedade  = $valor;
                    }
                }
            }

            if ($this->acoes_cabecalho) {
                $acoes_titulo = new Elemento('div');
                $acoes_titulo->{'class'} = 'header-actions';
                $acoes_titulo->{'style'} = 'float:right';
                $cabecalho->adic($acoes_titulo);

                foreach ($this->acoes_cabecalho as $acao_botao) {
                    $acoes_titulo->adic($acao_botao);
                }
            }
            $painel->adic($cabecalho);
        }

        $corpo = new Elemento('div');
        $corpo->{'class'} = 'card-body panel-body';
        $corpo->{'style'} = 'width: 100%';

        $painel->adic($this->decorado);
        $this->decorado->adic($corpo);

        if ($this->tabcorrente !== null) {
            $tabs = new Elemento('ul');
            $tabs->{'class'} = 'nav nav-tabs';
            $tabs->{'role'}  = 'tablist';

            $contador_tab = 0;
            foreach ($this->conteudotab as $tab => $linhas) {
                $classe = ($contador_tab == $this->pagina_corrente) ? 'active' : '';
                
                $tab_li = new Elemento('li');
                $tab_li->{'role'}  = 'presentation';
                $tab_li->{'class'} = $classe . " nav-item";

                $link_tab = new Elemento('li');
                $link_tab->{'href'} = "#tab_{$this->id}_{$contador_tab}";
                $link_tab->{'role'} = 'tab';
                $link_tab->{'data-toggle'} = 'tab';
                $link_tab->{'aria-expanded'} = 'true';
                $link_tab->{'class'} = "nav-link" . $classe;
                if ($this->funcaoTab) {
                    $link_tab->{'onclick'} = $this->funcaoTab;
                    $link_tab->{'data-current_page'} = $contador_tab;
                }

                if ($this->acaoTab) {
                    $this->acaoTab->defParametro('pagina_corrente', $contador_tab);
                    $acao_string = $this->acaoTab->serialize(FALSE);
                    $link_tab->{'onclick'} = "__ageunet_exec_ajax('$acao_string')";
                }

                $tab_li->adic($link_tab);
                $link_tab->adic(Elemento::tag('span', $tab, ['class' => 'tab-name']));

                $tabs->adic($tab_li);
                $contador_tab++;
            }
            $corpo->adic($tabs);
        }

        $conteudo = new Elemento('div');
        $conteudo->{'class'} = 'tab-content';
        $corpo->adic($conteudo);

        $contador_tab = 0;
        foreach ($this->tabconteudo as $tab => $linhas) {
            $paineltab = new Elemento('div');
            $paineltab->{'role'} = 'tabpanel';
            $paineltab->{'class'} = 'tab-pane ' . ( ($contador_tab == $this->pagina_corrente) ? 'active' : '');
            $paineltab->{'style'} = 'padding: 10px; margin-top: -1px;';
            if ($tab) {
                $paineltab->{'style'} .= 'border: 1px solid #DDDDDD';
            }
            $paineltab->{'id'}   = "tab_{$this->id}_{$contador_tab}";

            $conteudo->adic($paineltab);

            if ($linhas) {
                foreach ($linhas as $linha) {
                    $rotulo_aria = null;
                    $id_aria     = null;

                    $slots = $linha->{'content'};
                    $tipo  = $linha->{'type'};

                    $grupo_form = new Elemento('div');
                    $grupo_form->{'class'} = 'form-group tformrow row' . ' ' . (isset($linha->{'class'}) ? $linha->{'class'} : '');
                    $paineltab->adic($grupo_form);
                    $widgets_visual_linha = 0;

                    if (isset($linha->{'style'})) {
                        $grupo_form->{'style'} = $linha->{'style'};
                    }

                    $contador_slot = count($slots);
                    $contador_linha   = 0;

                    foreach ($slots as $slot) {
                        $css_rotulo = ((count($slots) > 1) AND (count($slot) == 1) AND $slot[0] instanceof Rotulo AND empty($linha->esboco)) ? ' col-form-label 
                        control-label' : '';
                        $classe_coluna = (!empty($linha->esboco) ? $linha->esboco[$contador_linha] : $this->classes_coluna[$contador_slot][$contador_linha]);
                        $embalagem_slot = new Elemento('div');
                        $embalagem_slot->{'class'} = $classe_coluna . ' fb-field-container ' . $css_rotulo;
                        $embalagem_slot->{'style'} = 'min-height:26px';
                        $grupo_form->adic($embalagem_slot);

                        // um campo por slot não precisa ser embalado
                        if (count($slot) == 1) {
                            foreach ($slot as $campo) {
                                $embalagem_campo = self::embalaCampo($campo, 'inherit', $this->tamanhos_campo);

                                $embalagem_slot->adic($embalagem_campo);

                                $embalagem_slot->adic($embalagem_campo);

                                if (!$campo instanceof Oculto) {
                                    $widgets_visual_linha++;
                                }

                                if ($campo instanceof Rotulo) {
                                    $rotulo_aria = $campo->obtValor();
                                    $id_aria     = $campo->obtId();
                                }

                                if ($this->aria_automatico && !empty($rotulo_aria) && !$campo instanceof Rotulo && $campo instanceof Campo) {
                                    $campo->{'aria-label'} = $rotulo_aria;
                                    $campo->{'aria-labelledby'} = $id_aria;
                                }

                                if ($campo instanceof Campo && $campo->ehExigido()) {
                                    $campo->{'aria-required'} = 'true';
                                }
                            }
                        } else { // mais campos devem ser embalados
                            $contador_campo = 0;
                            foreach ($slot as $campo) {
                                $embalagem_campo = self::embalaCampo($campo, 'inline-block', $this->tamanhos_campo);

                                if ( ($contador_campo + 1 < count($slot)) and (!$campo instanceof GBDBotaoBusca) ) { // enchimento menor que o ultimo elemento
                                    $embalagem_campo->{'style'} .= ';padding-right: ' . $this->espacamento.'px;';
                                }

                                $embalagem_slot->adic($embalagem_campo);

                                if (!$campo instanceof Oculto) {
                                    $widgets_visual_linha++;
                                }

                                if ($campo instanceof Rotulo) {
                                    $rotulo_aria = $campo->obtValor();
                                    $id_aria     = $campo->obtId();
                                }

                                if ($this->aria_automatico && !empty($rotulo_aria) && !$campo instanceof Rotulo && $campo instanceof Campo) {
                                    $campo->{'aria-label'} = $rotulo_aria;
                                    $campo->{'aria-labelledby'} = $id_aria;
                                }

                                if ($campo instanceof Campo && $campo->ehExigido()) {
                                    $campo->{'aria-required'} = 'true';
                                }

                                $contador_campo++;
                            }
                        }

                        $contador_linha++;
                    }

                    if ($widgets_visual_linha == 0) {
                        $grupo_form->{'style'} = 'dislay:none';
                    }
                }
            }
            $contador_tab++;
        }

        if ($this->acoes) {
            $rodape = new Elemento('div');
            $rodape->{'class'} = 'panel-footer card-footer';
            $rodape->{'style'} = 'width: 100%';
            $this->decorado->adic($rodape);

            foreach ($this->acoes as $botao_acao) {
                $rodape->adic($botao_acao);
            }
        }

        if (!$this->validacao_cliente) {
            $this->decorado->defPropriedade('naovalida', '');
        }

        $this->painel = $painel;
        return $this->painel;
    }

    /**
     * Exibe formulário
     */
    public function exibe()
    {
        if (empty($this->painel)) {
            $this->renderiza();
        }
        $this->painel->exibe();
    }

    /**
     * Cria um embala campo
     */
    public static function embalaCampo($campo, $exibe, $tamanho_campo_padrao = null) 
    {
        $objeto = $campo; // Compatibilidade BC (Backend)
        $tamanho_campo = (is_object($objeto) && method_exists($objeto, 'obtTamanho')) ? $campo->obtTamanho() : null;
        $tem_sublinhado = (!$campo instanceof Rotulo && !$campo instanceof GrupoRadio && !$campo instanceof GrupoCheck && !$campo instanceof Botao && !$campo
            instanceof Oculto && !$campo instanceof GDeslizante);
        $embalagem_campo = new Elemento('div');
        $embalagem_campo->{'class'} = 'fb-inline-field-container ' . ((($campo instanceof Campo) and ($tem_sublinhado)) ? 'form-line' : '');
        $embalagem_campo->{'style'} = "display: {$exibe}; vertical-align:top;" . ($exibe == 'inline-block'? 'float:left' : '');

        if (!empty($tamanho_campo_padrao)) {
            if (is_array($tamanho_campo)) {
                $tamanho_campo[0] = $tamanho_campo_padrao;
            } else {
                $tamanho_campo = $tamanho_campo_padrao;
            }
        }

        if ($campo instanceof Campo) {
            if (is_array($tamanho_campo)) {
                $largura = $tamanho_campo[0];
                $altura  = $tamanho_campo[1];

                if ($largura) {
                    $embalagem_campo->{'style'} .= ( (strpos($largura, '%') !== FALSE) ? ';width: ' . $largura : ';width: ' . $largura.'px');
                }

                if (!$objeto instanceof GEditorHtml) {
                    if ($altura) {
                        $embalagem_campo->{'style'} .= ( (strpos($altura, '%') !== FALSE) ? ';height: ' . $altura : ';height: ' . $altura.'px');
                    }
                }
            } else if ($tamanho_campo && !$objeto instanceof GGrupoRadio AND !$objeto instanceof GGrupoCheca AND (!$objeto instanceof BotaoBusca OR 
                !empty($tamanho_campo_padrao))) {
                $embalagem_campo->{'style'} .= ( (strpos($tamanho_campo, '%') !== FALSE) ? ';width: ' . $tamanho_campo : ';width: ' . $tamanho_campo.'px');
            }

            if (is_callable([$objeto, 'obtElementoPosterior']) && $objeto->obtElementoPosterior()) {
                $embalagem_campo->{'style'} .= ';display:inline-table';
            }
        }

        $embalagem_campo->adic($campo);

        if ($campo instanceof InterfaceBugiganga) {
            $classe_entrada = ($campo instanceof Rotulo) ? '' : 'form-control';
            $classe_entrada = ($campo instanceof Botao) ? 'btn btn-default btn-sm' : $classe_entrada;
            $classe_campo  = $classe_entrada . ' ' . ( isset($campo->{'class'}) ? $campo->{'class'} : '');

            if (trim($classe_campo)) {
                $campo->{'class'} = $classe_campo;
            }
        }

        if (is_object($objeto) && (method_exists($objeto, 'defTamanho'))) {
            if ($objeto instanceof BotaoBusca) {
                $tamanho_extra = $objeto->obtTamanhoExtra();
                if (!$objeto->possuiAuxiliar()) {
                    $objeto->defTamanho("calc(100% - {$tamanho_extra}px)");
                }
            } else if (in_array($objeto->obtPropriedade('widget'), ['tmultisearch', 'tdbmultisearch', 'thtmleditor', 'tmultientry'])) {
                $objeto->defTamanho('100%', $tamanho_campo[1] - 3);
            } else if ( ($tamanho_campo) AND !($objeto instanceof GGrupoRadio OR $objeto instanceof GGrupoCheca)) {
                $objeto->defTamanho('100%', '100%');
            }
        }

        return $embalagem_campo;
    }

    /**
     * 
     */
    public static function exibeCampo($form, $campo, $velocidade = 0) 
    {
        Script::cria("gform_exibe_campo('{$form}', '{$campo}', {$velocidade})");
    }

    /**
     * 
     */
    public static function ocultaCampo($form, $campo, $velocidade = 0) 
    {
        Script::cria("gform_oculta_campo('{$form}', '{$campo}', {$velocidade})");
    }

    /**
     * Converte o objeto em uma string
     */
    public function __toString()
    {
        return $this->obtConteudos();
    }

    /**
     * Retorna o conteúdo do elemento como uma string
     */
    public function obtConteudos()
    {
        ob_start();
        $this->exibe();
        $conteudo = ob_get_contents();
        ob_end_clean();
        return $conteudo;
    }
}