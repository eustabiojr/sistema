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
 * Classe Pessoa
 */
class Pessoa extends Gravacao
{
    const NOMETABELA = 'pessoa';

    private $cidade;

    public function obt_cidade()
    {
        if (empty($this->cidade)) {
            $this->cidade = new Cidade($this->id_cidade);
        }
        return $this->cidade;
    }

    public function obt_nome_cidade() {
        if (empty($this->cidade)) {
            $this->cidade = new Cidade($this->id_cidade);
        }
        return $this->cidade->nome;
    }

    public function adicGrupo(Grupo $grupo)
    {
        $pg = new GrupoPessoa;
        $pg->id_grupo = $grupo->id;
        $pg->id_pessoa = $this->id;
        $pg->grava();
    }

    public function apagGrupos() 
    {
        $criterio = new Criterio;
        $criterio->adic('id_pessoa', '=', $this->id);

        $repo =  new Repositorio('GrupoPessoa');
        return $repo->apaga($criterio);
    }

    public function obtGrupos() 
    {
        $grupos = array();
        $criterio = new Criterio;
        $criterio->adic('id_pessoa', '=', $this->id);

        $repo =  new Repositorio('GrupoPessoa');
        $vinculos = $repo->carrega($criterio);

        if ($vinculos) {
            foreach ($vinculos as $vinculo) {
                $grupos[] = new Grupo($vinculo->id_grupo);
            }
        }
        return $grupos;
    }

    public function obtIdsGrupos()
    {
        $ids_grupos = array();
        $grupos = $this->obtGrupos();
        if ($grupos) {
            foreach ($grupos as $grupo) {
                $ids_grupos[] = $grupo->id;
            }
        }
        return $ids_grupos;
    }

    public function obtContasEmAberto()
    {
        return Conta::obtPorPessoa($this->id);
    }

    public function totalDebitos()
    {
        return Conta::debitosPorPessoa($this->id);
    }
}