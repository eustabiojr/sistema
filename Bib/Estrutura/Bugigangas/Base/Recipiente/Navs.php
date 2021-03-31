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
 * Classe Navs 
 */
class Navs extends Elemento 
{
    public function __construct($param = NULL)
    {
        parent::__construct('ul');

        $this->class = 'nav';

        if (is_array($param) AND isset($param['nome_link'])) {
            $nome_link = $param['nome_link'];
        } else {
            $nome_link = $param;
        }

        if (isset($param['sub_classe'])) {
            $this->class = 'card ' . $param['sub_classe'];
        }

        $link = new Elemento('a');
        $link->{'class'} = 'nav-link';
        $link->href = '#';
        $link->adic($nome_link);

        $nav_item = new Elemento('li');
        $nav_item->{'class'} = 'nav-item';
        $nav_item->adic($link);
        #parent::adic($cabecalho);
    }

    /**
     * Método adic
     */
    public function adic($conteudo)
    {
        $this->corpo->adic($conteudo);
        parent::adic($this->corpo);
    }
}