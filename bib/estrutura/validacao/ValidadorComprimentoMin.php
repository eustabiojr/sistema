<?php
namespace Ageunet\Validacao;

use Ageunet\Validacao\GValidadorCampo;
use Exception;

/**
 * Validação de comprimento mínimo
 *
 * @version    0.1
 * @package    validador
 * @author     Eustábio J. Silva Jr. (Original de Pablo Dall'Oglio)
 * @copyright  Copyright (c) 2020 Eustábio Jesus da Silva Júnior
 * @license    http://www.ageueletro.com.br
 */
class ValidadorComprimentoMin extends ValidadorCampo
{
    /**
     * valida um valor fornecido
     * @param $rotulo Identifica o valor a ser validado no caso de exceção
     * @param $valor O valor a ser validado
     * @param $parametros Parametros adicionais para validação (comprimento)
     */
    public function valida($rotulo, $valor, $parametros = NULL)
    {
        $comprimento = $parametros[0];
        
        if (strlen(trim($valor)) < $comprimento)
        {
            throw new Exception('O campo ^1 não pode ter menos de ^2 caracteres', $rotulo, $comprimento);
        }
    }
}
