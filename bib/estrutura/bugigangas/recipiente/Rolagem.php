<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 09/06/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;

/**
 * Rolagem da janela: Permite adicionar outro recipiente dentro, criando barras de rolagem quando 
 * seu conteúdo é maior que sua área visual
 * 
 * @version    0.1
 * @package    widget
 * @subpackage form
 * @author     Pablo DallOglio (E modificado por Eustábio J. Silva Jr.)
 * @copyright  Copyright (c) 2020 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Rolagem extends Elemento
{
    private $largura;
    private $altura;
    private $margem;
    private $transparencia;

    /**
     * Método construtor da classe
     */
    public function __construct()
    {
        $this->{'id'} = 'rolagem_' . \mt_rand(1000000000, 1999999999);
        $this->margem = 2;
        $this->transparencia = FALSE;
        parent::__construct('div');
    }

    /**
     * Configura o tamanho da rolagem
     * @param $largura - Largura do cartão
     */
    public function defMargem($margem)
    {
        $this->margin = $margem;
    }

    /**
     * Transparencia - por motivos de compatibilidade
     */
    public function defTransparencia($transparencia)
    {
        $this->transparencia = $transparencia;
    }

    /**
     * Exibe a tag
     */
    public function exibe()
    {
        if (!$this->transparencia) {
            $this->{'style'} .= ';border: 1px solid #c2c2c2';
            $this->{'style'} .= ';background: #ffffff';
        }
        $this->{'style'} .= ";padding: {$this->margem}px";

        if (!empty($this->largura)) {
            $this->{'style'} .= is_numeric($this->largura) ? ";width: {$this->largura}px" : ";width: {$this->largura}";
        }

        if (!empty($this->altura)) {
            $this->{'style'} .= is_numeric($this->altura) ? ";height: {$this->altura}px" : ";height: {$this->altura}";
        }

        $this->{'style'} .= " rolagem";
        parent::exibe();
    }
}