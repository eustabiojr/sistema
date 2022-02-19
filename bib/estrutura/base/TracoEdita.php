<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Exception;

trait TracoEdita 
{
    public function aoEditar($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id'];
                Transacao::abre($this->conexao);

                $classe = $this->registroAtivo;
                $objeto = $classe::localiza($id);
                $this->form->defDados($objeto);
                Transacao::fecha();
            }
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }
}
