<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\Acao;
use Estrutura\Controle\InterfaceAcao;

/**
 * Classe Botao 
 */
class Botao extends Campo implements InterfaceBugiganga
{
    private $acao;
    private $rotulo;
    private $nomeForm;

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
     * Método defNomeForm
     */
    public function defNomeForm($nome)
    {
        $this->nomeForm = $nome;
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
    public function obtRotulo() : string
    {
        return $this->rotulo;
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
     * Retorna a ação do botão
     */
    public function obtAcao()
    {
        return $this->acao;
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        $url = $this->acao->serializa();

        # define as propriedades do botão
        $tag = new Elemento('button');
        $tag->name = $this->nome;
        $tag->type = 'button';
        $tag->adic($this->rotulo);

        # define ação do botão
        $tag->onclick = "document.{$this->nomeForm}.action='{$url}'; " . "document.{$this->nomeForm}.submit()";

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                $tag->$propriedade = $valor;
            }
        }
        $tag->exibe();             
    }
}