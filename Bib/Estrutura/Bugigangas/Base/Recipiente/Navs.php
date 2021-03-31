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
    public function __construct(array $param = array(), array $args_links = array())
    {
        parent::__construct('ul');

        if (!isset($param['sub_classe'])) {
            $this->class = 'nav';
        } else {
            $this->class = 'nav ' . $param['sub_classe'];
        }

        foreach ($param['links'] as $chave => $nome_link) {

            if ($args_links['ativo'] == $chave) {

                $link = new Elemento('a');
                $link->{'class'}        = 'nav-link active';
                $link->{'aria-current'} = 'page';

                $link->href = '#';
                $link->adic($nome_link);

                $nav_item = new Elemento('li');
                $nav_item->{'class'} = 'nav-item';
                $nav_item->adic($link);

            } else if ($args_links['desabilitado'] == $chave) {

                $link = new Elemento('a');
                $link->{'class'}        = 'nav-link disabled';
                $link->href = '#';
                $link->tabindex = '-1';
                $link->{'aria-disabled'} = 'true';
                $link->adic($nome_link);

                $nav_item = new Elemento('li');
                $nav_item->{'class'} = 'nav-item';
                $nav_item->adic($link);

            } else {

                $link = new Elemento('a');
                $link->{'class'} = 'nav-link';
                $link->href = '#';
                $link->adic($nome_link);

                $nav_item = new Elemento('li');
                $nav_item->{'class'} = 'nav-item';
                $nav_item->adic($link);
            }
            # 
            parent::adic($nav_item);
        }
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