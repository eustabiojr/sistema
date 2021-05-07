<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 12/04/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe CaixaH 
 * 
* @version    0.1
 * @package    widget
 * @subpackage form
 * @author     Pablo DallOglio (E modificado por Eustábio J. Silva Jr.)
 * @copyright  Copyright (c) 2020 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CaixaH extends Elemento
{
    /**
     * Classe construtor
     */
    public function __construct() 
    {
        parent::__construct('div');
    }

    public function adic($filho, $estilo = 'display: inline-table;') 
    {
        $embrulho = new Elemento('div');
        $embrulho->{'style'} = $estilo;
        $embrulho->adic($filho);
        parent::adic($embrulho);
        return $embrulho;
    }

    /**
     * Adiciona uma nova linha com várias células
     * @param $celulas Cada argumento é uma linha de células
     */
    public function adicGrupoLinha()
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

