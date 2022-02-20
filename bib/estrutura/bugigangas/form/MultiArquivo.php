<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\ConfigAplicativo;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * FileChooser widget
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Nataniel Rabaioli
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiArquivo extends Campo implements InterfaceBugiganga
{
    protected $id;
    protected $altura;
    protected $acaoCompleta;
    protected $classeUploader;
    protected $extensoes;
    protected $semente;
    protected $manipuladorArquivo; 
    protected $galeriaImagens;
    protected $larguraGaleria;
    protected $alturaGaleria; 
    protected $popover;
    protected $titulo_pop;
    protected $conteudo_pop;
    
    /**
     * Constructor method
     * @param $nome input name
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id = $this->nome . '_' . mt_rand(1000000000, 1999999999);
        // $this->altura = 25;
        $this->classeUploader = 'AdiantiUploaderService';
        $this->manipuladorArquivo = FALSE;
        
        $ini = ConfigAplicativo::obt();
        $this->semente = NOME_APLICATIVO . ( !empty($ini['general']['semente']) ? $ini['general']['semente'] : 's8dkld83kf73kf094' );
        $this->galeriaImagens = false;
        $this->popover = false;
    }
    
    /**
     * Enable image gallery view
     */
    public function habilitaGaleriaImagens($largura = null, $altura = 100) 
    {
        $this->galeriaImagens  = true;
        $this->larguraGaleria  = is_null($largura) ? 'unset' : $largura;
        $this->alturaGaleria = is_null($altura) ? 'unset' : $altura;
    }
    
    /**
     * Enable popover
     * @param $titulo Title
     * @param $conteudo Content
     */
    public function habilitaPopover($titulo = null, $conteudo = '')
    {
        $this->popover    = TRUE;
        $this->titulo_pop   = $titulo;
        $this->conteudo_pop = $conteudo;
    }
    
    /**
     * Define the service class for response
     */
    public function defServico($servico)
    {
        $this->classeUploader = $servico;
    }
    
    /**
     * Define the allowed extensoes
     */
    public function defExtensoesPermitidas($extensoes) 
    {
        $this->extensoes = $extensoes;
        $this->tag->{'accept'} = '.' . implode(',.', $extensoes);
    }
    
    /**
     * Define to file handling
     */
    public function habilitaManipuladorArquivo() 
    {
        $this->manipuladorArquivo = TRUE;
    }
    
    /**
     * Set field size
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->tamanho   = $largura;
    }
    
    /**
     * Set field height
     */
    public function defAltura($altura)
    {
        $this->altura = $altura;
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost()
    {
        $nome = str_replace(['[',']'], ['',''], $this->nome);
        
        if (isset($_POST[$nome]))
        {
            return $_POST[$nome];
        }
    }
    
    /**
     * Set field value
     */
    public function defValor($valor)
    {
        if ($this->manipuladorArquivo)
        {
            if (is_array($valor))
            {
                $novo_valor = [];
                
                foreach ($valor as $chave => $item)
                {
                    if (is_array($item))
                    {
                        $novo_valor[] = urlencode(json_encode($item));
                    }
                    else if (is_scalar($item) and (strpos($item, '%7B') === false))
                    {
                        if (!empty($item))
                        {
                            $novo_valor[] = urlencode(json_encode(['idFile'=>$chave,'fileName'=>$item]));
                        }
                    }
                    else
                    {
                        $objeto_valor = json_decode(urldecode($item));
                        
                        if (!empty($objeto_valor->{'delFile'}) AND $objeto_valor->{'delFile'} == $objeto_valor->{'fileName'})
                        {
                            $valor = '';
                        }
                        else
                        {
                            $novo_valor[] = $item;
                        }
                    }
                }
                $valor = $novo_valor;
            }
            
            parent::defValor($valor);
        }
        else
        {            
            parent::defValor($valor);
        }
    }
    
    /**
     * Show the widget at the screen
     */
    public function exibe()
    {
        // define the tag properties
        $this->tag->{'id'}        = $this->id;
        $this->tag->{'name'}      = 'file_' . $this->nome.'[]';  // tag name
        $this->tag->{'receiver'}  = $this->nome;  // tag name
        $this->tag->{'value'}     = $this->valor; // tag value
        $this->tag->{'type'}      = 'file';       // input type
        $this->tag->{'multiple'}  = '1';
        
        if ($this->tamanho)
        {
            $tamanho = (strstr($this->tamanho, '%') !== FALSE) ? $this->tamanho : "{$this->tamanho}px";
            $this->setProperty('style', "width:{$tamanho};", FALSE); //aggregate style info
        }
        
        if ($this->altura)
        {
            $altura = (strstr($this->altura, '%') !== FALSE) ? $this->altura : "{$this->altura}px";
            $this->setProperty('style', "height:{$altura}", FALSE); //aggregate style info
        }
        
        $acao_completa = "'undefined'";
        
        // verify if the widget is editable
        if (parent::getEditable())
        {
            if (isset($this->acaoCompleta))
            {
                if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form)
                {
                    throw new Exception(NucleoTradutor::traduz('Você deve passar o &1 (&2) como parâmetro para &3', __CLASS__, $this->nome, 'Form::defCampos()'));
                }
                $acao_string = $this->acaoCompleta->serialize(FALSE);
                
                $acao_completa = "function() { __adianti_post_lookup('{$this->nomeForm}', '{$acao_string}', '{$this->tag-> id}', 'callback'); }";
            }
        }
        else
        {
            // make the field read-only
            $this->tag->{'readonly'} = "1";
            $this->tag->{'type'}     = 'text';
            $this->tag->{'class'}    = 'tfield_disabled'; // CSS
        }
        
        $id_div = mt_rand(1000000000, 1999999999);
        
        $div = new Elemento('div');
        $div->{'id'}    = 'div_file_'.$id_div;
        
        foreach( (array)$this->valor as $val )
        {
            $nomeArquivoHd = new Oculto($this->nome.'[]');
            $nomeArquivoHd->defValor( $val );
            
            $div->adic( $nomeArquivoHd );
        }
                
        $div->adic( $this->tag );
        $div->exibe();
        
        if (empty($this->extensoes))
        {
            $acao = "engine.php?class={$this->classeUploader}";
        }
        else
        {
            $hash = md5("{$this->semente}{$this->nome}".base64_encode(serialize($this->extensoes)));
            $acao = "engine.php?class={$this->classeUploader}&name={$this->nome}&hash={$hash}&extensoes=".base64_encode(serialize($this->extensoes));
        }
        
        $manipuladorArquivo = $this->manipuladorArquivo ? '1' : '0';
        $galeriaImagens = json_encode(['enabled'=> $this->galeriaImagens ? '1' : '0', 'width' => $this->larguraGaleria, 'height' => $this->alturaGaleria]);
        $popover = json_encode(['enabled' => $this->popover ? '1' : '0', 'title' => $this->titulo_pop, 'content' => base64_encode($this->conteudo_pop)]);
        
        Script::cria(" tmultifile_start( '{$this->tag-> id}', '{$div-> id}', '{$acao}', {$acao_completa}, $manipuladorArquivo, '$galeriaImagens', '$popover');");
    }
    
    /**
     * Define the action to be executed when the user leaves the form field
     * @param $acao Acao object
     */
    function defAcaoCompleta(Acao $acao) 
    {
        if ($acao->ehEstatico())
        {
            $this->acaoCompleta = $acao;
        }
        else
        {
            $acao_string = $acao->paraString();
            throw new Exception("Ação ({$acao_string}) deve ser estático a ser usado em {__METHOD__}"); 
        }
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tmultifile_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tmultifile_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Clear the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function limparCampo($nome_form, $campo)
    {
        Script::cria( " tmultifile_clear_field('{$nome_form}', '{$campo}'); " );
    }
}