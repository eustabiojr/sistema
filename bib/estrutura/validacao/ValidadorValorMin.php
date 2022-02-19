<?php
namespace Ageunet\Validacao;

use Ageunet\Validacao\ValidadorCampo;
use Exception;

/**
 * Validação de valor mínimo
 *
 * @version    0.1
 * @package    validador
 * @author     Eustábio J. Silva Jr. (Original de Pablo Dall'Oglio)
 * @copyright  Copyright (c) 2020 Eustábio Jesus da Silva Júnior
 * @license    http://www.ageueletro.com.br
 */
class ValidadorValorMin extends ValidadorCampo
{
    /**
     * valida um valor fornecido
     * @param $rotulo Identifica o valor a ser validado no caso de exceção
     * @param $valor O valor a ser validado
     * @param $parametros Parametros adicionais para validação (valor mínimo)
     */
    public function valida($rotulo, $valor, $parametros = NULL)
    {
        $valorminimo = $parametros[0];
        
        if ($valor < $valorminimo)
        {
            throw new Exception('O campo ^1 não pode ser menor que ^2', $rotulo, $valorminimo);
        }
    }
}
