<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Form\ListaVerificacao;
use Exception;

/**
 * Database Checklist
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ListaVerificacaoBD extends ListaVerificacao
{
    protected $items; // array containing the combobox options
    protected $chaveColumn;
    protected $valorColumn;
    
    /**
     * Class Constructor
     * @param  $nome     widget's name
     * @param  $bancodados database name
     * @param  $modelo    model class name
     * @param  $chave      table field to be used as key in the combo
     * @param  $valor    table field to be listed in the combo
     * @param  $ordemColuna column to order the fields (optional)
     * @param  $criterio criterio (TCriteria object) to filter the model (optional)
     */
    public function __construct($nome, $bancodados, $modelo, $chave, $valor, $ordemColuna = NULL, Criterio $criterio = NULL)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        
        $chave   = trim($chave);
        $valor = trim($valor);
        
        if (empty($bancodados)) 
        {
            throw new Exception("O parâmetro (bancodados) {__CLASS__} é obrigatório");
        }
        
        if (empty($modelo)) 
        {
            throw new Exception("O parâmetro (modelo) {__CLASS__} é obrigatório");
        }
        
        if (empty($chave)) 
        {
            throw new Exception("O parâmetro (chave) {__CLASS__} é obrigatório");
        }
        
        if (empty($valor)) 
        {
            throw new Exception("O parâmetro (valor) {__CLASS__} é obrigatório");
        }
        
        parent::defIdColuna($chave);
        //$this->keyColumn = parent::addColumn($chave,    '',    'center',  '10%');
        $this->valorColuna = parent::adicColuna($valor,  '',    'left',  '100%');
        
        Transacao::abre($bancodados);
        
        // creates repository
        $repositorio = new Repositorio($modelo);
        if (is_null($criterio))
        {
            $criterio = new Criterio;
        }
        $criterio->defPropriedade('order', isset($ordemColuna) ? $ordemColuna : $chave);
        
        // load all objects
        $colecao = $repositorio->carrega($criterio, FALSE);
        
        // add objects to the options
        if ($colecao)
        {
            $items = array();
            foreach ($colecao as $objeto)
            {
                $items[$objeto->$chave] = $objeto;
                
                if (isset($objeto->$valor))
                {
                    $items[$objeto->$chave]->$valor = $objeto->$valor;
                }
                else
                {
                    $items[$objeto->$chave]->$valor = $objeto->renderiza($valor);
                }
            }
            
            if (strpos($valor, '{') !== FALSE AND is_null($ordemColuna))
            {
                asort($items);
            }
            parent::addItems($items);
        }
        $cabecalho = parent::getHead();
        $cabecalho->{'style'} = 'display:none';
        Transacao::fecha();
    }
}
