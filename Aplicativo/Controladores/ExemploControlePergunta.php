<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 11/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Dialogo\Pergunta;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;

/**
 * Class ExemploControlePergunta
 */
class ExemploControlePergunta extends Pagina
{
    public function __construct()
    {
        parent::__construct();

        $acao1 = new Acao(array($this, 'naConfirmacao'));
        $acao2 = new Acao(array($this, 'naNegacao'));

        $p = new Pergunta('Você deseja confirmar a ação?', $acao1, $acao2);

        $div = new Elemento('div');
        $div->adic($p);
        parent::adic($div);

    }

    public function naConfirmacao()
    {
        print "Você escolheu confirmar a questão";
    }

    public function naNegacao()
    {
        print "Você escolheu negar a questão";
    }
}