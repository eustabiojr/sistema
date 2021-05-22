<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 09/06/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Form\Rotulo;

/**
 * Frame Widget: creates a bordered area with a title located at its top-left corner
 *
 * @version    7.1
 * @package    widget
 * @subpackage container
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Moldura extends Elemento
{
    private $legenda;
    private $largura;
    private $altura;
    
    /**
     * Class Constructor
     * @param  $value text label
     */
    public function __construct($largura = NULL, $altura = NULL)
    {
        parent::__construct('fieldset');
        $this->{'id'}    = 'tfieldset_' . mt_rand(1000000000, 1999999999);
        $this->{'class'} = 'tframe';
        
        $this->largura  = $largura;
        $this->altura = $altura;
        
        if ($largura)
        {
            $this->{'style'} .= (strstr($largura, '%') !== FALSE) ? ";width:{$largura}" : ";width:{$largura}px";
        }
        
        if ($altura)
        {
            $this->{'style'} .= (strstr($altura, '%') !== FALSE) ? ";height:{$altura}" : ";height:{$altura}px";
        }
    }
    
    /**
     * Returns the frame size
     * @return array(largura, altura)
     */
    public function obtTamanho()
    {
        return array($this->largura, $this->altura);
    }
    
    /**
     * Set Legend
     * @param  $legenda frame legend
     */
    public function defLegenda($legenda)
    {
        $obj = new Elemento('legend');
        $obj->adic(new Rotulo($legenda));
        parent::adic($obj);
        $this->legenda = $legenda;
    }
    
    /**
     * Returns the inner legend
     */
    public function obtLegenda()
    {
        return $this->legenda;
    }
    
    /**
     * Return the Frame ID
     * @ignore-autocomplete on
     */
    public function obtId()
    {
        return $this->{'id'};
    }
}
