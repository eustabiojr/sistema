<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Autor: Eustábio J. Silva Jr. 
 * Data: 05/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe ConteudoCartao
 */
class ConteudoCartao
{
    public function __construct()
    {
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
    }
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