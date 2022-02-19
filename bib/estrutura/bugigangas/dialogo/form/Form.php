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
    protected $nome;
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
    public static function obtFormPeloNome($nome)
    {
        if (isset(self::$forms[$nome])) {
            return self::$forms[$nome];
        }
    }


    /**
     * Método defNome
     * define o nome do formulário
     */
    public function defNome($nome)
    {
        $this->nome = $nome;

        # registra o nome do formulário
        self::$forms[$this->nome] = $this;
    }

    /**
     * Método obtNome
     * retorna o nome do formulário
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
    public function adicCampo(InterfaceBugiganga $campo)
    {
        $nome = $campo->obtNome();
        if (isset($this->campos[$nome]) AND substr($nome, -2) !== '[]') {
            throw new Exception("Você já adicionou o campo {$nome} ao formulário");
        }
        # Esta propriedade precisa ser trabalhada.
        if ($nome) {
            $this->campos[$nome] = $campo;
            $campo->defNomeForm($this->nome);
            
            if ($campo instanceof Botao) {
                $campo->adicFunction($this->funcao_js);
            }
        }
        
    }

    /**
     * Remove a form field
     * @param $field Object
     */
    public function apagCampo(InterfaceBugiganga $campo)
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
     * Remove todos os campos
     */
    public function defCampos($campos)
    {
        if (is_array($campos)) {
            $this->campos = array();
            $this->funcao_js = '';
            foreach ($campos as $campo) {
                $this->adicCampo(($campo));
            }
        } else {
            throw new Exception("O método {__METHOD__} deve receber um parâmetro tipo Array");
        }
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

    /**
     * Método defDados
     */
    public function defDados($objeto)
    {
        foreach($this->campos as $nome => $campo) {
            if ($nome) {
                if (isset($objeto->$nome)) {
                    $campo->defValor($objeto->$nome);
                }
            }
        }
    }

    /**
     * Método obtDados
     * 
     * Retorna os dados POST como um objeto
     * @param $classe A string contendo a classe para o objeto de retorno
     */
    public function obtDados($classe = 'stdClass')
    {
        if (!class_exists($classe)) {
            throw new Exception("A classe {$classe} não encontrada em {__METHOD__}");
        }

        $objeto = new $classe;

        /**
         * Aqui pegamos os campos, e o transformamos em propriedade e seu respectivo valor
         * em um objeto genérico.
         */
        foreach ($this->campos as $chave => $objetoCampo) {
            $chave = str_replace(['[',']'], ['',''], $chave);
            if (!$objetoCampo instanceof Botao && !in_array($chave, $this->campos_silenciosos)) {
                #$val = $_POST[$chave] ?? '';
                $objeto->$chave = $objetoCampo->obtDadosPost();
            }
        }

        # percorre os arquivos de upload
        #foreach($_FILES as $chave => $conteudo) {
        #    $objeto->$chave = $conteudo['nome_tmp'];
        #}
        return $objeto;
    }

    /**
     * Método obtValores
     * 
     * Retorna os valores iniciais do formulário como um objeto
     * @param $classe A string contendo a classe para o objeto de retorno
     */
    public function obtValores($classe = 'StdClass', $comOpcoes = false)
    {
        if (!class_exists($classe)) {
            throw new Exception("A classe {$classe} não encontrada em {__METHOD__}");
        }

        $objeto = new $classe;
        if ($this->campos) {
            foreach ($this->campos as $chave => $campo) {
                $chave = str_replace(['[',']'], ['',''], $chave);

                if (!$campo instanceof Botao) {
                    if ($comOpcoes AND method_exists($campo, 'obtItens')) {
                        $itens = $campo->obtItens();

                        if (is_array($campo->obtItens())) {
                            $valor = [];
                            foreach ($campo->obtValor() as $valor_campo) {
                                if ($valor_campo) {
                                    $valor[] = $itens[$valor_campo];
                                }
                            }
                            $objeto->$chave = $valor;
                        }
                    } else {
                        $objeto->$chave = $campo->obtValor();
                    }
                }
            }
        }
        return $objeto;
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

    /**
     * Adiciona um recipiente ao formulário (geralmente uma tabela ou cartão)
     * 
     * @param $objeto Qualquer objeto que implemente o método exibe()
     */
    public function adic($objeto) 
    {
        if (!in_array($objeto, $this->filhos)) {
            $this->filhos[] = $objeto;
        }
    }

    /**
     * Embala um recipiente para um formulário (geralmente uma tabela ou cartão)
     * @param mixed $objeto, Qualquer objeto que implemente o método exibe()
     */
    public function pacote()
    {
        $this->filhos = func_get_args();
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
     * Retorna o objeto filho
     */
    public function obtFilhos()
    {
        return $this->filhos[0];
    }

    /**
     * Mostra o formulário na tela
     */
    public function exibe()
    {
        # define propriedades do formulário
        $this->elemento->{'enctype'} = "multipart/form-data";
        $this->elemento->{'name'}    = $this->nome;
        $this->elemento->{'id'}      = $this->nome;
        $this->elemento->{'method'}  = 'post';

        # adiciona o recipiente ao formulário
        if (isset($this->filhos)) {
            foreach ($this->filhos as $filho) {
                $this->elemento->adic($filho);
            }
        }
        # exibe o formulário
        $this->elemento->exibe();
    }
}