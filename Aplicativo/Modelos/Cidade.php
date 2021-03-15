<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Gravacao;

/**
 * Classe Cidade
 */
class Cidade extends Gravacao {
    const NOMETABELA = 'cidade';

    private $estado;

    /**
     * Usamos aqui um padrão usado em nosso 'Gravador', ou seja a classe
     * que faz a ligação entre os objetos e o banco de dados relacional. Na 
     * referida classe usamos o padrão de começar o método com 'obt_' ou 'def_'
     */
    public function obt_estado()
    {
        if (empty($this->estado)) {
            $this->estado = new Estado($this->id_estado);
        }
        return $this->estado;
    }

    public function obt_nome_estado()
    {
        if (empty($this->estado)) {
            $this->estado = new Estado($this->id_estado);
        }
        return $this->estado->nome;
    }
}