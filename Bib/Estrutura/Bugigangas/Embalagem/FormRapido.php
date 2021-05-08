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
use Estrutura\Bugigangas\Base\CaixaH;
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\GrupoCheck;
use Estrutura\Bugigangas\Form\GrupoRadio;
use Estrutura\Bugigangas\Form\InterfaceBugiganga;
use Estrutura\Bugigangas\Form\Oculto;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Bugigangas\Recipiente\Tabela;
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
    protected $acoesRecipiente;
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
     * Liga/Desliga a validação de cliente (esta valiação se refere a validação nativa do navegador de internet)
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
        return $this->acoesRecipiente;
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
            throw new Exception("O método {__METHOD__} aceita apenas valores do tipo inteiro entre 1 e 3";)
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
        if ($tamanho && !$objeto instanceof GrupoRadio && !$objeto instanceof GrupoCheck) {
            $objeto->defTamanho($tamanho);
        }
        parent::adicCampo($tamanho);

        if ($rotulo instanceof Rotulo) {
            $rotulo_campo = $rotulo;
            $valor_rotulo = $rotulo->obtValor();
        } else {
            $rotulo_campo = new Rotulo($rotulo);
            $valor_rotulo = $rotulo;
        }

        $objeto->defRotulo($valor_rotulo);

        if (empty($this->linhaAtual) OR ($this->posicoesCampo % $this->camposPorLinha) == o) {
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

        $linha->adicCelula($objeto); ###

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

}