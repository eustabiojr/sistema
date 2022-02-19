<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 08/01/2022
 ***************************************************************************************/

 namespace Estrutura\Historico;

class HistoricoHTML extends Historico {
    public function escreve($mensagem) {
        $nivel = "depuração";
        date_default_timezone_set('America/Bahia');
        $tempo = date('d-m-Y H:i:s');

        $texto = "<p>" . PHP_EOL;
        $texto .= "<b>$nivel</b>: " . PHP_EOL;
        $texto .= "<b>$tempo</b>" . PHP_EOL;
        $texto .= "<b>$mensagem</b>" . PHP_EOL;
        $texto .= "</p>" . PHP_EOL;

        $tratador = fopen($this->nomearquivo, 'a');
        fwrite($tratador, $texto);
        fclose($tratador);
    }
}