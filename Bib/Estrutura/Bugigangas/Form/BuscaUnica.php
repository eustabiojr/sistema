<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

/**
 * Unique Search Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BuscaUnica extends MultiBusca implements InterfaceBugiganga
{
    protected $tamanho;
    
    /**
     * Class Constructor
     * @param  $nome Widget's name
     */
    public function __construct($nome)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        parent::defTamanhoMax(1); 
        parent::defOpcaoPadrao(TRUE); 
        parent::desabilitaMultiplo(); 
        
        $this->tag->{'widget'} = 'tuniquesearch';
    }
    
    /**
     * Set value
     */
    public function defValor($valor)
    {
        $this->valor = $valor; // avoid use parent::defValor() because compat mode
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost()
    {
        if (isset($_POST[$this->nome]))
        {
            $val = $_POST[$this->nome];
            return $val;
        }
        else
        {
            return '';
        }
    }
    
    /**
     * Returns the size
     */
    public function obtTamanho()
    {
        return $this->tamanho;
    }
    
    /**
     * Show the component
     */
    public function exibe()
    {
        $this->tag->{'name'}  = $this->nome; // tag name
        parent::exibe();
    }
}
