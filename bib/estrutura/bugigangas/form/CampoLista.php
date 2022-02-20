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
use Estrutura\Bugigangas\Recipiente\Tabela;
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * Create a field list
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CampoLista extends Tabela
{
    private $campos;
    private $rotulos;
    private $corpo_criado;
    private $detalhe_linha;
    private $remove_funcao;
    private $funcao_clone;
    private $acao_ordena;
    private $ordenacao;
    private $propriedades_campo;
    private $funcoes_linha;
    private $aria_automatica;
    private $resumir;
    private $totais;
    private $funcoes_total;
    
    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->{'id'}     = 'tfieldlist_' . mt_rand(1000000000, 1999999999);
        $this->{'class'}  = 'tfieldlist';
        
        $this->campos = [];
        $this->propriedades_campo = [];
        $this->funcoes_linha = [];
        $this->corpo_criado = false;
        $this->detalhe_linha = 0;
        $this->ordenacao = false;
        $this->aria_automatica = false;
        $this->remove_funcao = 'ttable_remove_row(this)';
        $this->funcao_clone  = 'ttable_clone_previous_row(this)';
        $this->resumir = false;
        $this->funcoes_total = null;
    }
    
    /**
     * Enable ordenacao
     */
    public function habilitaOrdenacao()
    {
        $this->ordenacao = true;
    }
    
    /**
     * Generate automatic aria-labels
     */
    public function geraAria()
    {
        $this->aria_automatica = true;
    }
    
    /**
     * Define the action to be executed when the user sort rows
     * @param $acao Acao object
     */
    public function defAcaoOrdena(Acao $acao)
    {
        if ($acao->ehEstatico())
        {
            $this->acao_ordena = $acao;
        }
        else
        {
            $string_acao = $acao->paraString();
            throw new Exception(NucleoTradutor::traduz('A ação (&1) deve ser estática para ser usado em &2', $string_acao, __METHOD__));
        }
    }
    
    /**
     * Set the remove javascript action
     */
    public function defFuncaoRemove($acao) 
    {
        $this->remove_funcao = $acao;
    }
    
    /**
     * Set the clone javascript action
     */
    public function defFuncaoClone($acao) 
    {
        $this->funcao_clone = $acao;
    }
    
    /**
     * Add function
     */
    public function adicFuncaoBotao($funcao, $icone, $titulo) 
    {
        $this->funcoes_linha[] = [$funcao, $icone, $titulo];
    }
    
    /**
     * Add a field
     * @param $rotulo  Field Label
     * @param $object Field Object
     */
    public function adicCampo($rotulo, InterfaceBugiganga $campo, $propriedades = null)
    {
        if ($campo instanceof Campo)
        {
            $nome = $campo->obtNome();
            
            if (isset($this->campos[$nome]) AND substr($nome,-2) !== '[]')
            {
                throw new Exception(NucleoTradutor::traduz('Você já adicionou um campo chamado "&1" ao formulário', $nome));

            }
            
            if ($nome)
            {
                $this->campos[$nome] = $campo;
                $this->propriedades_campo[$nome] = $propriedades;
            }
            
            if (isset($propriedades['sum']) && $propriedades['sum'] == true)
            {
                $this->resumir = true;
            }
            
            if ($rotulo instanceof Rotulo)
            {
                $campo_rotulo = $rotulo;
                $valor_rotulo = $rotulo->obtValor();
            }
            else
            {
                $campo_rotulo = new Rotulo($rotulo);
                $valor_rotulo = $rotulo;
            }
            
            $campo->defRotulo($valor_rotulo);
            $this->rotulos[$nome] = $campo_rotulo;
        }
    }
    
    /**
     * Add table header
     */
    public function addHeader()
    {
        $secao = parent::adicSecao('thead');
        
        if ($this->campos)
        {
            $linha = parent::adicLinha();
            
            if ($this->ordenacao)
            {
                $linha->adicCelula( '' );
            }
            
            foreach ($this->campos as $nome => $campo)
            {
                if ($campo instanceof Oculto)
                {
                    $celula = $linha->adicCelula( '' );
                    $celula->{'style'} = 'display:none';
                }
                else
                {
                    $celula = $linha->adicCelula( new Rotulo( $campo->obrRotulo() ) );
                    
                    if (!empty($this->propriedades_campo[$nome]))
                    {
                        foreach ($this->propriedades_campo[$nome] as $propriedade => $valor)
                        {
                            $celula->defPropriedade($propriedade, $valor);
                        }
                    }
                }
            }
            
            if ($this->funcoes_linha)
            {
                foreach ($this->funcoes_linha as $linha_function)
                {
                    $celula = $linha->adicCelula( '' );
                    $celula->{'style'} = 'display:none';
                }
            }
            
            // aligned with remove button
            $celula = $linha->adicCelula( '' );
            $celula->{'style'} = 'display:none';
        }
        
        return $secao;
    }
    
    /**
     * Add detail row
     * @param $item Data object
     */
    public function adicDetalhe( $item )
    {
        $uniqid = mt_rand(1000000, 9999999);
        
        if (!$this->corpo_criado)
        {
            parent::adicSecao('tbody');
            $this->corpo_criado = true;
        }
        
        if ($this->campos)
        {
            $linha = parent::adicLinha();
            $linha->{'id'} = $uniqid;
            
            if ($this->ordenacao)
            {
                $move = new Imagem('fas:arrows-alt gray');
                $move->{'class'} .= ' handle';
                $move->{'style'} .= ';font-size:100%;cursor:move';
                $linha->adicCelula( $move );
            }
            
            foreach ($this->campos as $campo)
            {
                $campo_name = $campo->obtNome();
                $nome  = str_replace( ['[', ']'], ['', ''], $campo->obtNome());
                
                if ($this->detalhe_linha == 0)
                {
                    $clone = $campo;
                }
                else
                {
                    $clone = clone $campo;
                }
                
                if (isset($this->propriedades_campo[$campo_name]['sum']) && $this->propriedades_campo[$campo_name]['sum'] == true)
                {
                    $campo->{'exitaction'} = "tfieldlist_update_sum('{$nome}', 'callback')";
                    $campo->{'onBlur'}     = "tfieldlist_update_sum('{$nome}', 'callback')";
                    
                    $this->funcoes_total .= $campo->{'exitaction'} . ';';
                    
                    $valor = isset($item->$nome) ? $item->$nome : 0;
                    
                    if (isset($campo->{'data-nmask'}))
                    {
                        $sep_dezena = substr($campo->{'data-nmask'},1,1);
                        $sep_milhar = substr($campo->{'data-nmask'},2,1);
                        $valor   = str_replace($sep_milhar, '', $valor);
                        $valor   = str_replace($sep_dezena, '.', $valor);
                    }
                    
                    if (isset($this->totais[$nome]))
                    {
                        $this->totais[$nome] += $valor;
                    }
                    else
                    {
                        $this->totais[$nome] = $valor;
                    }
                }
                
                if ($this->aria_automatica)
                {
                    $rotulo = $this->rotulos[ $campo->obtNome() ];
                    $aria_label = $rotulo->obtValor();
                    $campo->{'aria-label'} = $aria_label;
                }
                
                $clone->defined($nome.'_'.$uniqid);
                $clone->{'data-row'} = $this->detalhe_linha;
                
                $celula = $linha->adicCelula( $clone );
                $celula->{'class'} = 'field';
                
                if ($clone instanceof Oculto)
                {
                    $celula->{'style'} = 'display:none';
                }
                
                if (!empty($item->$nome) OR (isset($item->$nome) AND $item->$nome == '0'))
                {
                    $clone->defValor( $item->$nome );
                }
                else
                {
                    $clone->defValor( null );
                }
            }
            
            if ($this->funcoes_linha)
            {
                foreach ($this->funcoes_linha as $linha_function)
                {
                    $btn = new Elemento('div');
                    $btn->{'class'} = 'btn btn-default btn-sm';
                    //$btn->{'style'} = 'padding:3px 7px';
                    $btn->{'onclick'} = $linha_function[0];
                    $btn->{'title'} = $linha_function[2];
                    $btn->adic(new Imagem($linha_function[1]));
                    $linha->adicCelula( $btn );
                }
            }
            
            $del = new Elemento('div');
            $del->{'class'} = 'btn btn-default btn-sm';
            //$del->{'style'} = 'padding:3px 7px';
            $del->{'onclick'} = $this->funcoes_total . $this->remove_funcao;
            //------------------------------------------------------------------------------------------------------------------------------------
            $del->{'title'} = _t('Delete');
            $del->adic('<i class="fa fa-times red"></i>');
            $linha->adicCelula( $del );
        }
        $this->detalhe_linha ++;
        
        return $linha;
    }
    
    /**
     * Add clone action
     */
    public function adicAcaoClone() 
    {
        parent::adicSecao('tfoot');
        
        $linha = parent::adicLinha();
        
        if ($this->ordenacao)
        {
            $linha->adicCelula( '' );
        }
        
        if ($this->campos)
        {
            foreach ($this->campos as $campo)
            {
                $campo_name = $campo->obtNome();
                
                $celula = $linha->adicCelula('');
                if ($campo instanceof Oculto)
                {
                    $celula->{'style'} = 'display:none';
                }
                else if (isset($this->propriedades_campo[$campo_name]['sum']) && $this->propriedades_campo[$campo_name]['sum'] == true)
                {
                    $campo_name = str_replace('[]', '', $campo_name);
                    $grand_total = clone $campo;
                    $grand_total->defNome('grandtotal_'.$campo_name);
                    $grand_total->{'field_name'} = $campo_name;
                    $grand_total->defEditavel(FALSE);
                    $grand_total->{'style'}  .= ';font-weight:bold;border:0 !important;background:none';
                    
                    if (!empty($this->totais[$campo_name]))
                    {
                        $grand_total->defValor($this->totais[$campo_name]);
                    }
                    
                    $celula->adic($grand_total);
                }
            }
        }
        
        if ($this->funcoes_linha)
        {
            foreach ($this->funcoes_linha as $linha_function)
            {
                $celula = $linha->adicCelula('');
            }
        }
        
        $adic = new Elemento('div');
        $adic->{'class'} = 'btn btn-default btn-sm';
        //$adic->{'style'} = 'padding:3px 7px';
        $adic->{'onclick'} = $this->funcao_clone;
        $adic->adic('<i class="fa fa-plus green"></i>');
        
        // add buttons in table
        $linha->adicCelula($adic);
    }
    
    /**
     * Clear field list
     * @param $nome field list name
     */
    public static function limpar($nome)
    {
        Script::cria( "tfieldlist_clear('{$nome}');" );
    }
    
    /**
     * Clear some field list rows
     * @param $nome     field list name
     * @param $index    field list name
     * @param $quantity field list name
     */
    public static function limparLinhas($nome, $inicio = 0, $comprimento = 0)
    {
        Script::cria( "tfieldlist_clear_rows('{$nome}', {$inicio}, {$comprimento});" );
    }
    
    /**
     * Clear some field list rows
     * @param $nome     field list name
     * @param $index    field list name
     * @param $quantity field list name
     */
    public static function adicLinhas($nome, $linhas)
    {
        Script::cria( "tfieldlist_add_rows('{$nome}', {$linhas});" );
    }
    
    /**
     * Show component
     */
    public function exibe()
    {
        parent::exibe();
        $id = $this->{'id'};
        
        if ($this->ordenacao)
        {
            if (empty($this->acao_ordena))
            {
                Script::cria("ttable_sortable_rows('{$id}', '.handle')");
            }
            else
            {
                if (!empty($this->campos))
                {
                    $primeiro_campo = array_values($this->campos)[0];
                    $this->acao_ordena->setParameter('static', '1');
                    $nome_form   = $primeiro_campo->obtNomeForm();
                    $string_acao = $this->acao_ordena->serializa(FALSE);
                    $acao_ordena = "function() { __adianti_post_data('{$nome_form}', '{$string_acao}'); }";
                    Script::cria("ttable_sortable_rows('{$id}', '.handle', $acao_ordena)");
                }
            }
        }
    }
}
