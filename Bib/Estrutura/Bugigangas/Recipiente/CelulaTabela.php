<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 09/06/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;

/**
 * CelulaTabela - Representa uma célula dentro da tabela
 * 
 * @version    0.1
 * @package    widget
 * @subpackage form
 * @author     Pablo DallOglio (E modificado por Eustábio J. Silva Jr.)
 * @copyright  Copyright (c) 2020 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CelulaTabela extends Elemento
{
    /**
     * Classe Construtor
     * @param $valor Conteúdo de CelulaTabela
     */
    public function __construct($valor, $tag = 'td')
    {
        parent::__construct($tag);
        parent::adic($valor);
    }
}