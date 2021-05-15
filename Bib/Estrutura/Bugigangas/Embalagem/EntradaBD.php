<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\BancoDados\Criterio;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Form\Entrada;
use Exception;

/**
 * Database Entry Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage wrapper
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class EntradaBD extends Entrada
{
    protected $comprimentoMin;
    protected $servico;
    protected $exibeMascara;
    private $bancodados;
    private $modelo;
    private $column;
    private $operador;
    private $ordemColuna;
    private $criterio;
    
    /**
     * Class Constructor
     * @param  $nome     widget's name
     * @param  $bancodados database name
     * @param  $modelo    model class name
     * @param  $valor    table field to be listed in the combo
     * @param  $ordercolumn column to order the fields (optional)
     * @param  $criterio criteria (TCriteria object) to filter the model (optional)
     */
    public function __construct($nome, $bancodados, $modelo, $valor, $ordemColuna = NULL, Criterio $criterio = NULL)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        
        $valor = trim($valor);
        
        if (empty($bancodados)) 
        {
            throw new Exception("O parâmetro (bancodados) {__CLASS__} é obrigatório");
        }
        
        if (empty($modelo)) 
        {
            throw new Exception("O parâmetro (modelo) {__CLASS__} é obrigatório");
        }
        
        if (empty($valor)) 
        {
            throw new Exception("O parâmetro (valor) {__CLASS__} é obrigatório");
        }
        
        $this->comprimentoMin = 1;
        $this->bancodados = $bancodados;
        $this->modelo = $modelo;
        $this->coluna = $valor;
        $this->exibeMascara = '{'.$valor.'}';
        $this->operador = null;
        $this->ordemColuna = isset($ordemColuna) ? $ordemColuna : NULL;
        $this->criterio = $criterio;
        $this->servico = 'AdiantiAutocompleteService';
    }
    
    /**
     * Define the display mask
     * @param $mascara Show mask
     */
    public function defExibeMascara($mascara)
    {
        $this->exibeMascara = $mascara;
    }
    
    /**
     * Define the search service
     * @param $servico Search service
     */
    public function defServico($servico)
    {
        $this->servico = $servico;
    }
    
    /**
     * Define the minimum length for search
     */
    public function defComprimentoMin($comprimento)
    {
        $this->comprimentoMin = $comprimento;
    }
    
    /**
     * Define the search operator
     * @param $operador Search operator
     */
    public function defOperador($operador)
    {
        $this->operador = $operador;
    }
    
    /**
     * Shows the widget
     */
    public function exibe()
    {
        parent::exibe();
        
        $min = $this->comprimentoMin;
        $ordemColuna = isset($this->ordemColuna) ? $this->ordemColuna : $this->coluna;
        $criterio = '';
        if ($this->criterio)
        {
            $criterio = base64_encode(serialize($this->criterio));
        }
        
        $seed = NOME_APLICATIVO.'s8dkld83kf73kf094';
        $hash = md5("{$seed}{$this->bancodados}{$this->coluna}{$this->modelo}");
        $comprimento = $this->comprimentoMin;
        
        $class = $this->servico;
        $callback = array($class, 'onSearch');
        $metodo = $callback[1];
        $url = "engine.php?class={$class}&method={$metodo}&static=1&database={$this->bancodados}&column={$this->coluna}&model={$this->modelo}&ordemColuna={$ordemColuna}&criteria={$criterio}&operator={$this->operador}&hash={$hash}&mask={$this->exibeMascara}";
        
        Script::cria(" tdbentry_start( '{$this->nome}', '{$url}', '{$min}' );");
    }
}
