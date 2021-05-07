<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Controle\Acao;

/**
 * Classe GradeRapida
 * 
 * Cria grade de dados por meio de uma interface simples
 */
class GradeRapida extends Gradedados
{
    /**
     * Adiciona uma coluna
     * @param $rotulo  Rótulo do campo
     * @param $objeto  Objeto do campo
     * @param $tamanho Tamanho do campo
     */
    public function adicColunaRapida($rotulo, $nome, $alin = 'left', $tamanho = 200, Acao $acao = NULL, $param = NULL)
    {
        # cria uma coluna nova
        $objeto = new ColunaGradedados($nome, $rotulo, $alin, $tamanho);

        if ($acao instanceof Acao) {
            # cria ordenação
            $acao->defParametro($param[0], $param[1]);
            $objeto->defAcao($acao);
        }
        # adiciona a coluna à grade de dados
        parent::adicColuna($objeto);
        return $objeto;
    }

    /**
     * Adiciona ação à grade de dados
     * @param $rotulo Rótulo da ação
     * @param $acao   Objeto da ação
     * @param $icone  Ícone da ação
     * 
     * Nota: Falta criar a classe GradeDadosAcao
     */
    public function adicAcaoRapida($rotulo, $acao, $campo, $icone = NULL) {

    }
}