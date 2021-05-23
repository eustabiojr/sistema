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
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Controle\Acao;
use Exception;

/**
 * Classe Botao 
 */
class Botao extends Campo implements InterfaceBugiganga
{
    private $acao;
    private $imagem;
    private $funcoes;
    private $nomeTag;
    protected $propriedades;
    protected $rotulo;
    protected $nomeForm;

    /**
     *  Cria um botão com ícone e ação
     */
    public static function cria($nome, $callback, $rotulo, $imagem)
    {
        $botao = new Botao($nome);
        $botao->defAcao(new Acao($callback), $rotulo);
        $botao->defImagem($imagem);
        return $botao;
    }

    /**
     * Adiciona uma classe
     */
    public function adicClasseEstilo($classe)
    {
        $classes = ['btn-primary', 'btn-secondary', 'btn-success', 'btn-danger', 'btn-warning', 'btn-info', 'btn-light', 'btn-dark', 'btn-link', 'btn-default'];
        $encontrado = false;

        foreach ($classes as $classeBtn) 
        {
            if (strpos($classe, $classeBtn) !== false)
            {
                $encontrado = true;
            }
        }

        $this->{'class'} = 'btn ' . ($encontrado ? '' : 'btn-default') . $classe;
    }

    /**
     * Define a ação do botão
     * @param $acao Objeto ação
     * @param $rotulo Rotulo do botão
     */
    public function defAcao(Acao $acao, $rotulo = NULL)
    {
        $this->acao   = $acao;
        $this->rotulo = $rotulo;
    }

    /**
     * Retorna a ação do botão
     */
    public function obtAcao()
    {
        return $this->acao;
    }

    /**
     * Define o nome da tag
     */
    public function defNomeTag($nome)
    {
        $this->nomeTag = $nome;
    }

    /**
     * Define o ícone do botão
     * @param $imagem caminho imagem
     */
    public function defImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    /**
     * Define o ícone do botão
     * @param $imagem caminho imagem
     */
    public function defRotulo($rotulo)
    {
        $this->rotulo = $rotulo;
    }

    /**
     * Retorna a ação do botão
     */
    public function obtRotulo()
    {
        return $this->rotulo;
    }

    /**
     * Adiciona uma função Javascript a ser executada pelo botão
     * 
     * @param $funcao Um pedaço de código javascript
     * @ignore-autocomplete ons
     */
    public function adicFuncao($funcao)
    {
        if ($funcao) 
        {
            $this->funcoes = $funcao . ';';
        }
    }
    
    /**
     * Define a propriedade de campo
     * @param $nome Nome da propriedade
     * @param $valor Valor da Propriedade
     */
    public function defPropriedade($nome, $valor, $substitui = TRUE)
    {
        $this->propriedades[$nome] = $valor;
    }

    /**
     * Retorna a ação do botão
     */
    public function obtPropriedade($nome) : string
    {
        return $this->propriedades[$nome] ?? null;
    }

    /**
     * Habilita campo
     * 
     * @param $nome_form Nome do formulário
     * @param $campo Nome do campo
     */
    public function habilitaCampo($nome_form, $campo)
    {
        Script::cria(" botao_habilita_campo('{$nome_form}', '{$campo}'); ");
    }

    /**
     * Desabilita campo
     * 
     * @param $nome_form Nome do formulário
     * @param $campo Nome do campo
     */
    public function desabilitaCampo($nome_form, $campo)
    {
        Script::cria(" botao_desabilita_campo('{$nome_form}', '{$campo}'); ");
    }

    /**
     * Método exibe
     */
    public function exibe() 
    {
        if ($this->acao) {
            if (empty($this->nomeForm)) {
                $rotulo = ($this->rotulo instanceof Rotulo) ? $this->rotulo->obtValor() : $this->rotulo;
                throw new Exception("Você deve passar o {__CLASS__} ({$rotulo}) como parâmetro para Form::defCampos()");
            }

            # Obtém a ação como URL
            $url = $this->acao->serializa(FALSE);
            if ($this->acao->ehEstatico()) {
                $url .= '&static=1';
            }
            $url = htmlspecialchars($url);
            $aguarda_mensagem = 'Carregando';
            # Define a ação do botão (post Ajax)
            $acao  = "Ageunet.aguardaMensagem = '$aguarda_mensagem';";
            $acao .= "{$this->funcoes}";
            $acao .= "__ageunet_dados_post('{$this->nomeForm}', '{$url}');";
            $acao .= "return false;";

            $botao  = new Elemento(!empty($this->nomeTag) ? $this->nomeTag : 'button');
            $botao->{'id'}      = 'botao_' . $this->nome; 
            $botao->{'name'}    = $this->nome; 
            $botao->{'class'}   = 'btn btn-default btn-sm'; 
            $botao->{'onclick'} = $acao; 
            $acao = '';
        }

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                $botao->$propriedade = $valor;
            }
        }

        $span = new Elemento('span');
        if ($this->imagem) {
            $imagem = new Imagem($this->imagem);
            if (!empty($this->rotulo)) {
                $imagem->{'style'} .= ';padding-right: 4px';
            }
        }

        if ($this->rotulo) {
            $span->adic($this->rotulo);
            $botao->{'aria-label'} = $this->rotulo;
        }

        $botao->adic($span);
        $botao->exibe();
    }
}