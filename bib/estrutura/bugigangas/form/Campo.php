<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Nucleo\NucleoTradutor;
use Estrutura\Validacao\ValidadorCampo;
use Estrutura\Validacao\ValidadorComprimentoMax;
use Estrutura\Validacao\ValidadorComprimentoMin;
use Estrutura\Validacao\ValidadorEmail;
use Estrutura\Validacao\ValidadorObrigatorio;

use Exception;
use ReflectionClass;
use Closure;

/**
 * Class Abstrata Campo
 */
abstract class Campo
{
    # Propriedades
    protected $id;
    protected $nome;
    protected $tamanho;
    protected $valor;
    protected $editavel;
    protected $tag;
    protected $rotulo;
    protected $propriedades;
    private   $validacoes;

    /**
     * Método Construtor
     */
    public function __construct($nome)
    {
        $cr = new ReflectionClass($this);
        $nomeclasse = $cr->getShortName();

        if (empty($nome)) {
            throw new Exception(NucleoTradutor::traduz('O parâmetro (&1) de &2 construtor é necessário', $nome,$nomeclasse));
        }

        # Talvez seja melhor tornar estes métodos estáticos
        $this->defEditavel(true);
        $this->defNome($nome);

        # Inicializa array de validações
        $this->validacoes   = [];
        $this->propriedades = [];

        $this->tag = new Elemento('input');
        $this->tag->{'class'}  = 'campo';
        $this->tag->{'widget'} = strtolower($nomeclasse);
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
     * Retorna se a propriedade está definida
     * @param $nome Nome propriedade
     */
    public function __isset($nome)
    {
        return isset($this->tag->nome);
    }

    /**
     * Duplica o objeto
     */
    function __clone()
    {
        $this->tag = clone $this->tag;
    }

    /**
     * Redireciona a função call
     * 
     * @param $metodo Nome do método
     * @param $param Array de parâmetros
     * 
     * Nota: É por meio desse método que chamamos os objetos de validação
     */
    public function __call($metodo, $param)
    {
        if (method_exists($this->tag, $metodo)) {
            return call_user_func_array( array($this->tag, $metodo), $param);
        } else {
            throw new Exception(NucleoTradutor::traduz('Método &1 não encontrado', $metodo));
        }
    }

    /**
     * Define o callback para o método defValor
     */
    public function defValorCallback($callback) 
    {
        $this->valorCallback = $callback;
    }

    /**
     * Método defRotulo
     */
    public function defRotulo($rotulo)
    {
        $this->rotulo = $rotulo;
    }

    /**
     * Método obtRotulo
     */
    public function obtRotulo()
    {
        return $this->rotulo;
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
     * Define o ID do campo
     * @param $id Uma string contendo o id do campo
     */
    public function defId($id)
    {
        $this->id = $id;
    }

    /**
     * Obtém o ID do campo
     */
    public function obtId() : int
    {
        return $this->id;
    }

    /**
     * Método defValor
     */
    public function defValor($valor)
    {
        $this->valor = $valor;

        if (!empty($this->valorCallback) && ($this->valorCallback instanceof Closure))
        {
            $callback = $this->valorCallback;
            $callback($this, $valor);
        }
    }

    /**
     * Método obtValor
     */
    public function obtValor()
    {
        return $this->valor;
    }

    /**
     * Define o nome do formulário para o qual o campo está anexando
     * @param $nome Uma string contendo o nome do formulário
     * @ignore-autocomplete on
     */
    public function defNomeForm($nome)
    {
        $this->nomeForm = $nome;
    }

    /**
     * Retorna o nome do formulário para o qual o campo está anexando
     */
    public function obtNomeForm() 
    {
        return $this->nomeForm;
    }

    /**
     * Define a dica do campo
     * @param $nome Uma string contendo o dica do campo
     */
    public function defDica($dica)
    {
        $this->tag->{'title'} = $dica;
    }

    /**
     * Retorna os dados postados
     */
    public function obtDadosPost()
    {
        return $_POST[$this->nome] ?? '';
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
     * Define se o campo é editável
     * @param $editavel A booleano
     */
    public function obtEditavel() : bool
    {
        return $this->editavel;
    }

    /**
     * Método defPropriedade
     */
    public function defPropriedade($nome, $valor, $substitui = TRUE)
    {
        if ($substitui) {
            # Encarrega a propriedade de atribuição ao objeto composto
            $this->tag->$nome = $valor;
        } else {
            if ($this->tag->$nome) {
                # Encarrega a propriedade de atribuição ao objeto composto
                $this->tag->$nome = $this->tag->$nome . ';' . $valor;
            } else {
                # Encarrega a propriedade de atribuição ao objeto composto
                $this->tag->$nome = $valor;
            }
        }
        $this->propriedades[$nome] = $this->tag->$nome;
    }

    /**
     * Retorna a propriedade como uma string
     */
    public function obtPropriedadesComoString($filtro = null) : string
    {
        $conteudo = '';

        if ($this->propriedades) {
            foreach ($this->propriedades as $nome => $valor) {
                if (empty($filtro) || ($filtro && strpos($nome, $filtro) !== false)) {
                    $valor = str_replace('"', '&quot;', $valor);
                    $conteudo .= " {$nome}=\"{$valor}\"";
                }
            }
        }
        return $conteudo;
    }

    /**
     * Método obtPropriedade
     */
    public function  obtPropriedade($nome) : string
    {
        return $this->propriedades[$nome];
    }

    /**
     * Método defTamanho
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->tamanho = $largura;
    }

    /**
     * Método obtTamanho
     */
    public function obtTamanho()
    {
        return $this->tamanho;
    }

    /**
     * Adiciona validador de campo
     * @param $rotulo Nome do campo
     * @param $validador Objeto 
     * @param $parametros Parâmetros adicionais (array)
     */
    public function adicValidacao($rotulo, ValidadorCampo $validador, $parametros = NULL) 
    {
        $this->validacoes[] = array($rotulo, $validador, $parametros);

        if ($validador instanceof ValidadorObrigatorio) {
            $this->tag->{'required'} = '';
        }

        if ($validador instanceof ValidadorEmail) {
            $this->tag->{'type'} = 'email';
        }

        if ($validador instanceof ValidadorComprimentoMax) {
            $this->tag->{'minlength'} = $parametros[0];
        }

        if ($validador instanceof ValidadorComprimentoMin) {
            $this->tag->{'maxlength'} = $parametros[0];
        }
    }

    /**
     * Retorna validações de campo
     */
    public function obtValidacoes() : array
    {
        return $this->validacoes;
    }

    /**
     * Retorna se o campo é obrigatório
     */
    public function ehObrigatorio()
    {
        if ($this->validacoes) {
            foreach ($this->validacoes as $validacao) {
                $validador = $validacao[1];
                if ($validador instanceof ValidadorObrigatorio) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Valida o campo
     */
    public function valida()
    {
        if ($this->validacoes) {
            foreach ($this->validacoes as $validacao) {
                $rotulo     = $validacao[0];
                $validador  = $validacao[1];
                $parametros = $validacao[2];

                $validador->valida($rotulo, $this->obtValor(), $parametros);
            }
        }
    }

    /**
     * Retorna o elemento conteúdo como uma string
     */
    public function obtConteudos() : string
    {
        ob_start();
        $this->exibe();
        $conteudo = ob_get_contents();
        ob_end_clean();
        return $conteudo;
    }
}