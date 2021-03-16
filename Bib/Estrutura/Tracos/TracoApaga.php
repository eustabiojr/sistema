<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 15/03/2021
 ************************************************************************************/

namespace Estrutura\Tracos;

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Dialogo\Pergunta;
use Estrutura\Controle\Acao;
use Exception;

trait TracoApaga 
{
    public function aoApagar($param)
    {
        $id = $param['id'];
        $acao1 = new Acao(array($this, 'Apaga'));
        $acao1->defParametro('id', $id);
        new Pergunta('Deseja realmente excluir o registro?', $acao1);
    }

    public function Apaga($param)
    {
        try {
            Transacao::abre($this->conexao);

            $id = $param['id'];
            $classe = $this->registroAtivo;
            $objeto = $classe::localiza($id);
            $objeto->apaga();
            Transacao::fecha();
            $this->aoRecarregar();
            new Mensagem('info', 'Registro excluído com sucesso');
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }
}
