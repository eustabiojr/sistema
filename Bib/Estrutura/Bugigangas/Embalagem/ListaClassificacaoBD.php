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
use Estrutura\Bugigangas\Form\ListaClassificacao;

use Exception;

/**
 * Database Sortlist Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage wrapper
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ListaClassificacaoBD extends ListaClassificacao
{
    protected $itens; // array containing the combobox options
    
    /**
     * Class Constructor
     * @param  $nome     widget's name
     * @param  $bancodados database name
     * @param  $modelo    model class name
     * @param  $chave      table field to be used as key in the combo
     * @param  $valor    table field to be listed in the combo
     * @param  $ordemColuna column to order the fields (optional)
     * @param  $criterio criteria (Criterio object) to filter the model (optional)
     */
    public function __construct($nome, $bancodados, $modelo, $chave, $valor, $ordemColuna = NULL, Criterio $criterio = NULL)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        
        if (empty($bancodados)) {
            throw new Exception("O parâmetro (bancodados) {__CLASS__} é obrigatório");
        }
        
        if (empty($modelo)) {
            throw new Exception("O parâmetro (modelo) {__CLASS__} é obrigatório");
        }
        
        if (empty($chave)) {
            throw new Exception("O parâmetro (chave) {__CLASS__} é obrigatório");
        }
        
        if (empty($valor)) {
            throw new Exception("O parâmetro (valor) {__CLASS__} é obrigatório");
        }
        
        // carrega objetos do banco de dados
        Transacao::abre($bancodados);
        // instancia um repositório de Estado
        $repositorio = new Repositorio($modelo);
        if (is_null($criterio))
        {
            $criterio = new Criterio;
        }
        $criterio->defPropriedade('order', isset($ordemColuna) ? $ordemColuna : $chave);
        // carrega todos objetos
        $colecao = $repositorio->carrega($criterio, FALSE);
        
        // adiciona objetos na combo
        if ($colecao)
        {
            $itens = array();
            foreach ($colecao as $objeto)
            {
                if (isset($objeto->$valor))
                {
                    $itens[$objeto->$chave] = $objeto->$valor;
                }
                else
                {
                    $itens[$objeto->$chave] = $objeto->render($valor);
                }
            }
            
            if (strpos($valor, '{') !== FALSE AND is_null($ordemColuna))
            {
                asort($itens);
            }
            parent::adicItens($itens);
        }
        Transacao::fecha();
    }
}
