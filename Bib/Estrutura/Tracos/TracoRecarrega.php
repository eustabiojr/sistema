<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 15/03/2021
 ************************************************************************************/

namespace Estrutura\Tracos;

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Exception;

trait TracoRecarrega 
{
    public function aoRecarregar()
    {
        try {
            Transacao::abre($this->conexao);
            $repositorio = new Repositorio($this->registroAtivo);

            # cria um critério de seleção de dados
            $criterio = new Criterio;
            $criterio->defPropriedade('ORDER', 'id');

            # verifica se há filtro predefinido
            if (isset($this->filtros)) {
                foreach ($this->filtros as $filtro) {
                    $criterio->adic($filtro[0], $filtro[1], $filtro[2], $filtro[3]);
                }
            }

            # carrega os objetos que satisfazem o critério
            $objetos = $repositorio->carrega($criterio);
            $this->gradedados->limpa();
            if ($objetos) {
                foreach ($objetos as $objeto) {
                    # adiciona o objeto à grade de dados
                    $this->gradedados->adicItem($objeto);
                }
            }
            Transacao::fecha();
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }
}
