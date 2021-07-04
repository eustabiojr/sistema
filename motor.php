<?php

require_once 'inic.php';

use Estrutura\Nucleo\ConfigAplicativo;
use Estrutura\Nucleo\NucleoAplicativo;
use Estrutura\Registro\Sessao;

class Aplicativo extends NucleoAplicativo
{
    public static function rodar($depura = null)
    {
        new Sessao();
        
        if ($_REQUEST)
        {
            $ini    = ConfigAplicativo::obt();
            $depura  = is_null($depura)? $ini['general']['debug'] : $depura;
            
            parent::rodar($depura);
        }
    }
}

Aplicativo::rodar();
