<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Ageunet\Validacao\ValidadorCampo;
use Ageunet\Validacao\ValidadorObrigatorio;
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\GrupoVerifica;
use Estrutura\Bugigangas\Form\GrupoRadio;
use Estrutura\Bugigangas\Form\InterfaceBugiganga;
use Estrutura\Bugigangas\Form\Oculto;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Bugigangas\Recipiente\CaixaH;
use Estrutura\Bugigangas\Recipiente\Tabela;
use Estrutura\Controle\Acao;
use Exception;

/**
* Cria formulários rapidamente para dados de entrada com um recipiente padrão para elementos
*/
class FormRapido extends Form
{
    protected $campos;
    protected $nome;
    protected $botoesAcao;
    protected $linhasEntrada;
    protected $linhaAtual;
    protected $tabela;
    protected $recipienteAcoes;
    protected $possuiAcao;
    protected $camposPorLinha;
    protected $tituloCelula;
    protected $acaoCelula;
    protected $posicoesCampo;
    protected $validacao_cliente;

    /**
     * Classe construtor
     * @param $nome Nome do formulário
     */
    public function __construct($nome = 'meu_form')
    {
        parent::__construct($nome);

        # cria a tabela
        $this->tabela            = new Tabela;
        $this->possuiAcao        = FALSE;
        $this->validacao_cliente = FALSE;

        $this->camposPorLinha = 1;

        $this->defPropriedade('novalidate', '');

        // adiciona a tabela ao formulário
        parent::adic($this->tabela);
    }

    /**
     * Liga/Desliga a validação de cliente (esta validação se refere a validação nativa do navegador de internet)
     */
    public function defValidacaoCliente($bool) 
    {
        if ($bool) {
            $this->redefinePropriedade('novalidate');
        } else {
            $this->defPropriedade('novalidate', '');
        }
    }

    /**
     * Retorna as ações o recipiente
     */
    public function obtAcoesRecipiente()
    {
        return $this->recipienteAcoes;
    }

    /**
     * Retorna a tabela interna
     */
    public function obtTabela()
    {
        return $this->tabela;
    }

    /**
     * Define a quantidade de campos por linha
     * 
     * @param $quantidade Quantidade de campos
     */
    public function defCamposPorLinha($quantidade) 
    {
        if (is_int($quantidade) AND $quantidade >= 1 AND $quantidade <=3) {
            $this->camposPorLinha = $quantidade;
            if(!empty($this->tituloCelula)) {
                $this->tituloCelula->{'colspan'} = 2 * $this->camposPorLinha;
            }
            if (!empty($this->acaoCelula)) {
                $this->acaoCelula->{'colspan'}   = 2 * $this->camposPorLinha;
            }
        } else {
            throw new Exception("O método {__METHOD__} aceita apenas valores do tipo inteiro entre 1 e 3");
        }
    }

    /**
     * Retorna os campos pela contagem de linhas
     */
    public function obtCamposPorLinha()
    {
        return $this->camposPorLinha;
    }

    /**
     * Intercepta sempre que alguém atribuir um novo valor de propriedade
     * 
     * @param $nome Nome da propriedade
     * @param $valor Valor da propriedade
     */
    public function __set($nome, $valor)
    {
        if ($nome == 'class') {
            $this->tabela->{'width'} = '100%';
        }

        if (method_exists('RForm', '__set')) {
            parent::__set($nome, $valor);
        }
    }

    /**
     * Retorna o recipiente formulário
     */
    public function obtRecipiente()
    {
        return $this->tabela;
    }

    /**
     * Adiciona um titulo de formulário
     * 
     * @param $titulo Titulo do formulário
     */
    public function defTituloForm($titulo)
    {
        # adiciona o campo ao recipiente
        $linha = $this->tabela->adicLinha();
        $linha->{'class'} = 'tituloform';
        $this->tabela->{'width'} = '100%';
        $this->tituloCelula = $linha->adicCelula(new Rotulo($titulo));
        $this->tituloCelula->{'colspan'} = 2 * $this->camposPorLinha;
    }

    /**
     * Retorna grupos de entrada
     */
    public function obtLinhasEntrada()
    {
        return $this->linhasEntrada;
    }

    /**
     * Adiciona um campo ao formulário
     * 
     * @param $rotulo Rotulo do campo
     * @param $objeto Objeto do campo
     * @param $tamanho Tamanho do campo
     * @param $validador Validador do campo
     */
    public function adicCampoRapido($rotulo, InterfaceBugiganga $objeto, $tamanho = 200, ValidadorCampo $validador = NULL, $tamanho_rotulo = NULL)
    {
        if ($tamanho && !$objeto instanceof GrupoRadio && !$objeto instanceof GrupoVerifica) {
            $objeto->defTamanho($tamanho);
        }
        parent::adicCampo($objeto);

        if ($rotulo instanceof Rotulo) {
            $rotulo_campo = $rotulo;
            $valor_rotulo = $rotulo->obtValor();
        } else {
            $rotulo_campo = new Rotulo($rotulo);
            $valor_rotulo = $rotulo;
        }

        $objeto->defRotulo($valor_rotulo);

        if (empty($this->linhaAtual) OR ($this->posicoesCampo % $this->camposPorLinha) == 0) {
            // adiciona o campo ao recipiente
            $this->linhaAtual = $this->tabela->adicLinha();
            $this->linhaAtual->{'class'} = 'gformrow'; ###
        }

        $linha = $this->linhaAtual;

        if ($validador instanceof ValidadorObrigatorio) {
            $rotulo_campo->defCorFonte('#FF0000'); ###
        }

        if ($tamanho_rotulo) {
            $rotulo_campo->defTamanho($tamanho_rotulo);
        }
        if ($objeto instanceof Oculto) {
            $linha->adicCelula(''); ###
            $linha->{'style'} = 'display: none';
        } else {
            $celula = $linha->adicCelula($rotulo_campo);
            $celula->{'width'} = '30%';
        }

        $linha->adicCelula($objeto); ###

        if ($validador) {
            $objeto->adicValidacao($valor_rotulo, $validador);
        }

        $this->linhasEntrada[] = array($rotulo_campo, array($objeto), $validador instanceof ValidadorObrigatorio, $linha);
        $this->posicoesCampo++;
        return $linha;
    }

