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
use Estrutura\Controle\Acao;

use Exception;

/**
 * Classe GrupoCheck 
 */
class GrupoVerifica extends Campo implements InterfaceBugiganga
{
    private $esboco = 'vertical';
    private $mudaAcao;
    private $itens;
    private $quebraItens;
    private $botoes;
    private $rotulos;
    private $todosItensVerificados; 
    protected $separador;
    protected $mudaFuncao;
    protected $nomeForm;
    protected $classeRotulo;
    protected $usaBotao;
    protected $valor;
    
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
                $botao = new BotaoVerifica("{$this->nome}[]");
                $botao->defPropriedade('checkgroup', $this->nome);
                $botao->defValorIndice($chave);
                $botao->defPropriedade('onchange', $botoes_antigos[$chave]->getProperty('onchange'));
                
                $obj = new Rotulo($valor);
                $this->botoes[$chave] = $botao;
                $this->rotulos[$chave] = $obj;
            }
        }
    }
    
    /**
     * Check all options
     */
    public function verificaTodos()
    {
        $this->todosItensVerificados = TRUE;
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
     * Define after how much items, it will break
     */
    public function defQuebraItens($quebraItens)
    {
        $this->quebraItens = $quebraItens;
    }
    
    /**
     * Show as button
     */
    public function quebraItens() 
    {
       $this->classeRotulo = 'btn btn-default ';
       $this->usaBotao  = TRUE;
    }
    
    /**
     * Add items to the check group
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
                $botao = new BotaoVerifica("{$this->nome}[]");
                $botao->defPropriedade('checkgroup', $this->nome);
                $botao->defValorIndice($chave); 

                $obj = new Rotulo($valor);
                $this->botoes[$chave] = $botao;
                $this->rotulos[$chave] = $obj;
            }
        }
    }
    
    /**
     * Return the items
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
     * Define the field's separator
     * @param $sep A string containing the field's separator
     */
    public function defValorSeparador($sep) 
    {
        $this->separador = $sep;
    }
    
    /**
     * Define the field's value
     * @param $valor A string containing the field's value
     */
    public function defValor($valor)
    {
        if (empty($this->separador))
        {
            $this->valor = $valor;
        }
        else
        {
            if ($valor)
            {
                $this->valor = explode($this->separador, $valor);
            }
        }
    }
    
    /**
     * Return the post data
     */
    public function obtDadosPost()
    {
        if (isset($_POST[$this->nome]))
        {
            if (empty($this->separador))
            {
                return $_POST[$this->nome];
            }
            else
            {
                return implode($this->separador, $_POST[$this->nome]);
            }
        }
        else
        {
            return array();
        }
    }
    
    /**
     * Define the action to be executed when the user changes the combo
     * @param $acao TAction object
     */
    public function setChangeAction(Acao $acao)
    {
        if ($acao->ehEstatico())
        {
            $this->mudaAcao = $acao;
        }
        else
        {
            $acao_string = $acao->paraString();
            throw new Exception("Ação ({$acao_string}) deve estática para ser usada em {__METHOD__}");
        }
    }
    
    /**
     * Set change function
     */
    public function defMudaFuncao($funcao) 
    {
        $this->mudaFuncao = $funcao;
    }
    
    /**
     * Enable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tcheckgroup_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Disable the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tcheckgroup_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * clear the field
     * @param $nome_form Form name
     * @param $campo Field name
     */
    public static function apagarCampo($nome_form, $campo)
    {
        Script::cria( " tcheckgroup_clear_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Shows the widget at the screen
     */
    public function exibe()
    {
        if ($this->usaBotao)
        {
            echo '<div '.$this->getPropertiesAsString('aria').' data-toggle="botoes">';
            echo '<div class="btn-group" style="clear:both;float:left;display:table">';
        }
        else
        {
            echo '<div '.$this->getPropertiesAsString('aria').' role="group">';
        }
        
        if ($this->itens)
        {
            // iterate the checkgroup options
            $i = 0;
            foreach ($this->itens as $indice => $rotulo)
            {
                $botao = $this->botoes[$indice];
                $botao->setName($this->nome.'[]');
                $ativo = FALSE;
                $id = $botao->obtId();
                
                // verify if the checkbutton is checked
                if ((@in_array($indice, $this->valor) AND !(is_null($this->valor))) OR $this->todosItensVerificados)
                {
                    $botao->defValor($indice); // value=indexvalue (checked)
                    $ativo = TRUE;
                }
                
                // create the label for the button
                $obj = $this->rotulos[$indice];
                $obj->{'class'} = $this->classeRotulo . ($ativo?'active':'');
                $obj->setTip($this->tag->title);
                
                if ($this->obtTamanho() AND !$obj->obtTamanho())
                {
                    $obj->defTamanho($this->obtTamanho());
                }
                
                // check whether the widget is non-editable
                if (parent::obtEditavel())
                {
                    if (isset($this->mudaAcao))
                    {
                        if (!Form::obtFormPeloNome($this->nomeForm) instanceof Form)
                        {
                            throw new Exception('Você deve passer a {__CLASS__} ({$this->nome}) como um parâmetro para Form::defCampos()');
                        }
                        $acao_string = $this->mudaAcao->serialize(FALSE);
                        
                        $botao->defPropriedade('changeaction', "__adianti_post_lookup('{$this->nomeForm}', '{$acao_string}', '{$id}', 'callback')");
                        $botao->defPropriedade('onChange', $botao->obtPropriedade('changeaction'), FALSE);
                    }
                    
                    if (isset($this->mudaFuncao))
                    {
                        $botao->defPropriedade('changeaction', $this->mudaFuncao, FALSE);
                        $botao->defPropriedade('onChange', $this->mudaFuncao, FALSE);
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
                    
                    $embrulho = new Elemento('div');
                    $embrulho->{'style'} = 'display:inline-flex;align-items:center;';
                    $embrulho->adic($botao);
                    $embrulho->adic($obj);
                    $embrulho->exibe();
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
