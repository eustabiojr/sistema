<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Estrutura\Controle\Pagina;

/**
 * Standard page controller for form/listings
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ListaFormPadrao extends Pagina
{
    protected $form;
    protected $gradedados;
    protected $navegacaoPagina;
    protected $filterField;
    protected $carregado;
    protected $limite;
    protected $ordem;
    protected $direcao;
    protected $criterio;
    protected $callbackTransforma;
    
    use TracoListaFormPadrao;
}