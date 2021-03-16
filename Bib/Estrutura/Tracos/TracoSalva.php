<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 15/03/2021
 ************************************************************************************/

namespace Estrutura\Tracos;

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Exception;

trait TracaoSalva 
{
    public function aoSalvar()
    {
        try {
            Transacao::abre($this->conexao);
            $classe = $this->registroAtivo();
            $dados  = $this->form->obtDados();

            $objeto = new $classe;
            $objeto->doArray((array) $dados);
            $objeto->grava();

            Transacao::fecha();
            new Mensagem('info', 'Dados armazenados com sucesso');
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }
}
