<?php
namespace Estrutura\Bugigangas\Util;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Exibe Texto
 *
 * @version    0.1
 * @package    widget
 * @subpackage util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ExibeTexto extends Elemento
{
    /**
     * Class Constructor
     * @param  $valor text content
     * @param  $cor text color
     * @param  $tamanho  text size
     * @param  $decoracao text decorations (b=bold, i=italic, u=underline)
     */
    public function __construct($valor, $cor = null, $tamanho = null, $decoracao = null)
    {
        parent::__construct('span');
        $this->{'class'} = 'ttd';
        
        $estilo = array();
        
        if (!empty($cor))
        {
            $estilo['color'] = $cor;
        }
        
        if (!empty($tamanho))
        {
            $estilo['font-size'] = (strpos($tamanho, 'px') or strpos($tamanho, 'pt')) ? $tamanho : $tamanho.'pt';
        }
        
        if (!empty($decoracao))
        {
            if (strpos(strtolower($decoracao), 'b') !== FALSE)
            {
                $estilo['font-weight'] = 'bold';
            }
            
            if (strpos(strtolower($decoracao), 'i') !== FALSE)
            {
                $estilo['font-style'] = 'italic';
            }
            
            if (strpos(strtolower($decoracao), 'u') !== FALSE)
            {
                $estilo['text-decoration'] = 'underline';
            }
        }
        
        parent::adic($valor);
        $this->{'style'} = substr( str_replace(['"',','], ['',';'], json_encode($estilo) ), 1, -1);
    }
}
