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
class NavsAbas extends Elemento 
{
    private $params;
    private $nav_item;

    /**
    * Método Construtor
    */
    public function __construct(NavItens $nav_itens/*, $abas_conteudo = ''*/)
    {
        parent::__construct('ul');

        $params = $nav_itens->obtItens();

        if (!isset($params['param']['sub_classe'])) { 
            $this->class = 'nav';
        } else { 
            $this->class = 'nav ' . $params['param']['sub_classe'];
        }

        if (isset($params['param']['id'])) { 
            $this->id = $params['param']['id'];
        }

        if (isset($params['param']['role'])) { 
            $this->role = 'tablist';
        }

        $modo_link = $params['param']['modo_link'] ?? 'a';

        if (isset($params['links'])) {

            foreach ($params['links'] as $indice => $link_indice) {

                $indice_nome = array_keys($link_indice[0]);
                $valor_nome = array_values($link_indice[0]);

                $indice_link = $indice_nome[0];
                $nome_link   = $valor_nome[0];
                $classe_link = $link_indice[1] ?? 'nav-item';

                if ($params['param']['ativo'] == $indice) {

                    if ($modo_link == 'a') {
                        $link = new Elemento('a');
                        $link->{'class'}        = 'nav-link active';
                        $link->{'aria-current'} = 'page';
                        $link->href = '#';
                        $link->adic($nome_link);
                    } elseif ($modo_link == 'button') {
                        $link = new Elemento('button');
                        $link->{'class'}        = 'nav-link active';
                        $link->id                 = $indice_link . '-tab';
                        $link->{'data-bs-toggle'} = '#' . $indice_link;
                        $link->{'data-bs-target'} = 'tab';
                        $link->type               = 'button';
                        $link->role               = 'tab';
                        $link->{'aria-controls'}  = $indice_link;
                        $link->{'aria-selected'}  = 'true';
                        $link->adic($nome_link);
                    }

                    $nav_item = new Elemento('li');
                    $nav_item->{'class'} = $classe_link;
                    if ($modo_link == 'button') {
                        $nav_item->role = 'presentation';
                    }
                    $nav_item->adic($link);

                } else if ($params['param']['desabilitado'] == $indice) {

                    if ($modo_link == 'a') {
                        $link = new Elemento('a');
                        $link->{'class'}        = 'nav-link disabled';
                        $link->href             = '#';
                        $link->tabindex          = '-1';
                        $link->adic($nome_link);
                    } elseif ($modo_link == 'button') {
                        $link = new Elemento('button');
                        $link->{'class'}          = 'nav-link';
                        $link->id                 = $indice_link . '-tab';
                        $link->{'data-bs-toggle'} = '#' . $indice_link;
                        $link->{'data-bs-target'} = 'tab';
                        $link->type               = 'button';
                        $link->role               = 'tab';
                        $link->{'aria-controls'}  = $indice_link;
                        $link->{'aria-selected'}  = 'false';
                        $link->adic($nome_link);
                    }

                    $nav_item = new Elemento('li');
                    $nav_item->{'class'} = $classe_link;
                    if ($modo_link == 'button') {
                        $nav_item->role = 'presentation';
                    }
                    $nav_item->adic($link);

                } else {

                    if ($modo_link == 'a') {
                        $link = new Elemento('a');
                        $link->{'class'}        = 'nav-link';
                        $link->href             = '#';
                        $link->adic($nome_link);
                    } elseif ($modo_link == 'button') {
                        $link = new Elemento('button');
                        $link->{'class'}          = 'nav-link';
                        $link->id                 = $indice_link . '-tab';
                        $link->{'data-bs-toggle'} = '#' . $indice_link;
                        $link->{'data-bs-target'} = 'tab';
                        $link->type               = 'button';
                        $link->role               = 'tab';
                        $link->{'aria-controls'}  = $indice_link;
                        $link->{'aria-selected'}  = 'false';
                        $link->adic($nome_link);
                    }

                    $nav_item = new Elemento('li');
                    $nav_item->{'class'} = $classe_link;
                    if ($modo_link == 'button') {
                        $nav_item->role = 'presentation';
                    }
                    $nav_item->adic($link);
                }
                $div = new Elemento('div');
                #$div->class = "style: dashed 2px black;";
                #$div->style = "dashed 6px black;";
                $div->adic($nav_item);
                parent::adic($div);
            } # Fim do foreach
        }
        /*
        if (!empty($abas_conteudo)) {
            parent::adic($abas_conteudo);
        }*/
    } # Fim do construtor
}