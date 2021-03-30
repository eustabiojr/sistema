<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/

 # Espaço de nomes
 namespace Estrutura\Bugigangas\Gradedados;

use Estrutura\Controle\Acao;
/**
 * Classe ColunaGradedados
 */
class ColunaGradedados 
{
    private $nome, $rotulo, $align, $largura, $acao, $transformador;

    /**
     * Método __construct
     */
    public function __construct($nome, $rotulo, $alinhamento, $largura)
    {   
        # atribui os parâmetros às propriedades do objeto
        $this->nome        = $nome;
        $this->rotulo      = $rotulo;
        $this->alinhamento = $alinhamento;
        $this->largura     = $largura;
    }

    /**
     * Método obtNome
     */
    public function obtNome()
    {
        return $this->nome;
    }

    /**
     * Método obtRotulo
     */
    public function obtRotulo()
    {
        return $this->rotulo;
    }

    /**
     * Método obtAlinhamento
     */
    public function obtAlinhamento()
    {
        return $this->alinhamento;
    }

    /**
     * Método obtLargura
     */
    public function obtLargura()
    {
        return $this->largura;
    }

    /**
     * Método defAcao
     */
    public function defAcao(Acao $acao)
    {
        $this->acao = $acao;
    }

    /**
     * Método obtAcao
     */
    public function obtAcao()
    {
        # verifica se a coluna possui ação
        if ($this->acao) {
            return $this->acao->serializa();
        }
    }

    /**
     * Método defTransformador
     */
    public function defTransformador($chamada_retorno)
    {
        $this->transformador = $chamada_retorno;
    }

    /**
     * Método obtTransformador
     */
    public function obtTransformador()
    {
        return $this->transformador;
    }
}