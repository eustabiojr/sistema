<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Form\InterfaceElementoForm;

/**
 * Class Abstrata Campo
 */
abstract class Campo implements InterfaceElementoForm
{
    # Propriedades
    protected $nome;
    protected $tamanho;
    protected $valor;
    protected $editavel;
    protected $tag;
    protected $rotuloForm;
    protected $propriedades;
    private   $validacoes;

    /**
     * Método Construtor
     */
    public function __construct($nome)
    {
        # Talvez seja melhor tornar estes métodos estáticos
        $this->defEditavel(true);
        $this->defNome($nome);
    }

    /**
     * Método defPropriedade
     */
    public function  defPropriedade($nome, $valor)
    {
        $this->propriedades[$nome] = $valor;
    }

    /**
     * Método obtPropriedade
     */
    public function  obtPropriedade($nome)
    {
        return $this->propriedades[$nome];
    }

    /**
     * Método __set
     */
    public function __set($nome, $valor)
    {
        /**
         * Valores escalares são os seguintes: inteiros, float, string ou booleano, 
         * Arrays, objetos e resource (recurso) não são valores escalares.
         */
        if (is_scalar($valor)) {
            $this->defPropriedade($nome, $valor);
        }
    }

    /**
     * Método __get
     */
    public function __get($nome)
    {
        return $this->obtPropriedade($nome);
    }

    /**
     * Método defNome
     */
    public function defNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Método obtNome
     */
    public function obtNome()
    {
        return $this->nome;
    }

    /**
     * Método defRotulo
     */
    public function defRotulo($rotulo)
    {
        $this->rotuloForm = $rotulo;
    }

    /**
     * Método obtRotulo
     */
    public function obtRotulo()
    {
        return $this->rotuloForm;
    }

    /**
     * Método defClasseRotulo
     */
    public function defClasseRotulo($classe_rotulo)
    {
        $this->classe_rotulo = $classe_rotulo;
    }

    /**
     * Método obtClasseRotulo
     */
    public function obtClasseRotulo()
    {
        return $this->classe_rotulo;
    }

    /**
     * Método defValor
     */
    public function defValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Método obtValor
     */
    public function obtValor()
    {
        return $this->valor;
    }

    /**
     * Método defEditavel
     */
    public function defEditavel($editavel)
    {
        $this->editavel = $editavel;
    }

    /**
     * Método obtEditavel
     */
    public function obtEditavel()
    {
        return $this->editavel;
    }

    /**
     * Método defTamanho
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->tamanho = $largura;
    }
}