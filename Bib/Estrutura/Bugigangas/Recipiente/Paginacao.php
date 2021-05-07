<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 18/05/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;

class Paginacao extends Elemento
{
    private $item;

    public function __construct($links, $rotulo = '')
    {
        parent::__construct('nav');

        $this->{'aria-label'} = $rotulo;

        $paginacao = new Elemento('ul');
        $paginacao->class = 'pagination';

        $temp = '';

        foreach ($links as $url => $desc) {

            $item = new Elemento('li');
            $item->class = 'page-item';

            $link = new Elemento('a');
            $link->class = 'page-link';
            $link->href  = $url;
            $link->adic($desc);

            $item->adic($link);

            $paginacao->adic($temp);
        }

        parent::adic($paginacao);
    }

    public function adicLink($nome_link)
    {
        $this->item = new Elemento('li');
        $this->item->class = 'page-item';

        $link = new Elemento('a');
        $link->class = 'page-link';
        $link->href  = '#'; # $url;
        $link->adic($nome_link); # $nome_link

        $this->item->adic($link);
    }
}

/**
 * 
    function criaLinks($links = Array()) {
        $temp  = "";
        $temp .= "<ul>\n";
        foreach ($links as $ch => $vl) {
            $temp .= "<li><a href=\"{$ch}.php\">$vl</a></li>\n";
        }
        $temp .= "</ul>\n";
        return $temp;
    }
 */