<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Form\Campo;
use Estrutura\Bugigangas\Form\InterfaceElementoForm;
use Estrutura\Controle\Acao;
use Exception;

/**
 * Class Combo
 */
class Combo extends Campo implements InterfaceBugiganga
{
    protected $id;
    protected $itens;
    protected $propriedades;
    protected $nomeForm;
    private $pesquisavel;
    private $mudaAcao;
    protected $opcaoPadrao;
    protected $mudaFuncao;
    protected $eh_booleano;

    /**
     * Método construtor
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id   = 'botaocombo_' . mt_rand(1000000000, 1999999999);
        $this->opcaoPadrao = '';

        # Cria um tag <select>
        $this->tag = new Elemento('select');
        $this->tag->{'class'}  = 'combo'; 
        $this->tag->{'widget'} = 'combo';
        $this->eh_booleano     = FALSE;
    }

    /**
     * Habilita/desabilita modo booleano
     */
    public function defModoBooleano()
    {
        $this->eh_booleano = true;
        $this->adicItens(['1' => 'Sim',
                          '2' => 'Não']);

        # se devValor() foi chamado anteriormente
        if ($this->valor === true) {
            $this->valor = '1';
        } else if ($this->valor === false) {
            $this->valor = '2';
        }
    }

    /**
     * Define o valor do campo
     * @param $valor Uma string contendo o valor do campo
     */
    public function defValor($valor) {
        if ($this->eh_booleano) {
            $this->valor = $valor ? '1' : '2';
        } else {
            parent::defValor($valor);
        }
    }

    /**
     * Retorna o valor do campo
     */
    public function obtValor()
    {
        if ($this->eh_booleano) {
            return $this->valor == '1' ? true : false;
        } else {
            return parent::obtValor();
        }
    }

    /**
     * Método adicItens
     * 
     * Adiciona itens ao combo box
     * @param $itens Um array indexado contendo as opções do combo
     */
    public function adicItens($itens)
    {
        if (is_array($itens)) {
            $this->itens = $itens;
        }
    }

    /**
     * Retorna itens ao combo box
     */
    public function obtItens()
    {
        return $this->itens;
    }

    /**
     * Habilita busca
     */
    public function habilitaBusca()
    {
        unset($this->tag->{'class'});
        $this->pesquisavel = true;
    }

    /**
     * Retorna os dados post
     */
    public function obtDadosPost() : string
    {
        $nome = str_replace(['[',']'], ['',''], $this->nome);

        if (isset($_POST[$nome])) {
            $val = $_POST[$nome];
            if ($val == '') {
                return '';
            } else {
                if (is_string($val) AND strpos($val, '::')) {
                    $temp = explode('::', $val);
                    return trim($temp[0]);
                } else {
                    if ($this->eh_booleano) {
                        return $val == '1' ? true : false;
                    } else {
                        return $val;
                    }
                }
            }
        } else {
            return '';
        }
    }

    /**
     * Define a ação a ser executada quando o usuário muda o combo
     * @param $acao Objeto Acao
     */
    public function defMudaAcao(Acao $acao)
    {
        if ($acao->ehEstatico()) {
            $this->mudaAcao = $acao;
        } else {
            $string_acao = $acao->paraString();
            throw new Exception("A ação {$string_acao} deve ser estática para ser usada em {__METHOD__}");
        }
    }

    /**
     * Define muda função
     */
    public function defMudaFuncao($funcao) 
    {
        $this->mudaFuncao = $funcao;
    }

    /**
     * Define a opção padrão do combo
     * @param $opcao valor da opção
     */
    public function defOpcaoPadrao($opcao)
    {
        $this->opcaoPadrao = $opcao;
    }

    /**
     * Renderiza itens
     */
    public function renderizaItens()
    {
        if ($this->opcaoPadrao !== FALSE) {
            # cria uma tag <option> vazia
            $opcao = new Elemento('option');

            $opcao->adic($this->opcaoPadrao);
            $opcao->{'value'} = '';

            $this->tag->adic($opcao);
        }

        if ($this->itens) {
            foreach ($this->itens as $chave => $item) {
                if (substr($chave, 0, 3) == '>>>') {
                    $opcgrupo = new Elemento('optgroup');
                    $opcgrupo->{'label'} = $item;

                    $this->tag->adic($opcgrupo);
                } else {
                    $opcao = new Elemento('option');
                    $opcao->{'value'} = $chave;
                    $opcao->adic(htmlspecialchars($item));
                }

                if (substr($chave, 0, 3) == '###') {
                    $opcao->{'disabled'} = '1';
                    $opcao->{'class'} = 'disabled';
                }

                if (($chave == $this->valor) AND !(is_null($this->valor)) AND strlen((string) $this->valor) > 0) {
                    $opcao->{'selected'} = 1;
                }

                if (isset($opcgrupo)) {
                    $opcgrupo->adic($opcao);
                } else {
                    $this->tag->adic($opcao);
                }
            }
        }
    }

    /**
     * Exibe o widget
     */
    public function exibe()
    {
        $this->tag->{'name'} = $this->nome;

        if ($this->id AND empty($this->tag->{'id'})) {
            $this->tag->{'id'} = $this->id;
        }

        if (!empty($this->tamanho)) {
            if (strstr($this->tamanho, '%') !== FALSE) {
                $this->defPropriedade('style', "width: {$this->tamanho};", false);
            } else {
                $this->defPropriedade('style', "width: {$this->tamanho}px;", false);
            }
        }

        if (isset($this->mudaAcao)) {
            if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form) {
                throw new Exception("Você deve passar o {__CLASS__} ({$this->nome}) como parâmetro para Form::defCampos()");
            }

            $string_acao = $this->mudaAcao->serialize(FALSE);
            $this->defPropriedade('mudaacao', "__ageunet_post_pesquisa('{$this->nomeForm}', '{$this->string_acao}', '{$this->id}',
                'callback')");
            $this->defPropriedade('onChange', $this->obtPropriedade('mudaacao'));
        }

        if (isset($this->mudaFuncao)) {
            $this->defPropriedade('mudaacao', $this->mudaFuncao, FALSE);
            $this->defPropriedade('onChange', $this->mudaFuncao, FALSE);
        }

        if (!parent::obtEditavel()) {
            $this->tag->{'onclick'}  = "return false;";
            $this->tag->{'style'}    = ';pointer-events: none';
            $this->tag->{'tabindex'} = '-1';
            $this->tag->{'class'}    = 'combo combo_disabled'; # Provalvelmente terei que alterar esta regra
        }

        if ($this->pesquisavel) {
            $this->tag->{'role'} = 'combobusca';
        }

        $this->renderizaItens();
        $this->tag->exibe();

        if ($this->pesquisavel) {
            $selecionar = 'Selecionar';
            Script::cria("combo_habilita_busca('#{$this->id}', '{$selecionar}')");

            if (!parent::obtEditavel()) {
                Script::cria(" multibusca_desabilita_campo( '{$this->nomeForm}', '{$this->nome}')");
            }
        }
    }
}