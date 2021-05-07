<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\Bugigangas\Form\Form;

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
}