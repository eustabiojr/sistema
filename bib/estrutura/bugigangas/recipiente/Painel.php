<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 09/06/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Estilo;

/**
 * Panel Container: Allows to organize the widgets using fixed (absolute) positions
 * 
 * Esta bugiganga está obsoleta. Vamos migrar para a bugiganga 'Cartao'.
 *
 * @version    7.1
 * @package    widget
 * @subpackage container
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Painel extends Elemento
{
    private $style;
    private $largura;
    private $altura;
    
    /**
     * Class Constructor
     * @param  $largura   Panel's width
     * @param  $altura  Panel's height
     */
    public function __construct($largura, $altura)
    {
        parent::__construct('div');
		
        $this->{'id'} = 'tpanel_' . mt_rand(1000000000, 1999999999);
        
        // creates the panel style
        $this->style = new Estilo('style_'.$this->{'id'});
        $this->style->position = 'relative';
        $this->largura = $largura;
        $this->altura = $altura;
        
        $this->{'class'} = 'style_'.$this->{'id'};
    }
    
    /**
     * Set the panel's size
     * @param $largura Panel width
     * @param $altura Panel height
     */
    public function defTamanho($largura, $altura)
    {
        $this->largura = $largura;
        $this->altura = $altura;
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
     * Put a widget inside the panel
     * @param  $bugiganga = widget to be exiben
     * @param  $col    = column in pixels.
     * @param  $linha    = row in pixels.
     */
    public function colocar($bugiganga, $col, $linha)
    {
        // creates a layer to colocar the widget inside
        $camada = new Elemento('div');
        // define the layer position
        $camada->style = "position:absolute; left:{$col}px; top:{$linha}px;";
        // add the widget to the layer
        $camada->adic($bugiganga);
        
        // add the widget to the container
        parent::adic($camada);
    }
    
    /**
     * Exibe a bugiganga
     */
    public function exibe()
    {
        $this->style->largura  = $this->largura.'px';
        $this->style->altura = $this->altura.'px';
        $this->style->exibe();
        
        parent::exibe();
    }
}
