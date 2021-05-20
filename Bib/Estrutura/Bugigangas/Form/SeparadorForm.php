<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Form separator
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SeparadorForm extends Elemento
{
    private $corFonte;
    private $corSeparador;
    private $tamanhoFonte;
    private $cabecalho;
    private $divisor;
    
    /**
     * Class Constructor
     * @param $texto Separator title
     */
    public function __construct($texto, $corFonte = '#333333', $tamanhoFonte = '16', $corSeparador = '#eeeeee')
    {
        parent::__construct('div');
        
        $this->corFonte = $corFonte;
        $this->corSeparador = $corSeparador;
        $this->tamanhoFonte = $tamanhoFonte;
        
        $this->cabecalho = new Elemento('h4');
        $this->cabecalho->{'class'} = 'tseparator';
        $this->cabecalho->{'style'} = "font-size: {$this->tamanhoFonte}px; color: {$this->corFonte};";
        
        $this->divisor = new Elemento('hr');
        $this->divisor->{'style'} = "border-top-color: {$this->corSeparador}";
        $this->divisor->{'class'} = 'tseparator-divisor';
        $this->cabecalho->adic($texto);

        $this->adic($this->cabecalho);
        $this->adic($this->divisor);
    }

    /**
     * Set font size
     * @param $tamanho font size
     */
    public function defTamanhoFonte($tamanho)
    {
        $this->tamanhoFonte = $tamanho;
        $this->cabecalho->{'style'} = "font-size: {$this->tamanhoFonte}px; color: {$this->corFonte};";
    }
    
    /**
     * Set font color
     * @param $cor font color
     */
    public function defCorFonte($cor)
    {
        $this->corFonte = $cor;
        $this->cabecalho->{'style'} = "font-size: {$this->tamanhoFonte}px; color: {$this->corFonte};";
    }

    /**
     * Set separator color
     * @param $cor separator color
     */
    public function defCorSeparador($cor)
    {
        $this->corSeparador = $cor;
        $this->divisor->{'style'} = "border-top-color: {$this->corSeparador}";
    }
}
