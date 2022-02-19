<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Estrutura\Base\TracoListaPadrao;
use Estrutura\Controle\Pagina;

/**
 * Página controladora padrão para listagens
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ListaPadrao extends Pagina
{
    protected $form;
    protected $gradedados;
    protected $paginaNavegacao;
    
    use TracoListaPadrao;
}
