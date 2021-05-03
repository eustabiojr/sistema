<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\InterfaceAcao;

/**
 * Classe Botao 
 */
class Submete extends Campo implements InterfaceBugiganga
{
    private $acao;
    private $rotulo;
    private $nomeForm;

    /**
     * Método defAcao
     */
    public function defAcao(InterfaceAcao $acao, $rotulo)
    {
        $this->acao   = $acao;
        $this->rotulo = $rotulo;
    }
    /**
     * Método defNomeForm
     */
    public function defNomeForm($nome)
    {
        $this->nomeForm = $nome;
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        $url = $this->acao->serializa();

        # define as propriedades do botão
        $tag = new Elemento('button');
        $tag->type = 'submit';
        $tag->name = $this->nome;
        $tag->adic($this->rotulo);

        # define ação do botão
        $tag->onclick = "document.{$this->nomeForm}.action='{$url}'; "; # . "document.{$this->nomeForm}.submit()"

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                $tag->$propriedade = $valor;
            }
        }
        $tag->exibe();             
    }
}
