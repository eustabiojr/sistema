<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Controle\Acao;
use Exception;
use ReflectionClass;

/**
 * Record Lookup Widget: Creates a lookup field used to search values from associated entities
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BotaoBusca extends Entrada implements InterfaceBugiganga
{
    private $acao;
    private $usaEventoSaida;
    private $botao;
    private $tamanho_extra;
    protected $auxiliar;
    protected $id;
    protected $nomeForm;
    protected $nome;
    
    /**
     * Class Constructor
     * @param  $nome name of the field
     */
    public function __construct($nome, $icone = NULL)
    {
        parent::__construct($nome);
        $this->usaEventoSaida = TRUE;
        $this->defPropriedade('class', 'tfield tseekentry', TRUE);   // classe CSS
        $this->tamanho_extra = 24;
        $this->botao = self::criaBotao($this->nome, $icone);
    }
    
    /**
     * Create seek button object
     */
    public static function criaBotao($nome, $icone)
    {
        $imagem = new Imagem( $icone ? $icone : 'fa:search');
        $botao = new Elemento('span');
        $botao->{'class'} = 'btn btn-default tseekbutton';
        $botao->{'type'} = 'button';
        $botao->{'onmouseover'} = "style.cursor = 'pointer'";
        $botao->{'name'} = '_' . $nome . '_seek';
        $botao->{'for'} = $nome;
        $botao->{'onmouseout'}  = "style.cursor = 'default'";
        $botao->adic($imagem);
        
        return $botao;
    }
    
    /**
     * Returns a property value
     * @param $nome     Property Name
     */
    public function __get($nome)
    {
        if ($nome == 'button')
        {
            return $this->botao;
        }
        else
        {
            return parent::__get($nome);
        }
    }
    
    /**
     * Define it the out event will be fired
     */
    public function DefUsaEventoSaida($bool)
    {
        $this->usaEventoSaida = $bool;
    }
    
    /**
     * Define the action for the SeekButton
     * @param $acao Action taken when the user
     * clicks over the Seek Button (A Acao object)
     */
    public function defAcao(Acao $acao)
    {
        $this->acao = $acao;
    }
    
    /**
     * Return the action
     */
    public function obtAcao()
    {
        return $this->acao;
    }
    
    /**
     * Define an auxiliar field
     * @param $objeto any Campo object
     */
    public function defAuxiliar($objeto)
    {
        if (method_exists($objeto, 'show'))
        {
            $this->auxiliar = $objeto;
            $this->tamanho_extra *= 2;
            
            if ($objeto instanceof Campo)
            {
                $this->acao->defParametro('receive_field', $objeto->obtNome());
            }
        }
    }
    
    /**
     * Returns if has auxiliar field
     */
    public function possuiAuxiliar()
    {
        return !empty($this->auxiliar);
    }
    
    /**
     * Set extra size
     */
    public function defTamanhoExtra($tamanho_extra)
    {
        $this->tamanho_extra = $tamanho_extra;
    }
    
    /**
     * Returns extra size
     */
    public function obtTamanhoExtra()
    {
        return $this->tamanho_extra;
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tseekbutton_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tseekbutton_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Show the widget
     */
    public function exibe()
    {
        // check if it's not editable
        if (parent::obtEditavel())
        {
            if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form)
            {
                throw new Exception("Você deve passar {__CLASS__} ({$this->nome}) como um parâmetro para Form::setFields()");
            }
            
            $acao_serializada = '';
            if ($this->acao)
            {
                // get the action class name
                if (is_array($callback = $this->acao->obtAcao()))
                {
                    if (is_object($callback[0]))
                    {
                        $rc = new ReflectionClass($callback[0]);
                        $nomeclasse = $rc->getShortName();
                    }
                    else
                    {
                        $nomeclasse  = $callback[0];
                    }
                    
                    if ($this->usaEventoSaida)
                    {
                        $inst       = new $nomeclasse;
                        $acaoAjax = new Acao(array($inst, 'onSelect'));
                        
                        if (in_array($nomeclasse, array('TStandardSeek')))
                        {
                            $acaoAjax->defParametro('parent',  $this->acao->obtParametro('parent'));
                            $acaoAjax->defParametro('database',$this->acao->obtParametro('database'));
                            $acaoAjax->defParametro('model',   $this->acao->obtParametro('model'));
                            $acaoAjax->defParametro('display_field', $this->acao->obtParametro('display_field'));
                            $acaoAjax->defParametro('receive_key',   $this->acao->obtParametro('receive_key'));
                            $acaoAjax->defParametro('receive_field', $this->acao->obtParametro('receive_field'));
                            $acaoAjax->defParametro('criteria',      $this->acao->obtParametro('criteria'));
                            $acaoAjax->defParametro('mask',          $this->acao->obtParametro('mask'));
                            $acaoAjax->defParametro('operator',      $this->acao->obtParametro('operator') ? $this->acao->obtParametro('operator') : 'like');
                        }
                        else
                        {
                            if ($acaoParameters = $this->acao->obtParametros())
                            {
                                foreach ($acaoParameters as $chave => $valor) 
                                {
                                    $acaoAjax->defParametro($chave, $valor);
                                }                    		
                            }                    	                    
                        }
                        $acaoAjax->defParametro('nome_form',  $this->nomeForm);
                        
                        $acao_string = $acaoAjax->serializa(FALSE);
                        $this->defPropriedade('seekaction', "__adianti_post_lookup('{$this->nomeForm}', '{$acao_string}', '{$this->id}', 'callback')");
                        $this->defPropriedade('onBlur', $this->obtPropriedade('seekaction'), FALSE);
                    }
                }
                $this->acao->defParametro('nome_campo', $this->nome);
                $this->acao->defParametro('nome_form',  $this->nomeForm);
                $acao_serializada = $this->acao->serialize(FALSE);
            }
            
            $this->botao->{'onclick'} = "javascript:serialform=(\$('#{$this->nomeForm}').serialize());__adianti_append_page('engine.php?{$acao_serializada}&'+serialform)";
                  
            $embrulho = new Elemento('div');
            $embrulho->{'class'} = 'tseek-group';
            $embrulho->abre();
            parent::exibe();
            $this->botao->exibe();
            
            if ($this->auxiliar)
            {
                $this->auxiliar->exibe();
            }
            $embrulho->fecha();
        }
        else
        {
            parent::exibe();
        }
    }
}
