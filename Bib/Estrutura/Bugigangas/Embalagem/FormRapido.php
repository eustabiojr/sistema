<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\Bugigangas\Form\Form;
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
}