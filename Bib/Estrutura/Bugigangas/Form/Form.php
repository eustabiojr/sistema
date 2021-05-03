<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Controle\InterfaceAcao;
use Exception;

/**
 * Class Form
 */
class Form 
{
    protected $titulo;
    protected $campos = array();
    protected $acoes;
    protected $itens_grupo;

    /**
     * Método __construct
     */
    public function __construct($nome = 'meu_form')
    {
        $this->defNome($nome);
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
     * Método defTitulo
     */
    public function defTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Método obtTitulo
     */
    public function obtTitulo()
    {
        return $this->titulo;
    }

    /**
     * Método adicCampo
     * 
     * Com a classe EmbalaForms os campos são gravados na classe ItensForm. Vou precisar
     * fazer uma adaptação aqui. 
     */
    public function adicCampo(InterfaceElementoForm $objeto_campo)
    {
        $nome = $objeto_campo->obtNome();
        if (isset($this->campos[$nome]) AND substr($nome, -2) !== '[]') {
            throw new Exception("Você já adicionou o campo {$nome} ao formulário");
        }
        # Esta propriedade precisa ser trabalhada.
        if ($nome) {
            $this->campos[$nome] = $objeto_campo;
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