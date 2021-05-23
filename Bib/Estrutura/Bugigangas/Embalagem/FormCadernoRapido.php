<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\Bugigangas\Recipiente\Caderno;
use Estrutura\Bugigangas\Recipiente\CaixaV;
use Estrutura\Bugigangas\Recipiente\Tabela;
use Estrutura\Controle\Acao;

/**
 * Create quick forms with a caderno wrapper
 *
 * @version    7.1
 * @package    widget
 * @subpackage wrapper
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormCadernoRapido extends FormRapido
{
    protected $caderno;
    protected $tabela;
    protected $caixa_vertical;
    
    /**
     * Class Constructor
     * @param $nome Form Name
     */
    public function __construct($nome = 'meu_form')
    {
        parent::__construct($nome);
        
        $this->caixa_vertical = new CaixaV;
        $this->caixa_vertical->{'style'} = 'width: 100%';
        $this->caderno = new Caderno();
        $this->possuiAcao = FALSE;
        
        $this->fieldsByRow = 1;
    }
    
    /**
     * Set the caderno wrapper
     * @param $caderno Notebook wrapper
     */
    public function defEmbrulhoCaderno($caderno)
    {
        $this->caderno = $caderno;
    }
    
    /**
     * Add a form title
     * @param $titulo     Form title
     */
    public function setFormTitle($titulo)
    {
        parent::defTituloForm($titulo);
        $this->caixa_vertical->adic($this->tabela);
    }
    
    /**
     * Append a caderno page
     * @param $titulo     Page title
     * @param $cotnainer Page container
     */
    public function anexaPagina($titulo, $recipiente = NULL)
    {
        if (empty($recipiente))
        {
            $recipiente = new Tabela;
            $recipiente->{'width'} = '100%';
        }
        
        if ($this->caderno->obtContadorPagina() == 0)
        {
            $this->caixa_vertical->adic($this->caderno);
        }
        
        $this->tabela = $recipiente;
        $this->caderno->anexaPagina($titulo, $this->tabela);
        $this->fieldPositions = 0;
    }
    
    /**
     * Add a form action
     * @param $rotulo  Action Label
     * @param $acao Acao Object
     * @param $icone   Action Icon
     */
    public function adicAcaoRapida($rotulo, Acao $acao, $icone = 'fa:save')
    {
        $this->tabela = new Tabela;
        $this->tabela->{'width'} = '100%';
        $this->caixa_vertical->adic($this->tabela);
        
        parent::adicAcaoRapida($rotulo, $acao, $icone);
    }
    
    /**
     * Show the component
     */
    public function exibe()
    {
        $this->caderno->{'style'} = 'margin:10px';
        
        // add the tabela to the form
        parent::pacote($this->caixa_vertical);
        parent::exibe();
    }
}
