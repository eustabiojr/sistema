<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 12/04/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

# Espaço de nomes

/**
 * Classe CaixaV
 * 
* @version    0.1
 * @package    widget
 * @subpackage form
 * @author     Pablo DallOglio (E modificado por Eustábio J. Silva Jr.)
 * @copyright  Copyright (c) 2020 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CaixaV extends Elemento
{
    public function __construct() 
    {
        parent::__construct('div');
        $this->{'style'} = 'display: inline-block';
    }

    public function adic($filho) 
    {
        $embrulho = new Elemento('div');
        $embrulho->{'style'} = 'clear: both';
        $embrulho->adic($filho);
        parent::adic($embrulho);
        return $embrulho;
    }

    /**
     * Adiciona uma nova coluna com várias células
     * @param $celulas Cada argumento é uma linha de células
     */
    public function adicGrupoColuna()
    {
        $args = func_get_args();
        if ($args) {
            foreach ($args as $arg) {
                $this->adic($arg);
            }
        }
    }

    /**
     * Método estático para conteúdo pacote
     * @param $celulas Cada argumento é uma célula
     */
    public static function pacote()
    {
        $caixa = new self;
        $args = func_get_args();
        if ($args) {
            foreach ($args as $arg) {
                $caixa->adic($arg);
            }
        }
        return $caixa;
    }
}
