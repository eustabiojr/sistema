<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Form;

/**
 * Numeric Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Numerico extends Entrada implements InterfaceBugiganga
{
    public function __construct($nome, $decimais, $separadorDecimal, $separadorMilhar, $substituiNoPost = true)
    {
        parent::__construct($nome);
        $padrao_dec = $separadorDecimal == '.' ? '\\.' : $separadorDecimal;
        $padrao_mil = $separadorMilhar == '.' ? '\\.' : $separadorMilhar;
        
        $this->tag->{'pattern'}   = '^\\$?(([1-9](\\d*|\\d{0,2}('.$padrao_mil.'\\d{3})*))|0)('.$padrao_dec.'\\d{1,2})?$';
        $this->tag->{'inputmode'} = 'numeric';
        
        parent::setNumericMask($decimais, $separadorDecimal, $separadorMilhar, $substituiNoPost);
    }
}
