<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/

 # Espaço de nomes
 namespace Estrutura\Bugigangas\Gradedados;

use Estrutura\Controle\Acao;
/**
 * Classe ColunaGradedados
 */
class ColunaGradedados 
{
    private $nome;
    private $rotulo;
    private $alinhamento;
    private $largura;
    private $acao;
    private $acao_edita;
    private $transformador;
    private $propriedades;
    private $proriedadesDados;
    private $funcaoTotal;
    private $totalTransformado;
    
    /**
     * Class Constructor
     * @param  $nome  = Name of the column in the database
     * @param  $rotulo = Text rotulo that will be shown in the header
     * @param  $alinhamento = Column alinhamento (left, center, right)
     * @param  $largura = Column Width (pixels)
     */
    public function __construct($nome, $rotulo, $alinhamento, $largura = NULL)
    {
        $this->nome  = $nome;
        $this->rotulo = $rotulo;
        $this->alinhamento = $alinhamento;
        $this->largura = $largura;
        $this->propriedades = array();
        $this->proriedadesDados = array();
    }
    
    /**
     * Define column visibility
     */
    public function defVisibilidade($bool)
    {
        if ($bool)
        {
            $this->defPropriedade('style', '');
            $this->defPropriedadeDados('style', '');
        }
        else
        {
            $this->defPropriedade('style', 'display:none');
            $this->defPropriedadeDados('style', 'display:none');
        }
    }
    
    /**
     * Enable column auto hide
     */
    public function enableAutoHide($largura)
    {
        $this->defPropriedade('hiddable', $largura);
        $this->defPropriedadeDados('hiddable', $largura);
    }
    
    /**
     * Define a column header property
     * @param $nome  Property Name
     * @param $valor Property Value
     */
    public function defPropriedade($nome, $valor)
    {
        $this->propriedades[$nome] = $valor;
    }
    
    /**
     * Define a data property
     * @param $nome  Property Name
     * @param $valor Property Value
     */
    public function defPropriedadeDados($nome, $valor)
    {
        $this->proriedadesDados[$nome] = $valor;
    }
    
    /**
     * Return a column property
     * @param $nome  Property Name
     */
    public function obtProriedade($nome)
    {
        return $this->propriedades[$nome] ?? null;
    }
    
    /**
     * Return a data property
     * @param $nome  Property Name
     */
    public function obtProriedadeDados($nome)
    {
        return $this->proriedadesDados[$nome] ?? null;
    }
    
    /**
     * Return column propriedades
     */
    public function obtProriedades()
    {
        return $this->propriedades;
    }
    
    /**
     * Return data propriedades
     */
    public function obtProriedadesDados()
    {
        return $this->proriedadesDados;
    }
    
    /**
     * Intercepts whenever someones assign a new property's value
     * @param $nome     Property Name
     * @param $valor    Property Value
     */
    public function __set($nome, $valor)
    {
        // objects and arrays are not set as propriedades
        if (is_scalar($valor))
        {              
            // store the property's value
            $this->defPropriedade($nome, $valor);
        }
    }
    
    /**
     * Returns the database column's nome
     */
    public function obtNome()
    {
        return $this->nome;
    }
    
    /**
     * Returns the column's rotulo
     */
    public function obtRotulo()
    {
        return $this->rotulo;
    }
    
    /**
     * Set the column's rotulo
     * @param $rotulo column rotulo
     */
    public function defRotulo($rotulo)
    {
        $this->rotulo = $rotulo;
    }
    
    /**
     * Returns the column's alinhamento
     */
    public function obtAlinh()
    {
        return $this->alinhamento;
    }
    
    /**
     * Returns the column's largura
     */
    public function obtLargura()
    {
        return $this->largura;
    }
    
    /**
     * Define the action to be executed when
     * the user clicks over the column header
     * @param $acao     Acao object
     * @param $parametros Action parameters
     */
    public function defAcao(Acao $acao, $parametros = null)
    {
        $this->acao = $acao;
        
        if ($parametros)
        {
            $this->acao->defParametros($parametros);
        }
    }
    
    /**
     * Returns the action defined by set_action() method
     * @return the action to be executed when the
     * user clicks over the column header
     */
    public function obtAcao()
    {
        // verify if the column has an actions
        if ($this->acao)
        {
            return $this->acao;
        }
    }
    
    /**
     * Remove action
     */
    public function removeAcao()
    {
        $this->acao = null;
    }
    
    /**
     * Define the action to be executed when
     * the user clicks do edit the column
     * @param $acao   A GradeDadosAcao object
     */
    public function defAcaoEdita(GradeDadosAcao $acao_edita)
    {
        $this->acao_edita = $acao_edita;
    }
    
    /**
     * Returns the action defined by setEditAcao() method
     * @return the action to be executed when the
     * user clicks do edit the column
     */
    public function obtEditaAcao()
    {
        // verify if the column has an actions
        if ($this->acao_edita)
        {
            return $this->acao_edita;
        }
    }
    
    /**
     * Define a callback function to be applyed over the column's data
     * @param $callback  A function nome of a method of an object
     */
    public function defTransformador(Callable $callback)
    {
        $this->transformador = $callback;
    }

    /**
     * Returns the callback defined by the setTransformer()
     */
    public function obtTransformador()
    {
        return $this->transformador;
    }
    
    /**
     * Define a callback function to totalize column
     * @param $callback  A function nome of a method of an object
     * @param $aplica_transformador Apply transform function also in total
     */
    public function defFuncaoTotal(Callable $callback, $aplica_transformador = true)
    {
        $this->funcaoTotal = $callback;
        $this->totalTransformado = $aplica_transformador;
    }
    
    /**
     * Returns the callback defined by the setTotalFunction()
     */
    public function obtFuncaoTotal()
    {
        return $this->funcaoTotal;
    }
    
    /**
     * Is total transformed
     */
    public function totalTransformado()
    {
        return $this->totalTransformado;
    }
}