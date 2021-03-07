<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;

use Exception;

/**
 * Classe Repositorio
 */
class Repositorio {
    # propriedades
    private $registroAtivo;

    /**
     * Método construtor
     * 
     * aqui carregamos o a classe de gravação (registro ativo) desejada
     */
    public function __construct($classe)
    {
        $this->registroAtivo = $classe;
    }

    /**
     * Método carrega
     */
    public function carrega(Criterio $criterio)
    {
        $sql = "SELECT * FROM " . constant($this->registroAtivo.'::NOMETABELA');

        # obtém a cláusula WHERE do objeto critério.
        if ($criterio) {
            $expressao = $criterio->despeja();
            if ($expressao) {
                $sql .= ' WHERE ' . $expressao;
            }

            # obtém as propriedades do critério
            $ordem        = $criterio->obtPropriedade('ORDER');
            $limite       = $criterio->obtPropriedade('LIMIT');
            $deslocamento = $criterio->obtPropriedade('OFFSET');

            # obtém a ordenação do SELECT
            if ($ordem) {
                $sql .= ' ORDER BY ' . $ordem;
            }
            if ($limite) {
                $sql .= ' LIMIT ' . $limite;
            }
            if ($deslocamento) {
                $sql .= ' OFFSET ' . $deslocamento;
            }
        }

        # obtém transação ativa
        if ($conexao = Transacao::obt()){
            Transacao::hist($sql);

            # executa a consulta no banco de dados
            $resultado = $conexao->query($sql);
            $resultados = array();

            if ($resultado) {
                # percorre os resultados da consulta, retornando um objeto
                while ($linha = $resultado->fetchObject($this->registroAtivo)) {
                    # armazena no array $resultados
                    $resultados[] = $linha;
                }
            }
            return $resultados;
        } else {
            throw new Exception('Não há transação ativa!');
        }
    }

    /**
     * Método apaga
     */
    public function apaga(Criterio $criterio) 
    {
        $expressao = $criterio->despeja();
        $sql = "DELETE FROM " . constant($this->registroAtivo.'::NOMETABELA');
        if ($expressao) {
            $sql .= ' WHERE ' . $expressao;
        }

        # obtém transação ativa
        if ($conexao = Transacao::obt()){
            Transacao::hist($sql);
            $resultado = $conexao->exec($sql);
            return $resultado;
        } else {
            throw new Exception('Não há transação ativa!');
        }
    }

    public function conta(Criterio $criterio)
    {
        $expressao = $criterio->despeja();
        $sql = "SELECT count(*) FROM " . constant($this->registroAtivo.'::NOMETABELA');
        if ($expressao) {
            $sql .= ' WHERE ' . $expressao;
        }

        # obtém transação ativa
        if ($conexao = Transacao::obt()){
            Transacao::hist($sql);
            $resultado = $conexao->query($sql);
            if ($resultado) {
                $linha = $resultado->fetch();
            }
            return $linha[0];
        } else {
            throw new Exception('Não há transação ativa!');
        }
    }
}