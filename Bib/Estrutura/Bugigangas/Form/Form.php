<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\InterfaceAcao;
use Exception;
use ReflectionClass;

/**
 * Class Form
 */
class Form implements InterfaceElementoForm 
{
    protected $titulo;
    protected $campos;
    protected $filhos;
    protected $funcao_js;
    protected $elemento;
    protected $campos_silenciosos;
    static private $forms;

    protected $acoes;
    protected $itens_grupo;

    /**
     * Método __construct
     */
    public function __construct($nome = 'meu_form')
    {
        if ($nome) {
            $this->defNome($nome);
        }
        $this->filhos = [];
        $this->campos_silenciosos = [];
        $this->elemento = new Elemento('form');
    }

    /**
     * Intercepta sempre que alguém atribuir uma nova propriedade
     * 
     * @param $nome     Nome propriedade
     * @param $valor    Valor propriedade
     */
    public function __set($nome, $valor)
    {
        $cr = new ReflectionClass($this);
        $nomeclasse = $cr->getShortName();

        if (in_array($nomeclasse, array('Form', 'FormRapido'))) {
            # obbjetos e arrays não são definidos como propriedades
            if (is_scalar($valor)) {
                $this->elemento->$nome = $valor;
            }
        } else {
            $this->$nome = $valor;
        }
    }

    /**
     * Campo silencioso
     */
    public function campoSilencioso($nome) 
    {
        $this->campos_silenciosos[] = $nome;
    }

    /**
     * Intercepta sempre que alguém atribuir uma nova propriedade
     * 
     * @param $nome     Nome propriedade
     * @param $valor    Valor propriedade
     */
    public function defPropriedade($nome, $valor, $substitui = TRUE)
    {
        if ($substitui) {
            $this->elemento->$nome = $valor;
        } else {
            if ($this->elemento->$nome) {
                $this->elemento->$nome = $this->elemento->$nome ; ';' . $valor;
            } else {
                $this->elemento->$nome = $valor;
            }
        }
    }

    /**
     * Redefine propriedade de formulário
     */
    public function redefinePropriedade($nome)
    {
        unset($this->elemento->$nome);
    }

    /**
     * Retorna o objeto formulário pelo seu nome
     */
    public static function obtNomePeloNome($nome)
    {
        if (isset(self::$forms[$nome])) {
            return self::$forms[$nome];
        }
    }


    /**
     * Método defNome
     */
    public function defNome($nome)
    {
        $this->nome = $nome;

        # registra o nome do formulário
        self::$forms[$this->nome] = $this;
    }

    /**
     * Método obtNome
     */
    public function obtNome()
    {
        return $this->nome;
    }

    /**
     * Envia dados para o formulário localizado na janela pai
     * 
     * @param $nome_form    Nome do formulário
     * @param $objeto       Um objeto contendo dados de formulário
     */
    public static function enviaDados($nome_form, $objeto, $agrega = FALSE, $diparaEventos = TRUE, $expira = 0)
    {
        $param_dispara = $diparaEventos ? 'true' : 'false';

        if ($objeto) {
            foreach ($objeto as $campo => $valor) {
                if (is_array($valor)) {
                    $valor = implode('|', $valor);
                }

                $valor = addslashes($valor);
                # Filtragem que assegura de unicode
                $valor = str_replace(array("\n", "\r"), array( '\n', '\r'), $valor);
            }
        }
    }

    /**
     * Define se o formulário será editável
     * 
     * @param $bool Um booleano
     */
    public function defEditavel($bool)
    {
        if ($this->campos) {
            foreach ($this->campos as $objeto) {
                $objeto->defEditavel($bool);
            }
        }
    }


    /**
     * Método adicCampo
     * 
     * Com a classe EmbalaForms os campos são gravados na classe ItensForm. Vou precisar
     * fazer uma adaptação aqui. 
     */
    public function adicCampo(InterfaceBugiganga $objeto_campo)
    {
        $nome = $objeto_campo->obtNome();
        if (isset($this->campos[$nome]) AND substr($nome, -2) !== '[]') {
            throw new Exception("Você já adicionou o campo {$nome} ao formulário");
        }
        # Esta propriedade precisa ser trabalhada.
        if ($nome) {
            $this->campos[$nome] = $objeto_campo;
            $objeto_campo->defNomeForm($this->nome);
            
            if ($objeto_campo instanceof Botao) {
                $objeto_campo->adicFunction($this->funcao_js);
            }
        }
        
    }

