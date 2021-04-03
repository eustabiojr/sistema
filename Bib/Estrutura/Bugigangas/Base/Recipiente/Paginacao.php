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
    public function __construct(array $paginas = array(), $classe = 'Page', $subclasse = '', $tipo = 0)
    {
        parent::__construct('nav');

        $this->{'aria-label'} = $classe;

	    $ul = new Elemento('ul');
	    $ul->class = (!empty($subclasse)) ? 'pagination ' . $subclasse : 'pagination';

        $paginas = array('1','2','3','4','5');

        $anterior_posterior = true;

        if ($anterior_posterior) {
        	$anterior = $tipo === 0 ? 'Anterior' : '&laquo;';
        	$a = new Elemento('a');
        	$a->class = 'page-link';
        	$a->href  = '#';
        	$a->{'aria-label'} = 'Anterior';
        	$a->adic($anterior);

        	$li = new Elemento('li');
        	$li->class = 'page-item';
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

        if ($anterior_posterior) {
        	$anterior = $tipo === 0 ? 'Posterior' : '&raquo;';
        	$a = new Elemento('a');
        	$a->class = 'page-link';
        	$a->href  = '#';
        	$a->{'aria-label'} = 'Anterior';
        	$a->adic($anterior);

        	$li = new Elemento('li');
        	$li->class = 'page-item';
        	$li->adic($a);

        	$ul->adic($li);
        }
	    parent::adic($ul);
    }
}