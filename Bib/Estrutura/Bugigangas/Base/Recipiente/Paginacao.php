<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Autor: Eustábio J. Silva Jr. 
 * Data: 03/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe Paginacao
 */
class Paginacao extends Elemento
{
    #private $params;

    /**
    * Método Construtor
    */
    public function __construct(array $paginas = [], array $params = [])
    {
        parent::__construct('nav');

        $this->{'aria-label'} = $params['classe'] ?? 'Page';

	    $ul = new Elemento('ul');
	    $ul->class = (!isset($params['subclasse'])) ? 'pagination' : 'pagination ' . $params['subclasse'];

        $links_adjacentes = $params['links_adjacentes'] ?? null;

        $tipo = !isset($params['tipo']) ? 0 : $params['tipo']; 

        if ($links_adjacentes) {
            $anterior = $tipo === 0 ? 'Anterior' : '&laquo;';
            $span = new Elemento('span');
            $span->{'aria-hidden'} = 'true';
            $span->adic($anterior);

        	$a = new Elemento('a');
        	$a->class = 'page-link';
        	$a->href  = '#';
        	$a->{'aria-label'} = 'Anterior';
            if (1) {
                $a->tabindex = '-1';
                $a->{'aria-disabled'} = 'true';
            }
            if ($params['span']) {
                $a->adic($span);
            } else {
               $a->adic($anterior);
            }

        	$li = new Elemento('li'); 
        	$li->class = $param['item_desativado'] ?? 'page-item'; 
        	$li->adic($a);

        	$ul->adic($li);
        }

        foreach ($paginas as $link => $valor) {

        	$a = new Elemento('a');
        	$a->class = 'page-link';
        	$a->href  = '#' . $link;
        	$a->adic($valor);

        	$li = new Elemento('li');
        	$li->class = 'page-item';
        	$li->adic($a);

        	$ul->adic($li);
        }

        if ($links_adjacentes) {

            $anterior = $tipo === 0 ? 'Anterior' : '&laquo;';
            $span = new Elemento('span');
            $span->{'aria-hidden'} = 'true';
            $span->adic($anterior);

        	$a = new Elemento('a');
        	$a->class = 'page-link';
        	$a->href  = '#';
        	$a->{'aria-label'} = 'Anterior';
            if ($params['span']) {
                $a->adic($span);
            } else {
               $a->adic($anterior);
            }

        	$li = new Elemento('li');
        	$li->class = 'page-item';
        	$li->adic($a);

        	$ul->adic($li);
        }
	    parent::adic($ul);
    }
}