    /**
     * Remove a form field
     * @param $field Object
     */
    public function apagCampo(InterfaceElementoForm $campo)
    {
        if ($this->campos) {
            foreach($this->campos as $nome => $objeto) {
                if ($campo === $objeto) {
                    unset($this->campos[$nome]);
                }
            }
        }
    }
    
    /**
     * Remove todos os campos formulário
     */
    public function apagCampos()
    {
        $this->campos = array();
    }

    /**
     * Método obtCampos
     */
    public function obtCampo($nome) 
    {
        if (isset($this->campos[$nome]))
        return $this->campos[$nome];
    }

    /**
     * Método obtCampos
     */
    public function obtCampos() 
    {
        return $this->campos;
    }

    /**
     * limpa os dados do formulário
     */
    public function limpa($mantemPadroes = FALSE)
    {
        // itera os campos do formulário
        foreach ($this->campos as $nome => $campo)
        {
            // os rótulos não tem nome
            if ($nome AND !$mantemPadroes)
            {
                $campo->defValor(NULL);
            }
        }
    }

    //--------------------------------------------------------------------------------------------------------------------- 
    /**
     * Método valida
     * 
     * Este método está aqui apenas para estudo de como implementar validação
     * de formulário (pelo menos por enquanto).
     */
    public function valida() {
        # Atribui os dados post antes da validação
        # a exceção de validação deveria impedir
        # que o código do usuário execute defDados()
        $this->defDados($this->obtDados());

        $erros = array();
        foreach ($this->campos as $objetoCampo) {
            try {
                $objetoCampo->valida();
            } catch (Exception $e) {
                $erros[] = $e->getMessage() . '.';
            }
        }

        if (count($erros) > 0) {
            throw new Exception(implode("<br>", $erros));
        }
    }
    //--------------------------------------------------------------------------------------------------------------------- 

    /**
     * Método adicItensGrupo
     */
    public function adicItensGrupo(ItensForm $itens_grupo)
    {
        $this->itens_grupo[$itens_grupo->obtNomeAba()] = $itens_grupo;

        $this->recuperaCampos();
    }

    /**
     * Método obtItensGrupo
     */
    public function obtItensGrupo()
    {
        return $this->itens_grupo;
    }

    /**
     * Método recuperaCampos
     * 
     * Aqui nós pegamos os campos em ItensForm, e passamos para o Form
     */
    public function recuperaCampos()
    {
        foreach ($this->obtItensGrupo() as $obj_grupos) {

            $objetos_grupos = $obj_grupos->grupo_campos;

            foreach($objetos_grupos as $indice2 => $obt_itens) {
                #echo "Indice2: " . $indice2 . "<br>\n";
                unset($obt_itens[1]);
                $this->campos[$indice2] = $obt_itens[0];
            }
        }
    }

    /**
     * Método adicAcao
     */
    public function adicAcao($rotulo, InterfaceAcao $acao)
    {
        $this->acoes[$rotulo] = $acao;
    }

    /**
     * Método obtAcoes
     */
    public function obtAcoes()
    {
        return $this->acoes;
    }

    /**
     * Método defDados
     */
    public function defDados($objeto)
    {
        foreach($this->campos as $nome => $campo) {
            if ($nome AND isset($objeto->$nome)) {
                $campo->defValor($objeto->$nome);
            }
        }
    }

    /**
     * Método obtDados
     */
    public function obtDados($classe = 'stdClass')
    {
        $objeto = new $classe;

        /**
         * Aqui pegamos os campos, e o transformamos em propriedade e seu respectivo valor
         * em um objeto genérico.
         */
        foreach ($this->campos as $chave => $objetoCampo) {
            $val = $_POST[$chave] ?? '';
            $objeto->$chave = $val;
        }

        # percorre os arquivos de upload
        foreach($_FILES as $chave => $conteudo) {
            $objeto->$chave = $conteudo['nome_tmp'];
        }
        return $objeto;
    }
}