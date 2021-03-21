<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 20/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Gravacao;
use Estrutura\BancoDados\Repositorio;

/**
 * Classe Estado
 */
class Usuario extends Gravacao {
    const NOMETABELA = 'usuario';


    public function fazLogin($usurio, $senha)
    {
        $criterio = new Criterio;
        $criterio->adic('usuario', '=', $usurio);
        $criterio->adic('senha', '=', $senha);

        $repo = new Repositorio('');
        $resultado = $repo->carrega($criterio);
    }
}