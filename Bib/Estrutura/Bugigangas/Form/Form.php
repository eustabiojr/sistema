<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

use Estrutura\Controle\InterfaceAcao;

/**
 * Class Form
 */
class Form {
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
    public function adicCampo($rotulo, InterfaceElementoForm $objeto, $tamanho = '100%')
    {
        $objeto->defTamanho($tamanho);
        $objeto->defRotulo($rotulo);

        # Esta propriedade precisa ser trabalhada.
        $this->campos[$objeto->obtNome()] = $objeto;
    }

    /**
     * Método obtCampos
     */
    public function obtCampos() 
    {
        return $this->campos;
    }

    /**
     * Método adicItensGrupo
     */
    public function adicItensGrupo(ItensForm2 $itens_grupo)
    {
        $this->itens_grupo[$itens_grupo->obtNomeAba()] = $itens_grupo;
    }

    /**
     * Método obtItensGrupo
     */
    public function obtItensGrupo()
    {
        return $this->itens_grupo;
    }

    public function recuperaCampos2()
    {
        $r = $this->obtItensGrupo(); # recuperaCampos1
 
        foreach ($this->obtItensGrupo() as $indice => $obj_grupos) {

            $novos = $obj_grupos->recuperaCampos1();
            $this->campos = array_merge($this->campos, $novos);
            #echo '<pre>';
                #print_r($this->campos); # $r | $this->campos
            #echo '</pre>';
        }
        #$r2 = $r[0];
        #echo '<pre>';
            #echo "TST";
            #print_r($this->campos); # $r | $this->campos
        #echo '</pre>';
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