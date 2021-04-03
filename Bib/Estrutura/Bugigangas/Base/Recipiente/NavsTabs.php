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
 * Classe NavsTabs 
 */
class NavsTabs extends Elemento 
{
    private $params;
    private $nav_item;

    /**
    * Método Construtor
    */
    public function __construct(NavItens $nav_itens)
    {
        parent::__construct('ul');

        $params = $nav_itens->obtItens();

        if (!isset($params['param']['sub_classe'])) { 
            $this->class = 'nav';
        } else { 
            $this->class = 'nav ' . $params['param']['sub_classe'];
        }

        if (isset($params['links'])) {

            foreach ($params['links'] as $indice => $link_indice) {

                $nome_link = $link_indice[0];
                $tipo_link = $link_indice[1] ?? 'nav-item';

                if ($params['param']['ativo'] == $indice) {

                    $link = new Elemento('a');
                    $link->{'class'}        = 'nav-link active';
                    $link->{'aria-current'} = 'page';

                    $link->href = '#';
                    $link->adic($nome_link);

                    $nav_item = new Elemento('li');
                    $nav_item->{'class'} = $tipo_link;
                    $nav_item->adic($link);

                } else if ($params['param']['desabilitado'] == $indice) {

                    $link = new Elemento('a');
                    $link->{'class'}         = 'nav-link disabled';
                    $link->href              = '#';
                    $link->tabindex          = '-1';
                    $link->{'aria-disabled'} = 'true';
                    $link->adic($nome_link);

                    $nav_item = new Elemento('li');
                    $nav_item->{'class'} = $tipo_link;
                    $nav_item->adic($link);

                } else {

                    $link = new Elemento('a');
                    $link->{'class'} = 'nav-link';
                    $link->href      = '#';
                    $link->adic($nome_link);

                    $nav_item = new Elemento('li');
                    $nav_item->{'class'} = $tipo_link;
                    $nav_item->adic($link);
                }
                parent::adic($nav_item);
            }
        }
    }

    /**
     * Método adicAba
     */
    public function adicAba($conteudo)
    {
        $div = new Elemento('div');
        $div->class = 'tab-content';

        $abas = array();

        foreach($abas as $chave => $valor) {
            $aba = new Elemento('div');
            $aba->class               = 'tab-pane active';
            $aba->id                  = 'inicio';
            $aba->role                = 'tabpanel';
            $aba->{'aria-labelledby'} = 'home-tab';
            $aba->adic($conteudo);

            $div->adic($aba);
        }
    }
}