<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Controle\Pagina;

class ModeloTeste2 extends Pagina
{
    public function exibe() 
    {
        try {
            Transacao::abre('exemplo');

            # busca pessoa 1
            $p1 = Pessoa::localiza(1);
            $p1->apagGrupos();
            $p1->adicGrupo(new Grupo(1));
            $p1->adicGrupo(new Grupo(3));

            $grupos = $p1->obtGrupos();

            if ($grupos) {
                foreach ($grupos as $grupo) {
                    print $grupo->id . ' - ';
                    print $grupo->nome . '<br/>';
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}