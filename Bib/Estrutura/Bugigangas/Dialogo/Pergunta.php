<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Dialogo;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\Acao;

/**
 * Classe Pergunta
 */
class Pergunta {
    public function __construct($mensagem, Acao $acao_sim, Acao $acao_nao = NULL)
    {
        $div = new Elemento('div');
        $div->class = 'alert alert-warning question';

        # converte os nomes de métodos em URL's
        $url_sim = $acao_sim->serializa();
        $link_sim = new Elemento('a');
        $link_sim->href =  $url_sim;
        $link_sim->class = 'btn btn-default'; 
        $link_sim->style = 'float: right';
        $link_sim->adic('Sim');
        $mensagem .= '&nbsp;' . $link_sim;

        if ($acao_nao) {
            $url_nao = $acao_nao->serializa();
            $link_nao = new Elemento('a');
            $link_nao->href =  $url_nao;
            $link_nao->class = 'btn btn-default'; 
            $link_nao->style = 'float: right';
            $link_nao->adic('Não');
            $mensagem .= '&nbsp;' . $link_nao;
        }
        $div->adic($mensagem);
        $div->exibe();
    }
}