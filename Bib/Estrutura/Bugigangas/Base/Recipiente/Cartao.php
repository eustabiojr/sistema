<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe Cartao
 */
class Cartao extends Elemento 
{
    private $corpo;
    private $rodape;
    private $imagem_titulo = array();
    private $itens = array();

    /**
     * Método construtor
     */
    public function __construct($titulo_cartao = NULL, $tipo_titulo = NULL, array $imagem = array(), array $links = array())
    {
        parent::__construct('div');
        $this->class = 'card';

        if ($imagem) {
            $imagem_titulo = new Elemento('img');
            $imagem_titulo->class = 'card-img-top';
            $imagem_titulo->src   = $imagem[1];
            $imagem_titulo->alt   = $imagem[0];

            parent::adic($imagem_titulo);
        }

        if ($titulo_cartao) {

            $tipo = $tipo_titulo ? 'div' : 'h5';
            $cabecalho = new Elemento($tipo);
            $cabecalho->class = 'card-header';

            $titulo = new Elemento('h5');
            $titulo->adic($titulo_cartao);

            if (count($links['links']) > 0) {
                $ul = new Elemento('ul');
                $ul->class = 'nav nav-tabs card-header-tabs';

                foreach ($links['links'] as $chave => $valor) {

                    if ($links['marcado'] == $chave) {
                        $li = new Elemento('li');
                        $li->class = 'nav-item';

                        $ancora = new Elemento('a');
                        $ancora->class    = 'nav-link';
                        $ancora->href     = '#';
                        $ancora->tabindex = '-1';
                        $ancora->{'aria-disabled'} = 'true';
                        $ancora->adic($valor);

                        $li->adic($ancora);         
                        $ul->adic($li);

                    } else {
                        $li = new Elemento('li');
                        $li->class = 'nav-item';

                        $ancora = new Elemento('a');
                        $ancora->class    = 'nav-link';
                        $ancora->href     = '#';
                        #$ancora->tabindex = '-1';
                        #$ancora->{'aria-disabled'} = 'true';
                        $ancora->adic($valor);

                        $li->adic($ancora);         
                        $ul->adic($li);
                    }
                }
            }

            $cabecalho->adic($titulo);
            if (isset($ul)) {
                $cabecalho->adic($ul);
            }
            parent::adic($cabecalho);
        }

        $this->corpo = new Elemento('div');
        $this->corpo->class = 'card-body';
        $this->corpo->adic($this->corpo);

        $titulo_cartao = new Elemento('h5');
        $titulo_cartao->class = 'card-title';
        $this->corpo->adic($titulo_cartao);

        $texto_cartao = new Elemento('p');
        $texto_cartao->class = 'card-text';
        $this->corpo->adic($texto_cartao);

        $link_cartao = new Elemento('a');
        $link_cartao->class = 'btn btn-primary';
        $this->corpo->adic($link_cartao);

        $this->rodape = new Elemento('div');
        $this->rodape->{'class'} = 'card-footer';
    }

    public function defImagem($nome, $origem, $classe = 'card-img-top')
    {
        $this->imagem_titulo = array($nome, $origem, $classe);
    }

    public function obtImagem()
    {
        return $this->imagem_titulo;
    }

    public function adic($conteudo)
    {
        $this->corpo->adic($conteudo);
    }

    public function adicRodape($rodape)
    {
        $this->rodape->adic($rodape);
        parent::adic($this->rodape);
    }
}