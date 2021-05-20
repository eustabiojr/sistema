<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Form;



/**
 * Campo Oculto
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Oculto extends Campo implements InterfaceBugiganga
{
    protected $id;
    
    /**
     * Return the post data
     */
    public function obtDadosPost()
    {
        $nome = str_replace(['[',']'], ['',''], $this->nome);
        
        if (isset($_POST[$nome]))
        {
            return $_POST[$nome];
        }
        else
        {
            return '';
        }
    }
    
    /**
     * Show the widget at the screen
     */
    public function exibe()
    {
        // set the tag properties
        $this->tag->{'name'}   = $this->nome;  // tag name
        $this->tag->{'value'}  = $this->valor; // tag value
        $this->tag->{'type'}   = 'hidden';     // input type
        $this->tag->{'widget'} = 'thidden';
        $this->tag->{'style'}  = "width:{$this->tamanho}";
        
        if ($this->id and empty($this->tag->{'id'}))
        {
            $this->tag->{'id'} = $this->id;
        }
        else
        {
            $this->tag->{'id'} = 'thidden_' . mt_rand(1000000000, 1999999999);
        }
        
        // shows the widget
        $this->tag->exibe();
    }
}