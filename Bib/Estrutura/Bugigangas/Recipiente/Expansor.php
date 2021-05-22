<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 09/06/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;

/**
 * Expander Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage recipiente
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Expansor extends Elemento
{
    private $recipiente;
    private $botao;
    private $lado_caret;
    private $rotulo;
    
    /**
     * Class Constructor
     * @param  $valor text label
     */
    public function __construct($rotulo = '')
    {
        parent::__construct('div');
        $this->{'id'}    = 'texpander_'.mt_rand(1000000000, 1999999999);
        $this->{'class'} = 'dropdown';
        
        $this->botao = new Elemento('button');
        $this->botao->{'class'} = 'btn btn-default dropdown-toggle';
        $this->botao->{'type'} = 'button';
        $this->botao->{'id'}   = 'button_'.mt_rand(1000000000, 1999999999);
        $this->botao->{'data-toggle'} = 'dropdown';
        $this->rotulo = $rotulo;
        
        $this->recipiente = new Elemento('ul');
        $this->recipiente->{'class'} = 'dropdown-menu texpander-recipiente';
        
        $this->recipiente->{'aria-labelledby'} = $this->botao->{'id'};
        
        parent::adic($this->botao);
        parent::adic($this->recipiente);
    }
    
    /**
     * Set caret side
     * @lado_caret Caret side (left, right)
     */
    public function defLadoCaret($lado_caret) 
    {
        $this->lado_caret = $lado_caret;
    }
    
    /**
     * Define the pull side
     * @side left/right
     */
    public function defPuxarLado($lado) 
    {
        $this->recipiente->{'class'} = "dropdown-menu texpander-recipiente pull-{$lado}";
    }
    
    /**
     * Define a button property
     * @param $propriedade Property name (Ex: style)
     * @param $valor    Property value
     */
    public function defPropriedadeBotao($propriedade, $valor) 
    {
        $this->botao->$propriedade = $valor;
    }
    
    /**
     * Define a recipiente property
     * @param $propriedade Property name (Ex: style)
     * @param $valor    Property value
     */
    public function defPropriedade($propriedade, $valor)
    {
        $this->recipiente->$propriedade = $valor;
    }
    
    /**
     * Add content to the expander
     * @param $conteudo Any Object that implements exibe() method
     */
    public function adic($conteudo)
    {
        $this->recipiente->adic($conteudo);
    }
    
    /**
     * Shows the expander
     */
    public function exibe()
    {
        if ($this->lado_caret == 'left')
        {
            $this->botao->adic(Elemento::tag('span', '', array('class'=>'caret')));
            $this->botao->adic($this->rotulo);
        }
        else if ($this->lado_caret == 'right')
        {
            $this->botao->adic($this->rotulo);
            $this->botao->adic('&nbsp');
            $this->botao->adic(Elemento::tag('span', '', array('class'=>'caret')));
        }
        else
        {
            $this->botao->adic($this->rotulo);
        }
        
        parent::exibe();
        Script::cria('texpander_start();');
    }
}
