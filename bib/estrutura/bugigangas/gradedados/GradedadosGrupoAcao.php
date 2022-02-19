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
 * Representa um grupo de ações para a grade de dados
 * 
 * @version    0.1
 * @package    widget
 * @subpackage form
 * @author     Pablo DallOglio (E modificado por Eustábio J. Silva Jr.)
 * @copyright  Copyright (c) 2020 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class GradedadosGrupoAcao 
{
    private $acoes;
    private $cabecalhos;
    private $separadores;
    private $rotulo;
    private $icone;
    private $indice;

    /**
     * Construtor da classe GradedadosGrupoAcao
     * @param $rotulo Rótulo para grupo de ações
     * @param $icone  Ícone para grupo de ações
     */
    public function __construct($rotulo, $icone = NULL) 
    {
        $this->indice = 0;
        $this->acoes  = array();
        $this->rotulo = $rotulo;
        $this->icone  = $icone;
    }

    /**
     * Retorna o rótulo Grupo de Ações
     */
    public function obtRotulo()
    {
        return $this->rotulo;
    }

    /**
     * Retorna o ícone do Grupo de Ações
     */
    public function obtIcone()
    {
        return $this->icone;
    }

    /**
     * Adiciona um ação para o grupo de ações
     * @param $acao Objeto Acao
     */
    public function adicAcao(Acao $acao)
    {
        $this->acoes[$this->indice] = $acao;
        $this->indice++;
    }

    /**
     * Adiciona um separador
     */
    public function adicSeparador()
    {
        $this->separadores[$this->indice] = TRUE;
        $this->indice++;
    }

    /**
     * Adiciona um cabeçalho
     */
    public function adicCabecalho()
    {
        $this->cabecalhos[$this->indice] = TRUE;
        $this->indice++;
    }

    /**
     * Retorna as ações
     */
    public function obtAcoes()
    {
        return $this->acoes;
    }

    /**
     * Retorna as cabeçalhos
     */
    public function obtCabecalhos()
    {
        return $this->cabecalhos;
    }

    /**
     * Retorna os separadores
     */
    public function obtSeparadores()
    {
        return $this->separadores;
    }
}