<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
namespace Estrutura\Historico;

/**
 * Classe HistoricoXML
 */
class HistoricoXML extends Historico {

    /**
     * Método escreve
     */
    public function escreve($mensagem)
    {
        date_default_timezone_set('America/Bahia');
        $tempo = date('d-m-Y H:i:s');

        $texto  = "<hist>" . PHP_EOL;
        $texto .= " <tempo>$tempo</tempo>" . PHP_EOL;
        $texto .= " <mensagem>$mensagem</mensagem>" . PHP_EOL;
        $texto .= "</hist>" . PHP_EOL;

        # adiciona ao final do arquivo
        $tratador = fopen($this->nomearquivo, 'a');
        fwrite($tratador, $texto);
        fclose($tratador);
    }
}