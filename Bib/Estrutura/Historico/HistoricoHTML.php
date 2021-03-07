<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
namespace Estrutura\Historico;

/**
 * Classe HistoricoHTML
 */
class HistoricoHTML extends Historico {

    /**
     * Método escreve
     */
    public function escreve($mensagem)
    {
        date_default_timezone_set('America/Bahia');
        $tempo = date('d-m-Y H:i:s');

        $texto  = "<p>" . PHP_EOL;
        $texto .= " <b>$tempo</b>" . PHP_EOL;
        $texto .= " <b>$mensagem</b>" . PHP_EOL;
        $texto .= "</p>" . PHP_EOL;

        # adiciona ao final do arquivo
        $tratador = fopen($this->nomearquivo, 'a');
        fwrite($tratador, $texto);
        fclose($tratador);
    }
}