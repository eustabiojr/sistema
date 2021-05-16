<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Class Abstrata Campo
 */
class BotaoRadio extends Campo implements InterfaceBugiganga
{
    private $verificado;


    public function __construct($nome) 
    {
        parent::__construct($nome);
        $this->id = 'botaoradio_' . mt_rand(1000000000, 1999999999);
        $this->tag->{'class'} = '';
    }
    /**
     * Método exibe
     */
    public function exibe()
    {
        // define as propriedades da tag
        $this->tag->{'name'}  = $this->nome;
        $this->tag->{'value'} = $this->valor;
        $this->tag->{'type'}  = 'radio';

        if ($this->id and empty($this->tag->{'id'})) 
        {
            $this->tag->{'id'} = $this->id;
        }

        // verifica se o campo é não é editável
        if (!parent::obtEditavel())
        {
            # marca a bugiganga como somente leitura
            $this->tag->{'onclick'}  = 'return false';
            $this->tag->{'style'}    = 'pointer-events: none';
            $this->tag->{'tabindex'} = '-1';
            
        }
        # exibe a tag
        $this->tag->exibe();
    }
}