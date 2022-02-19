<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 08/01/2022
 ***************************************************************************************/

namespace Estrutura\Historico;

class HistoricoXML extends Historico {
    public function escreve($mensagem) {
        $nivel = "depuração";
        date_default_timezone_set('America/Bahia');
        $tempo = date('d-m-Y H:i:s');

        $texto = "<hist>" . PHP_EOL;
        $texto .= "<nivel>$nivel</nivel>" . PHP_EOL;
        $texto .= "<tempo>$tempo</tempo>" . PHP_EOL;
        $texto .= "<mensagem>$mensagem</mensagem>" . PHP_EOL;
        $texto .= "</hist>" . PHP_EOL;

        $tratador = fopen($this->nomearquivo, 'a');
        fwrite($tratador, $texto);
        fclose($tratador);
    }
}