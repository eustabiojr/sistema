<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\MultiBusca;
use Estrutura\Nucleo\ConfigAplicativo;
use Exception;

/**
 * Database Multisearch Widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage wrapper
 * @author     Pablo Dall'Oglio
 * @author     Matheus Agnes Dias
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiBuscaBD extends MultiBusca
{
    protected $id;
    protected $initialItems;
    protected $itens;
    protected $tamanho;
    protected $altura;
    protected $tamnhoMin;
    protected $tamanhoMax;
    protected $bancodados;
    protected $modelo;
    protected $chave;
    protected $coluna;
    protected $operador;
    protected $ordemColuna;
    protected $criterio;
    protected $mascara;
    protected $servico;
    protected $seed;
    protected $editavel;
    protected $mudaFuncao;
    protected $idBusca;
    protected $idTextoBusca;
    
    /**
     * Class Constructor
     * @param  $nome     widget's name
     * @param  $bancodados bancodados name
     * @param  $modelo    model class name
     * @param  $chave      table field to be used as key in the combo
     * @param  $valor    table field to be listed in the combo
     * @param  $ordercoluna coluna to order the fields (optional)
     * @param  $criterio criterio (TCriteria object) to filter the model (optional)
     */
    public function __construct($nome, $bancodados, $modelo, $chave, $valor, $ordemColuna = NULL, Criterio $criterio = NULL)
    {
        // executes the parent class constructor
        parent::__construct($nome);
        $this->id   = 'tdbmultisearch_'.mt_rand(1000000000, 1999999999);
        
        $chave   = trim($chave);
        $valor = trim($valor);

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
        
        $ini = ConfigAplicativo::obt();
        
        $this->bancodados = $bancodados;
        $this->modelo = $modelo;
        $this->chave = $chave;
        $this->coluna = $valor;
        $this->operador = null;
        $this->ordemColuna = isset($ordemColuna) ? $ordemColuna : NULL;
        $this->criterio = $criterio;
        
        if (strpos($valor,',') !== false) {
            $colunas = explode(',', $valor);
            $this->mascara = '{'.$colunas[0].'}';
        } else {
            $this->mascara = '{'.$valor.'}';
        }
        
        $this->servico = 'AgeunetServicoMultiBusca';
        $this->seed = NOME_APLICATIVO . ( !empty($ini['general']['seed']) ? $ini['general']['seed'] : 's8dkld83kf73kf094' );
        $this->tag->{'widget'} = 'tdbmultisearch';
        $this->idBusca = true;
        $this->idTextoBusca = false;
    }
    
    /**
     * Define the search service
     * @param $servico Search service
     */
    public function defServico($servico) {
        $this->servico = $servico;
    }
    
    /**
     * Disable search by id
     */
    public function desabilitaIdBusca() {
        $this->idBusca = false;
    }
    
    /**
     * Enable Id textual search
     */
    public function habilitaBuscaTextual() {
        $this->idTextoBusca = true;
    }
    
    /**
     * Define the search operador
     * @param $operador Search operador
     */
    public function defOperador($operador) {
        $this->operador = $operador;
    }
    
    /**
     * Define the display mascara
     * @param $mascara Show mascara
     */
    public function defMascara($mascara) {
        $this->mascara = $mascara;
    }
    
    /**
     * Define the field's value
     * @param $valores An array the field's values
     */
    public function defValor($valores)
    {
        $valores_originais = $valores;
        $ini = ConfigAplicativo::obt();
        
        if (isset($ini['general']['compat']) AND $ini['general']['compat'] ==  '4') {
            if ($valores) {
                parent::defValor( $valores );
                parent::adicItens( $valores );
            }
        } else {
            $itens = [];
            if ($valores) {
                if (!empty($this->separador)) {
                    $valores = explode($this->separador, $valores);
                }
                
                Transacao::abre($this->bancodados);
                foreach ($valores as $valor)
                {
                    if ($valor) {
                        $modelo = $this->modelo;
                        
                        $pk = constant("{$modelo}::PRIMARYKEY");
                        
                        if ($pk === $this->chave) { // key is the primary key (default) 
                            // use find because it uses cache
                            $objeto = $modelo::localiza( $valor );
                        } else { // key is an alternative key (uses where->first)
                            $objeto = $modelo::where( $this->chave, '=', $valor )->first();
                        }
                        
                        if ($objeto) {
                            $descricao = $objeto->renderiza($this->mascara);
                            $itens[$valor] = $descricao;
                        }
                    }
                }
                Transacao::fecha();
                
                parent::adicItens( $itens );
            }
            parent::defValor( $valores_originais );
        }
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost() {
        $ini = ConfigAplicativo::obt();
        
        if (isset($_POST[$this->nome])) {
            $valores = $_POST[$this->nome];
            
            if (isset($ini['general']['compat']) AND $ini['general']['compat'] ==  '4') {
                $retorno = [];
                if (is_array($valores)) {
                    Transacao::abre($this->bancodados);
                    foreach ($valores as $valor) {
                        if ($valor) {
                            $modelo = $this->modelo;
                            $pk = constant("{$modelo}::PRIMARYKEY");

                            // key is the primary key (default)
                            if ($pk === $this->chave) {
                                // use find because it uses cache
                                $objeto = $modelo::find( $valor );
                            
                            } else { // key is an alternative key (uses where->first)
                                $objeto = $modelo::where( $this->chave, '=', $valor )->first();
                            }
                            
                            if ($objeto) {
                                $descricao = $objeto->render($this->mascara);
                                $retorno[$valor] = $descricao;
                            }
                        }
                    }
                }
                return $retorno;
            } else {
                if (empty($this->separador)) {
                    return $valores;
                } else {
                    return implode($this->separador, $valores);
                }
            }
        } else {
            return '';
        }
    }
    
    /**
     * Shows the widget
     */
    public function exibe()
    {
        // define the tag properties
        $this->tag->{'id'}    = $this->id; // tag id
        
        // may be defined by child classes
        if (empty($this->tag->{'name'})) {
            $this->tag->{'name'}  = $this->nome.'[]';  // tag name
        }
        
        if (strstr($this->tamanho, '%') !== FALSE) {
            $this->defPropriedade('style', "width:{$this->tamanho};", false); //aggregate style info
            $tamanho  = "{$this->tamanho}";
        } else {
            $this->defPropriedade('style', "width:{$this->tamanho}px;", false); //aggregate style info
            $tamanho  = "{$this->tamanho}px";
        }
        
        $multiplo = $this->tamanhoMax == 1 ? 'false' : 'true';
        $ordemColuna = isset($this->ordemColuna) ? $this->ordemColuna : $this->coluna;
        $criterio = '';
        if ($this->criterio) {
            $criterio = str_replace(array('+', '/'), array('-', '_'), base64_encode(serialize($this->criterio)));
        }
        
        $hash = md5("{$this->seed}{$this->bancodados}{$this->chave}{$this->coluna}{$this->modelo}");
        $comprimento = $this->tamnhoMin;
        
        $classe = $this->servico;
        $callback = array($classe, 'onSearch');
        $metodo = $callback[1];
        $id_search_string_busca = $this->idBusca ? '1' : '0';
        $id_texto_busca = $this->idTextoBusca ? '1' : '0';
        $busca_palavra = !empty($this->getProperty('placeholder'))? $this->obtPropriedade('placeholder') : 'Busca';
        $url = "engine.php?class={$classe}&method={$metodo}&static=1&bancodados={$this->bancodados}&key={$this->chave}&coluna={$this->coluna}&model={$this->modelo}&ordemColuna={$ordemColuna}&criterio={$criterio}&operador={$this->operador}&mascara={$this->mascara}&idsearch={$id_search_string_busca}&idtextsearch={$id_texto_busca}&minlength={$comprimento}";
        $muda_acao = 'function() {}';
        
        if (isset($this->mudaAcao)) {
            if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form) {
                throw new Exception("Você deve passar a {__CLASS__} ({$this->nome}) como parâmetro para Form::defCampos()");
            }
            
            $string_acao = $this->mudaAcao->serializa(FALSE);
            $muda_acao = "function() { __adianti_post_lookup('{$this->nomeForm}', '{$string_acao}', '{$this->id}', 'callback'); }";
            $this->defPropriedade('changeaction', "__adianti_post_lookup('{$this->nomeForm}', '{$string_acao}', '{$this->id}', 'callback')");
        } else if (isset($this->mudaFuncao)) {
            $muda_acao = "function() { $this->mudaFuncao }";
            $this->defPropriedade('changeaction', $this->mudaFuncao, FALSE);
        }
        
        // exibes the component
        parent::renderizaItems( false );
        $this->tag->exibe();
        
        Script::cria(" tdbmultisearch_start( '{$this->id}', '{$comprimento}', '{$this->tamanhoMax}', '{$busca_palavra}', $multiplo, '{$url}', '{$tamanho}', '{$this->altura}px', '{$hash}', {$muda_acao} ); ");
        
        if (!$this->editavel)
        {
            Script::cria(" tmultisearch_disable_field( '{$this->nomeForm}', '{$this->nome}'); ");
        }
    }
}
