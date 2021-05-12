<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Util;

use Estrutura\Controle\Acao;

/**
 * Link Acao
 *
 * @version    7.1
 * @package    widget
 * @subpackage util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class LinkAcao extends ExibeTexto
{
    /**
     * Class Constructor
     * @param  $valor  text content
     * @param  $acao TAction Object
     * @param  $cor  text color
     * @param  $tamanho   text size
     * @param  $decoracao text decorations (b=bold, i=italic, u=underline)
     */
    public function __construct($valor, Acao $acao, $cor = null, $tamanho = null, $decoracao = null, $icone = null)
    {
        if ($icone)
        {
            $valor = new Imagem($icone) . $valor;
        }
        
        parent::__construct($valor, $cor, $tamanho, $decoracao);
        parent::defNome('button');
        
        $this->{'href'} = $acao->serializa();
        $this->{'generator'} = 'ageunet';
    }
    
    /**
     * Add CSS class
     */
    public function adicEstiloClasse($classe) 
    {
        $this->{'class'} .= " {$classe}";
    }
}
