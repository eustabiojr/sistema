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
class BotaoVerifica extends Campo implements InterfaceBugiganga
{
    private $valorIndice;


    /**
     * Construtor da classe
     * 
     * @param $nome Nome da bugiganga
     */
    public function __construct($nome) 
    {
        parent::__construct($nome);
        $this->id = 'botaoverifica_' . mt_rand(1000000000, 1999999999);
        $this->tag->{'class'} = '';
    }

    /********
     * Define o valor índice para o botão verifica
     * 
     * @index Valor índice
     */
    public function defValorIndice($indice)
    {
        $this->valorIndice = $indice;
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        # atribui as propriedades da tag

        $this->tag->{'name'} = $this->nome;
        $this->tag->{'type'} = 'checkbox';
        $this->tag->{'value'} = $this->valorIndice;

        if ($this->id and empty($this->tag->{'id'}))
        {
            $this->tag->{'id'} = $this->id;
        }

        if ($this->valorIndice == $this->valor AND !(is_null($this->valor)) AND strlen((string) $this->valor) > 0)
        {
            $this->tag->{'checked'} = '1';
        }

        # caso o campo não seja editável
        if (!parent::obtEditavel()) {
            $this->tag->{'onclick'} = "return false;";
            $this->tag->{'style'} = 'pointer-events: none';
            $this->tag->{'tabindex'} = '-1';
        }

        $this->tag->exibe();
    }
}