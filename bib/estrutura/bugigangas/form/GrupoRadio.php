<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * A group of RadioButton's
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class GrupoRadio extends Campo implements InterfaceBugiganga
{
    private $esboco = 'vertical';
    private $acaoMuda;
    private $itens;
    private $quebraItens;
    private $botoes;
    private $rotulos;
    private $aparencia;
    protected $funcaoMuda;
    protected $nomeFor;
    protected $classeRotulo;
    protected $usaBotao;
    protected $eh_booleano;
    
    /**
     * Class Constructor
     * @param  $nome name of the field
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        parent::defTamanho(NULL);
        $this->classeRotulo = 'tcheckgroup_label ';
        $this->usaBotao  = FALSE;
        $this->eh_booleano = FALSE;
    }
    
    /**
     * Clone object
     */
    public function __clone()
    {
        if (is_array($this->itens))
        {
            $botoes_antigos = $this->botoes;
            $this->botoes = array();
            $this->rotulos  = array();

            foreach ($this->itens as $chave => $valor)
            {
                $botao = new BotaoRadio($this->nome);
                $botao->defValor($chave);
                $botao->defPropriedade('onchange', $botoes_antigos[$chave]->obtPropriedade('onchange'));
                
                $obj = new Rotulo($valor);
                $this->botoes[$chave] = $botao;
                $this->rotulos[$chave] = $obj;
            }
        }
    }
    
    /**
     * Enable/disable boolean mode
     */
    public function defModoBooleano()
    {
        $this->eh_booleano = true;
        $this->adicItens( [ '1' => 'Sim',
                           '2' => 'Não'] );
        $this->defEsboco('horizontal');
        $this->defUsaBotao();
        
        // if defValor() was called previously
        if ($this->valor === true)
        {
            $this->valor = '1';
        }
        else if ($this->valor === false)
        {
            $this->valor = '2';
        }
    }
    
    /**
     * Define the field's value
     * @param $valor A string containing the field's value
     */
    public function defValor($valor)
    {
        if ($this->eh_booleano)
        {
            $this->valor = $valor ? '1' : '2';
        }
        else
        {
            parent::defValor($valor);
        }
    }
    
    /**
     * Returns the field's value
     */
    public function obtValor()
    {
        if ($this->eh_booleano)
        {
            return $this->valor == '1' ? true : false;
        }
        else
        {
            return parent::obtValor();
        }
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost()
    {
        if ($this->eh_booleano)
        {
            $dados = parent::obtDadosPost();
            return $dados == '1' ? true : false;
        }
        else
        {
            return parent::obtDadosPost();
        }
    }
    
    /**
     * Define the direction of the options
     * @param $direction String (vertical, horizontal)
     */
    public function defEsboco($dir)
    {
        $this->esboco = $dir;
    }
    
    /**
     * Get the direction (vertical or horizontal)
     */
    public function obtEsboco()
    {
        return $this->esboco;
    }
    
    /**
     * Define after how much itens, it will break
     */
    public function defQuebraItens($quebraItens) 
    {
        $this->quebraItens = $quebraItens;
    }
    
    /**
     * Show as button
     */
    public function defUsaBotao()
    {
       $this->classeRotulo = 'btn btn-default ';
       $this->usaBotao  = TRUE;
    }
    
    /**
     * Add itens to the radio group
     * @param $itens An indexed array containing the options
     */
    public function adicItens($itens)
    {
        if (is_array($itens))
        {
            $this->itens = $itens;
            $this->botoes = array();
            $this->rotulos  = array();

            foreach ($itens as $chave => $valor)
            {
                $botao = new BotaoRadio($this->nome);
                $botao->defValor($chave);

                $obj = new Rotulo($valor);
                $this->botoes[$chave] = $botao;
                $this->rotulos[$chave] = $obj;
            }
        }
    }
    
    /**
     * Return the itens
     */
    public function obtItens()
    {
        return $this->itens;
    }
    
    /**
     * Return the option botoes
     */
    public function obtBotoes()
    {
        return $this->botoes;
    }

    /**
     * Return the option rotulos
     */
    public function obtRotulos()
    {
        return $this->rotulos;
    }
    
    /**
     * Define the action to be executed when the user changes the combo
     * @param $acao Acao object
     */
    public function defAcaoMuda(Acao $acao) 
    {
        if ($acao->ehEstatico())
        {
            $this->acaoMuda = $acao;
        }
        else
        {
            $string_acao = $acao->paraString();
            throw new Exception(NucleoTradutor::traduz('A ação (&1) deve ser estática para ser usado em &2', $string_acao, __METHOD__));
        }
    }
    
    /**
     * Set change function
     */
    public function defFuncaoMuda($funcao) 
    {
        $this->funcaoMuda = $funcao;
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tradiogroup_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tradiogroup_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * clear the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function limpaCampo($nome_form, $campo)
    {
        Script::cria( " tradiogroup_clear_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Show the widget at the screen
     */
    public function exibe()
    {
        if ($this->usaBotao)
        {
            echo '<div '.$this->obtPropriedadesComoString('aria').' data-toggle="botoes">';
            
            if (strpos($this->obtTamanho(), '%') !== FALSE)
            {
                echo '<div class="btn-group" style="clear:both;float:left;width:100%;display:table" role="group">';
            }
            else
            {
                echo '<div class="btn-group" style="clear:both;float:left;display:table" role="group">';
            }
        }
        else
        {
            echo '<div '.$this->getPropertiesAsString('aria').' role="group">';
        }
        
        if ($this->itens)
        {
            // iterate the RadioButton options
            $i = 0;
            foreach ($this->itens as $indice => $rotulo)
            {
                $botao = $this->botoes[$indice];
                $botao->defNome($this->nome);
                $ativo = FALSE;
                $id = $botao->obtId();
                
                // check if contains any value
                if ( $this->valor == $indice AND !(is_null($this->valor)) AND strlen((string) $this->valor) > 0)
                {
                    // mark as checked
                    $botao->defPropriedade('checked', '1');
                    $ativo = TRUE;
                }
                
                // create the label for the button
                $obj = $this->rotulos[$indice];
                $obj->{'class'} = $this->classeRotulo. ($ativo?'active':'');
                
                if ($this->obtTamanho() AND !$obj->obtTamanho())
                {
                    $obj->defTamanho($this->obtTamanho());
                }
                
                if ($this->obtTamanho() AND $this->usaBotao)
                {
                    if (strpos($this->obtTamanho(), '%') !== FALSE)
                    {
                        $size = str_replace('%', '', $this->obtTamanho());
                        $obj->defTamanho( ($size / count($this->itens)) . '%');
                    }
                    else
                    {
                        $obj->defTamanho($this->obtTamanho());
                    }
                }
                
                // check whether the widget is non-editable
                if (parent::obtEditavel())
                {
                    if (isset($this->acaoMuda))
                    {
                        if (!Form::obtFormPeloNome($this->nomeFor) instanceof Form)
                        {
                            throw new Exception(NucleoTradutor::traduz('Você deve passar o &1 (&2) como parâmetro para &3', __CLASS__, $this->nome, 'Form::defCampos()'));
                        }
                        $string_acao = $this->acaoMuda->serialize(FALSE);
                        
                        $botao->defPropriedade('changeaction', "__adianti_post_lookup('{$this->nomeFor}', '{$string_acao}', '{$id}', 'callback')");
                        $botao->defPropriedade('onChange', $botao->obtPropriedade('changeaction'), FALSE);
                    }
                    
                    if (isset($this->funcaoMuda))
                    {
                        $botao->defPropriedade('changeaction', $this->funcaoMuda, FALSE);
                        $botao->defPropriedade('onChange', $this->funcaoMuda, FALSE);
                    }
                }
                else
                {
                    $botao->defEditavel(FALSE);
                    $obj->defCorFonte('gray'); 
                }
                
                if ($this->usaBotao)
                {
                    $obj->adic($botao);
                    $obj->exibe();
                }
                else
                {
                    $botao->defPropriedade('class', 'filled-in');
                    $obj->{'for'} = $botao->obtId();
                    
                    $wrapper = new Elemento('div');
                    $wrapper->{'style'} = 'display:inline-flex;align-itens:center;';
                    $wrapper->adic($botao);
                    $wrapper->adic($obj);
                    $wrapper->exibe();
                }
                
                $i ++;
                
                if ($this->esboco == 'vertical' OR ($this->quebraItens == $i))
                {
                    $i = 0;
                    if ($this->usaBotao)
                    {
                       echo '</div>';
                       echo '<div class="btn-group" style="clear:both;float:left;display:table">';
                    }
                    else
                    {
                        // exibes a line break
                        $br = new Elemento('br');
                        $br->exibe();
                    }
                }
                echo "\n";
            }
        }
        
        if ($this->usaBotao)
        {
            echo '</div>';
            echo '</div>';
        }
        else
        {
            echo '</div>';
        }
    }
}
