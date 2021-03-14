<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Gravacao;

/**
 * Classe Funcionario
 */
class Cidade extends Gravacao {
    const NOMETABELA = 'cidade';

    private $estado;

    public function obtEstado()
    {
        if (empty($this->estado)) {
            $this->estado = new Estado($this->id_estado);
        }
        return $this->estado;
    }

    public function obtNomeEstado()
    {
        if (empty($this->estado)) {
            $this->estado = new Estado($this->id_estado);
        }
        return $this->estado->nome;
    }
}