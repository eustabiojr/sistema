<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 08/01/2022
 ***************************************************************************************/

namespace Estrutura\Historico;

class HistoricoTXT extends Historico {
    public function escreve($mensagem) {
        $nivel = "depuração";
        date_default_timezone_set('America/Bahia');
        $tempo = date('d-m-Y H:i:s');

        $texto = "$nivel: $tempo - $mensagem" . PHP_EOL;
        $tratador = fopen($this->nomearquivo, 'a');
        fwrite($tratador, $texto);
        fclose($tratador);
    }
}