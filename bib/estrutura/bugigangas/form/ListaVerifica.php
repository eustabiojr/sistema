<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Ageunet\Validacao\ValidadorCampo;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Embrulho\EmbrulhoBootstrapGradedados;

/**
 * Checklist
 *
 * @version    7.1
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ListaVerifica implements InterfaceBugiganga
{
    protected $gradedados;
    protected $idColuna;
    protected $campos;
    protected $nomeForm;
    protected $nome;
    protected $valor;
    protected $validacoes;
    
    /**
     * Construct method
     */
    public function __construct($nome)
    {
        $this->gradedados = new EmbrulhoBootstrapGradedados(new Gradedados);
        $this->gradedados->{'style'} = 'width: 100%';
        $this->gradedados->{'widget'} = 'tchecklist';
        $this->gradedados->disableDefaultClick(); // important!
        
        $id = $this->gradedados->{'id'};
        
        $check = new BotaoVerifica('check_all_'.$id);
        $check->setIndexValue('on');
        $check->{'onclick'} = "tchecklist_select_all(this, '{$id}')";
        $check->{'style'} = 'cursor:pointer';
        $check->setProperty('class', 'filled-in');
        
        $rotulo = new Rotulo('');
        $rotulo->{'style'} = 'margin:0';
        $rotulo->{'class'} = 'checklist-label';
        $check->after($rotulo);
        $rotulo->{'for'} = $check->getId();
        
        
        $this->gradedados->adicColuna( new ColunaGradedados('check',   $check->getContents(),   'center',  '1%') );
        
        $this->defNome($nome);
        $this->valor = [];
        $this->campos = [];
    }
    
    /**
     * Define the field's nome
     * @param $nome   A string containing the field's nome
     */
    public function defNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Returns the field's nome
     */
    public function obtNome()
    {
        return $this->nome;
    }
    
    /**
     * Define the checklist selected ids
     * @param $valor 
     */
    public function defValor($valor)
    {
        $this->valor = $valor;
        $id_coluna = $this->idColuna;
        $items = $this->gradedados->obtItens();
        
        if ($items)
        {
            foreach ($items as $item)
            {
                if ($this->valor)
                {
                    if (in_array($item->$id_coluna, $this->valor))
                    {
                        $item->{'check'}->defValor('on');
                        
                        $posicao = $this->gradedados->obtIndiceLinha( $id_coluna, $item->$id_coluna );
                        if (is_int($posicao))
                        {
                            $linha = $this->gradedados->obtLinha($posicao);
                            $linha->{'className'} = 'selected';
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Returns the selected ids
     */
    public function obtValor()
    {
        return $this->valor;
    }
    
    /**
     * Define the Identification column
     */
    public function defIdColuna($nome)
    {
        $this->idColuna = $nome;
    }
    
    /**
     * Add list column
     * @param  $nome  = Name of the column in the database
     * @param  $rotulo = Text label that will be exiben in the header
     * @param  $alinh = Column align (left, center, right)
     * @param  $largura = Column Width (pixels)
     */
    public function adicColuna($nome, $rotulo, $alinh, $largura)
    {
        if (empty($this->idColuna))
        {
            $this->idColuna = $nome;
        }
        
        return $this->gradedados->adicColuna( new ColunaGradedados($nome, $rotulo, $alinh, $largura) );
    }
    
    /**
     * Add item
     */
    public function adicItem($objeto)
    {
        $id_coluna = $this->idColuna;
        $objeto->{'check'} = new BotaoVerifica('check_' . $this->nome . '_' . base64_encode($objeto->$id_coluna));
        $objeto->{'check'}->setIndexValue('on');
        $objeto->{'check'}->setProperty('class', 'filled-in');
        $objeto->{'check'}->{'style'} = 'cursor:pointer';
        
        $rotulo = new Rotulo('');
        $rotulo->{'style'} = 'margin:0';
        $rotulo->{'class'} = 'checklist-label';
        $objeto->{'check'}->after($rotulo);
        $rotulo->{'for'} = $objeto->{'check'}->getId();
        
        if (count($this->gradedados->obtItens()) == 0)
        {
            $this->gradedados->criaModelo();
        }
        
        $linha = $this->gradedados->adicItem($objeto);
        
        if (in_array($objeto->$id_coluna, $this->valor))
        {
            $objeto->{'check'}->defValor('on');
            $linha->{'className'} = 'selected';
        }
        
        $this->campos[] = $objeto->{'check'};
        
        $form = Form::obtFormPeloNome($this->nomeForm);
        if ($form)
        {
            $form->adicCampo($objeto->{'check'});
        }
    }
    
    /**
     * add Items
     */
    public function adicItems($objetos)
    {
        if ($objetos)
        {
            foreach ($objetos as $objeto)
            {
                $this->adicItem($objeto);
            }
        }
    }
    
    /**
     * Clear gradedados
     */
    public function limpa()
    {
        $this->gradedados->limpa();
    }
    
    /**
     * Get campos
     */
    public function obtCampos()
    {
        return $this->campos;
    }
    
    /**
     * Define the nome of the form to wich the field is attached
     * @param $nome    A string containing the nome of the form
     * @ignore-autocomplete on
     */
    public function defNomeForm($nome)
    {
        $this->nomeForm = $nome;
    }
    
    /**
     * Return the nome of the form to wich the field is attached
     */
    public function obtNomeForm()
    {
        return $this->nomeForm;
    }
    
    /**
     * Redirect calls to decorated objeto
     */
    public function __call($metodo, $parametros)
    {
        return call_user_func_array(array($this->gradedados, $metodo),$parametros);
    }
    
    /**
     * Get post data
     */
    public function obtDadosPost()
    {
        $valor = [];
        $items = $this->gradedados->obtItens();
        
        $id_coluna = $this->idColuna;
        if ($items)
        {
            foreach ($items as $item)
            {
                $field_nome = 'check_'.$this->nome . '_' . base64_encode($item->$id_coluna);
                
                if (!empty($_POST[$field_nome]) && $_POST[$field_nome] == 'on')
                {
                    $valor[] = $item->$id_coluna;
                }
            }
        }
        
        return $valor;
    }
    
    /**
     * Add a field validator
     * @param $rotulo Field nome
     * @param $validador TFieldValidator objeto
     * @param $parametros Aditional pa$parametros
     */
    public function adicValidacao($rotulo, ValidadorCampo $validador, $parametros = NULL)
    {
        $this->validacoes[] = array($rotulo, $validador, $parametros);
    }
    
    /**
     * Returns field validacoes
     */
    public function obtValidacoes()
    {
        return $this->validacoes;
    }
    
    /**
     * Validate a field
     */
    public function valida()
    {
        if ($this->validacoes)
        {
            foreach ($this->validacoes as $validacao)
            {
                $rotulo      = $validacao[0];
                $validador  = $validacao[1];
                $parametros = $validacao[2];
                
                $validador->valida($rotulo, $this->obtValor(), $parametros);
            }
        }
    }
    
    /**
     * Show checklist
     */
    public function exibe()
    {
        if (count($this->gradedados->obtItens()) == 0)
        {
            $this->gradedados->criaModelo();
        }
        
        $this->gradedados->exibe();
        
        $id = $this->gradedados->{'id'};
        Script::cria("tchecklist_row_enable_check('{$id}')");
    }
}
