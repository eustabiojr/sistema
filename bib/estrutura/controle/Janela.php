<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 08/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Controle;

use Estrutura\Bugigangas\Recipiente\DialogoJS;

class Janela extends Pagina
{
    private $embrulho;
    
    public function __construct()
    {
        parent::__construct();
        $this->embrulho = new DialogoJS;
        $this->embrulho->defUsaBotaoOK(FALSE);
        $this->embrulho->defTitulo('');
        $this->embrulho->defTamanho(1000, 500);
        $this->embrulho->defModal(TRUE);
        $this->embrulho->{'widget'} = 'T'.'Window';
        parent::adic($this->embrulho);
    }
    
    /**
     * Returns ID
     */
    public function obtId()
    {
        return $this->embrulho->obtId();
    }
    
    /**
     * Create a window
     */
    public static function cria($titulo, $largura, $altura, $params = null)
    {
        $inst = new static($params);
        $inst->defEstaEmbalado(TRUE);
        $inst->defTitulo($titulo);
        $inst->defTamanho($largura, $altura);
        unset($inst->embrulho->{'widget'});
        return $inst;
    }
    
    /**
     * Remove padding
     */
    public function removeEspacamento()
    {
        $this->defPropriedade('class', 'window_modal');
    }
    
    /**
     * Remove titlebar
     */
    public function removeBarraTitulo()
    {
        $this->defClasseDialogo('sem-titulo');
    }
    
    /**
     * Set Dialog class
     * @param $classe Class name
     */
    public function defClasseDialogo($classe)
    {
        $this->embrulho->defClasseDialogo($classe);
    }
    
    /**
     * Define the stack order (zIndex)
     * @param $ordem Stack order
     */
    public function defOrdemPilha($ordem)
    {
        $this->embrulho->defOrdemPilha($ordem);
    }
    
    /**
     * Define the window's title
     * @param  $titulo Window's title
     */
    public function defTitulo($titulo)
    {
        $this->embrulho->defTitulo($titulo);
    }
    
    /**
     * Turn on/off modal
     * @param $modal Boolean
     */
    public function defModal($modal)
    {
        $this->embrulho->defModal($modal);
    }
    
    /**
     * Disable Escape
     */
    public function desabilitaEscapa()
    {
        $this->embrulho->desabilitaEscape();
    }
    
    /**
     * Disable scrolling
     */
    public function desabilitaRolagem()
    {
        $this->embrulho->desabilitaRolagem();
    }
    
    /**
     * Define the window's size
     * @param  $largura  Window's width
     * @param  $altura Window's height
     */
    public function defTamanho($largura, $altura)
    {
        $this->embrulho->defTamanho($largura, $altura);
    }
    
    /**
     * Define the window's min width between percent and absolute
     * @param  $porcentagem width
     * @param  $absoluto width
     */
    public function defLarguraMin($porcentagem, $absoluto)
    {
        $this->embrulho->defLarguraMin($porcentagem, $absoluto);
    }
    
    /**
     * Define the top corner positions
     * @param $x left coordinate
     * @param $y top  coordinate
     */
    public function defPosicao($x, $y)
    {
        $this->embrulho->defPosicao($x, $y);
    }
    
    /**
     * Define the Property value
     * @param $propriedade Property name
     * @param $valor Property value
     */
    public function defPropriedade($propriedade, $valor)
    {
        $this->embrulho->$propriedade = $valor;
    }
    
    /**
     * Add some content to the window
     * @param $conteudo Any object that implements the show() method
     */
    public function adic($conteudo)
    {
        $this->embrulho->adic($conteudo);
    }
    
    /**
     * set close action
     * @param $acao close action
     */
    public function defAcaoFechar(Acao $acao)
    {
        $this->embrulho->defAcaoFechar($acao);
    }
    
    /**
     * Close DialogoJS's
     */
    public static function fechaJanela($id = null)
    {
        if (!empty($id))
        {
            DialogoJS::fechaPeloId($id);
        }
        else
        {
            DialogoJS::fechaTudo();
        }
    }
}