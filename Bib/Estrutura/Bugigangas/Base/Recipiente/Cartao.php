<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Autor: Eustábio J. Silva Jr. 
 * Data: 30/03/2021
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
    private $titulo_corpo;
    private $texto_corpo;
    private $link_corpo;
    private $rodape;

    /**
     * Método construtor
     */
    public function __construct($titulo = NULL, $tipo_titulo = NULL, array $imagem = array(), array $links = array())
    {
        parent::__construct('div');

        $this->class = 'card';

        /**
         * Assim, podemos passar outros parâmetros de necessário
         */
        if (is_array($titulo) AND isset($titulo['titulo_cartao'])) {
            $titulo_cartao = $titulo['titulo_cartao'];
            if (isset($titulo['sub_classe'])) {
                $this->class = 'card ' . $titulo['sub_classe'];
            }
        } else {
            $titulo_cartao = $titulo;
        }

        # 
        if (!isset($links['links'])) {
            $links['links'] = array();
        }

        # $imagem é um array com as seguintes informações: a origem da imagem e o valor da propriedade 'alt'
        if ($imagem) {
            $imagem_titulo = new Elemento('img');
            $imagem_titulo->class = 'card-img-top';
            $imagem_titulo->src   = $imagem[1];
            $imagem_titulo->alt   = $imagem[0];

            parent::adic($imagem_titulo);
        }

        if ($titulo_cartao) {

            $tipo = $tipo_titulo ?? 'div';
            $cabecalho = new Elemento($tipo);
            $cabecalho->class = 'card-header';

            # $links é um array de 2 dimensões. A primeira são os links, e a segunda é a marcação
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
                        $ancora->adic($valor);

                        $li->adic($ancora);         
                        $ul->adic($li);
                    }
                } # Fim do foreach
            }

            $cabecalho->adic($titulo_cartao);
            if (isset($ul)) {
                $cabecalho->adic($ul);
            }

            parent::adic($cabecalho);
        }

        # Corpo
        $this->corpo = new Elemento('div');
        $this->corpo->class = 'card-body';
        
        $this->titulo_corpo = new Elemento('h5'); 
        $this->titulo_corpo->class = 'card-title';
        
        $this->texto_corpo = new Elemento('p'); 
        $this->texto_corpo->class = 'card-text';
        
        $this->link_corpo = new Elemento('a'); 
        $this->link_corpo->class = 'btn btn-primary';
        $this->link_corpo->href  = '#';
        
        # Rodapé
        $this->rodape = new Elemento('div');
        $this->rodape->{'class'} = 'card-footer';

    } # Fim do construtor

    /**
     * Método adic
     */
    public function adic($conteudo)
    {
        $this->corpo->adic($conteudo);
        parent::adic($this->corpo);
    }

    /**
     * Método adicTituloCorpo
     */
    public function adicTituloCorpo($conteudo)
    {
        $this->titulo_corpo->adic($conteudo);
        $this->corpo->adic($this->titulo_corpo);
    }

    /**
     * Método adicTextoCorpo
     */
    public function adicTextoCorpo($conteudo)
    {
        $this->texto_corpo->adic($conteudo);
        $this->corpo->adic($this->texto_corpo);
    }

    /**
     * Método adicLinkCorpo
     */
    public function adicLinkCorpo($conteudo)
    {
        $this->link_corpo->adic($conteudo);
        $this->corpo->adic($this->link_corpo);
    }

    /**
     * Método adicRodape
     */
    public function adicRodape($rodape)
    {
        $this->rodape->adic($rodape);
        parent::adic($this->rodape);
    }
}