    /**
     * Adiciona um campo ao formulário
     * 
     * @param $rotulo Rotulo do campo
     * @param $objetos Array de bjetos
     * @param $obrigatorio Booleano TRUE se obrigatório
     */
    public function adicCamposRapido($rotulo, $objetos, $obrigatorio = FALSE)
    {
        if (empty($this->linhaAtual) OR ($this->posicoesCampo % $this->camposPorLinha) == 0) {
            // adiciona o campo ao recipiente
            $this->linhaAtual = $this->tabela->adicLinha();
            $this->linhaAtual->{'class'} = 'gformrow'; ###         
        }

        $linha = $this->linhaAtual;

        if ($rotulo instanceof Rotulo) {
            $rotulo_campo = $rotulo;
            $valor_rotulo = $rotulo->obtValor();
        } else {
            $rotulo_campo = new Rotulo($rotulo);
            $valor_rotulo = $rotulo;
        }

        if ($obrigatorio) {
            $rotulo_campo->defCorFonte('#FF0000'); ###
        }

        $linha->adicCelula($rotulo_campo); ###

        $caixah = new CaixaH;
        foreach ($objetos as $objeto) {
            parent::adicCampo($objeto);

            if (!$objeto instanceof Botao) {
                $objeto->defRotulo($valor_rotulo);
            }
            $caixah->adic($objeto);
        }
        $linha->adicCelula($caixah);

        $this->posicoesCampo++;
        $this->linhasEntrada[] = array($rotulo_campo, $objetos, $obrigatorio, $linha);
        return $linha;
    }

    /**
     * Adiciona um campo ao formulário
     * 
     * @param $rotulo Rotulo da ação
     * @param $acao Objeto ação
     * @param $icone Ícone da ação
     */
    public function adicAcaoRapida($rotulo, Acao $acao, $icone = '')
    {
        $nome = 'btn_' . strtolower(str_replace(' ', '_', $rotulo));
        $botao = new Botao($nome);
        parent::adicCampo($botao);

        # define o botão de ação
        $botao->defAcao($acao, $rotulo);
        $botao->defImagem($icone);

        if (!$this->possuiAcao) {
            $this->recipienteAcoes = new CaixaH;

            $linha = $this->tabela->adicLinha();
            $linha->{'class'} = 'gacaoform';
            $this->acaoCelula = $linha->adicCelula($this->recipienteAcoes);
            $this->acaoCelula->{'colspan'} = 2 * $this->camposPorLinha;
        }

        # adiciona célula para botão
        $this->possuiAcao = TRUE;
        $this->botoesAcao[] = $botao;

        return $botao;
    }

    /**
     * Adiciona um botão ao formulário
     * 
     * @param $rotulo Rotulo da ação
     * @param $acao Ação JS
     * @param $icone icone da ação
     */
    public function adicBotaoRapido($rotulo, $acao, $icone = 'fa:save')
    {
        $nome = strtolower(str_replace('','_', $rotulo));
        $botao = new Botao($nome);
        parent::adicCampo($botao);

        # define a ação do botão
        $botao->adicFuncao($acao);
        $botao->defRotulo($rotulo);
        $botao->defImagem($icone);

        if (!$this->possuiAcao) {
            $this->recipienteAcoes = new CaixaH;

            $linha = $this->tabela->adicLinha();
            $linha->{'class'} = 'gacaoform';
            $this->acaoCelula = $linha->adicCelula($this->recipienteAcoes);
            $this->acaoCelula->{'colspan'} = 2 * $this->camposPorLinha;
        }

        # adiciona célula para botão
        $this->recipienteAcoes->adicLinha();
        $this->possuiAcao = TRUE;

        return $botao;
    }

    /**
     * Limpar ações linha
     */
    public function apagAcoes()
    {
        if ($this->recipienteAcoes) {
            foreach ($this->recipienteAcoes as $chave => $botao) {
                parent::apagCampo($botao);
                unset($this->botoesAcao[$chave]);
            }
            $this->recipienteAcoes->limparFilhos(); ###
        }
    }

    /**
     * Retorna um array com botões de ação
     */
    public function obtBotoesAcao()
    {
        return $this->botoesAcao;
    }

    /**
     * Desanexa ação de botão
     */
    public function desanexaBotoesAcao()
    {
        $botoes = $this->obtBotoesAcao();
        $this->apagAcoes();
        return $botoes;
    }

    /**
     * Adiciona uma linha
     */
    public function adicLinha()
    {
        return $this->tabela->adicLinha();
    }
}