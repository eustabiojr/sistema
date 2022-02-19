<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 08/01/2022
 ***************************************************************************************/

namespace Estrutura\Historico;

abstract class Historico implements InterfaceHistorico {
    protected $nomearquivo;

    public function __construct($nomearquivo = NULL)
    {
        if ($nomearquivo) {
            $this->nomearquivo = $nomearquivo;
            file_put_contents($nomearquivo, '');
        }
    }

    abstract function escreve($mensagem);
}