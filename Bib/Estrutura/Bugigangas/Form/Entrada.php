<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Controle\Acao;
use Exception;

/**
  * Class Rotulo
  */
class Entrada extends Campo implements InterfaceBugiganga
{
    # propriedades
    protected $propriedades;
    protected $id;

    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id   = 'entrada_' . mt_rand(1000000000, 1999999999);
        $this->mascaraNumerica = FALSE;
        $this->substituiNoPost = FALSE;
        $this->comprimentoMin  = 1;
        $this->saidaAoEntrar   = FALSE;
        $this->tag->{'type'}   = 'text';
        $this->tag->{'widget'} = 'entrada';
    }

    /**
     * Define o tipo de entrada
     */
    public function defTipoEntrada($tipo)
    {
        $this->tag->{'type'} = $tipo;
    }

    /**
     * Ligar ao dar enter
     */
    public function saiNoEnter(){
        $this->saiAoDarEnter = true;
    }

    /**
     * Define a máscara do campo
     * @param $mascara A máscara para os dados de entrada
     */
    public function defMascara($mascara, $substituiNoPost = FALSE)
    {
        $this->mascara = $mascara;
        $this->substituiNoPost = $substituiNoPost;
    }

    public function defMascaraNumerica($decimais, $separadorDecimal, $separadorMilhar, $substituiNoPost = FALSE)
    {
        if (empty($separadorDecimal)) {
            $decimais = 0;
        } else if (empty($decimais)) {
            $separadorDecimal = '';
        }

        $this->{'style'} = 'text-align: right;';
        $this->mascaraNumerica = TRUE;
        $this->decimais = $decimais;
        $this->separadorDecimal = $separadorDecimal;
        $this->separadorMilhar  = $separadorMilhar;
        $this->substituiNoPost  = $substituiNoPost;

        $this->tag->{'data-nmask'} = $decimais.$separadorDecimal.$separadorMilhar;
    }

    /**
     * Define o valor do campo
     * @param $valor Uma string contendo o valor do campo
     */
    public function defValor($valor)
    {
        if ($this->substituiNoPost) {
            if ($this->mascaraNumerica && is_numeric($valor)) {
                parent::defValor(number_format($valor, $this->separadorDecimal, $this->separadorMilhar));
            } else if ($this->mascara) {
                parent::defValor($this->formataMascara($this->mascara, $valor));
            } else {
                parent::defValor($valor);
            }
        } else {
            parent::defValor($valor);
        }
    }

    /**
     * Retorna dados post
     */
    public function obtDadosPost() : string
    {
        $nome = str_replace(['[',']'], ['',''], $this->nome);

        if (isset($_POST[$nome])) {
            if ($this->substituiNoPost) {
                $valor = $_POST[$nome];

                if ($this->mascaraNumerica) {
                    $valor = str_replace($this->separadorMilhar,  '',  $valor);
                    $valor = str_replace($this->separadorDecimal, '.', $valor);
                    return $valor;
                } else if ($this->mascara) {
                    return preg_replace('/[^a-z\d]+/i', '', $valor);
                } else {
                    return $valor;
                }
            } else {
                return $_POST[$nome];
            }
        } else {
            return '';
        }
    }

    /**
     * Define o comprimento máximo
     * @param $comprimento Comprimento máximo
     */
    public function defComprimentoMax($comprimento)
    {
        if ($comprimento > 0) {
            $this->tag->{'maxlength'} = $comprimento;
        }
    }

    /**
     * Define opções de complementação
     * @param $opcoes Array de opções de complementação
     */
    public function defComplementacao($opcoes)
    {
        $this->complementacao = $opcoes;
    }

    /**
     * Define a ação a ser executada quando o usuário deixa o campo do formulário
     * @param $acao Objeto Acao
     */
    public function defAcaoSair(Acao $acao)
    {
        if ($acao->ehEstatico()) { # Método da ação
            $this->acaoSair = $acao;
        } else {
            $acao_string = $acao->paraString();
            throw new Exception("A ação {$acao_string} deve ser estática para ser usada em {__METHOD__}");
        }
    }

    /**
     * Define a função JS a ser executada quando o usuário deixa o campo do formulário
     * @param $funcao Função Javascript
     */
    public function defFuncaoSair($funcao)
    {
        $this->funcaoSaida = $funcao;
    }

    /**
     * Força letras minúsculas
     */
    public function forcaMinusculas()
    {
        $this->tag->{'onKeyPress'} = "return entrada_minuscula(this)";
        $this->tag->{'onBlur'} = "return entrada_minuscula(this)";
        $this->tag->{'forcelower'} = "1";
        $this->defPropriedade('style', 'text-transform: lowercase'); # CSS
    }

    /**
     * Força letras maiúsculas
     */
    public function forcaMaiusculas()
    {
        $this->tag->{'onKeyPress'} = "return entrada_maiuscula(this)";
        $this->tag->{'onBlur'} = "return entrada_maiuscula((this)";
        $this->tag->{'forceupper'} = "1";
        $this->defPropriedade('style', 'text-transform: uppercase'); # CSS
    }

    /**
     * Define delimitador de auto-complementação
     * @param $delimitador Delimitador auto-complementação
     */
    public function defDelimitador($delimitador)
    {
        $this->delimitador = $delimitador;
    }

    /**
     * Define o comprimento mínimo para busca
     * @param $comprimento Comprimento mínimo
     */
    public function defComprimentoMin($comprimento)
    {
        $this->comprimentoMin = $comprimento;
    }

    /**
     * Recarrega complementação
     * @param $campo Id ou nome do campo
     * @param $opcoes Array de opções para auto-complementação
     */
    public static function recarregaComplementacao($campo, $lista, $opcoes = null)
    {
        $lista_json = json_encode($lista);
        if (is_numeric($opcoes)) {
            $opcoes = [];
        }

        $opcoes_json = json_encode($opcoes);
        Script::cria(" entrada_autocomplementa_pelo_nome('{$campo}', {$lista_json}, '{$opcoes_json}'); ");
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        # atribui as propriedades da tag
        $tag = new Elemento('input'); 
        $tag->class = 'field';
        $tag->name  = $this->nome;
        $tag->value = $this->valor;
        $tag->type  = 'text';
        $tag->style = "width: {$this->tamanho}";

        # caso o campo não seja editável
        if (!parent::obtEditavel()) {
            $tag->readonly = "1";
        }

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                $tag->$propriedade = $valor;
            }
        } 
        $tag->exibe();
    }
}