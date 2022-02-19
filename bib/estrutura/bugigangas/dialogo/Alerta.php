<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Dialogo;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Alert
 *
 * @version    7.1
 * @package    widget
 * @subpackage dialog
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Alerta extends Elemento
{
    /**
     * Class Constructor
     * @param $tipo    Type of the alert (success, info, warning, danger)
     * @param $mensagem Message to be shown
     */
    public function __construct($tipo, $mensagem)
    {
        parent::__construct('div');
        $this->{'class'} = 'talert alert alert-dismissible alert-'.$tipo;
        $this->{'role'}  = 'alert';
        
        $botao = new Elemento('button');
        $botao->{'type'} = 'button';
        $botao->{'class'} = 'close';
        $botao->{'data-dismiss'} = 'alert';
        $botao->{'aria-label'}   = 'Close';
        
        $span = new Elemento('span');
        $span->{'aria-hidden'} = 'true';
        $span->adic('&times;');
        $botao->adic($span);
        
        parent::adic($botao);
        parent::adic($mensagem);
    }
}
