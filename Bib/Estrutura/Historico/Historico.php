<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
namespace Estrutura\Historico;

/**
 * Classe abstrata Historico
 */
abstract class Historico {
    protected $nomearquivo;

    /**
     * Método construtor
     */
    public function __construct($nomearquivo)
    {
        $this->nomearquivo = $nomearquivo;
        file_put_contents($nomearquivo, '');   
    }

    # define o método de escrita (write) como obrigatório
    abstract function escreve($mensagem);
}