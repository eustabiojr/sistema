<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Gravacao;
use Estrutura\BancoDados\Repositorio;

/**
 * Classe Conta
 */
class Conta extends Gravacao {
    const NOMETABELA = 'conta';

    private $cliente;

    public function obtCliente()
    {
        if (empty($this->cliente)) {
            $this->cliente = new Pessoa($this->id_cliente);
        }
        return $this->cliente;
    }

    public static function obtPorPessoa($id_pessoa)
    {
        $criterio = new Criterio;
        $criterio->adic('paga', '<>', 'S');
        $criterio->adic('id_cliente', '=', $id_pessoa);

        $repo = new Repositorio('Conta');
        return $repo->carrega($criterio);
    }

    public static function debitosPorPessoa($id_pessoa)
    {
        $total = 0;
        $contas = self::obtPorPessoa($id_pessoa);
        if ($contas) {
            foreach ($contas as $conta) {
                $total += $conta->valor;
            }
        }
        return $total;
    }

    public static function geraParcelas($id_cliente, $atraso, $valor, $parcelas) 
    {
        $data = new DateTime(date('Y-m-d'));
        $data->add(new DateInterval('P'. $atraso . 'D'));
        for ($n = 1; $n <= $parcelas; $n++) {
            $conta = new self;
            $conta->id_cliente = $id_cliente;
            $conta->dt_emissao = date('Y-m-d');
            $conta->data_vencimento = $data->format('Y-m-d');
            $conta->valor = $valor / $parcelas;
            $conta->paga = 'N';
            $conta->grava();
            $data->add(new DateInterval('P1M'));
        }
    }
}