<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Controle\Pagina;
use Estrutura\Historico\HistoricoTXT;

class ObtemColecao extends Pagina {

    public function __construct()
    {
        try {
            # inicia a transação com o bd
            Transacao::abre('exemplo');

            # define o arquivo de histórico
            Transacao::defHistorico(new HistoricoTXT('/tmp/hist_colecao.txt'));

            # define o critério de seleção
            $criterio = new Criterio;
            $criterio->adic('estoque', '>', 0);
            #$criterio->adic('origem',  '=', 'N');

            # cria o repositório
            $repositorio = new Repositorio('Produto');

            # carrega os objetos, conforme o critério
            $produtos = $repositorio->carrega($criterio);

            if ($produtos) {
                echo "Produtos <br>" . PHP_EOL;
                # percorre todos os objetos
                foreach ($produtos as $produto) {
                    echo ' ID: ' . $produto->id;
                    echo ' - Descrição: ' . $produto->descricao;
                    echo ' - Estoque: '   . $produto->estoque;
                    echo "<br>" . PHP_EOL;
                }
            }

            print "Quantidade: " . $repositorio->conta($criterio);
            Transacao::fecha();
        } catch (Exception $e) {
            echo $e->getMessage();
            Transacao::desfaz();
        }
    }
}