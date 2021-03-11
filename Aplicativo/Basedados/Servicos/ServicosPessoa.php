<?php
/********************************************************************************************
 * Sistema - Rest
 * 
 * Autor: Eustábio Júnior
 * Data: 07/03/2021
 ********************************************************************************************/
use Estrutura\BancoDados\Transacao;

class ServicosPessoa {

    public static function obtDados($solicitacao) 
    {
        $id_pessoa = $solicitacao['id'];

        $array_pessoa = array();
        Transacao::abre('exemplo');

        $pessoa = Pessoa::localiza($id_pessoa);
        if ($pessoa) {
            $array_pessoa = $pessoa->paraArray();
        } else {
            throw new Exception("Pessoa {$id_pessoa} não encontrado");
        }
        Transacao::fecha();
        return $array_pessoa;
    }
}