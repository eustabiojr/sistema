<?php
namespace Ageunet\Validacao;

use Ageunet\Validacao\ValidadorCampo;
use Exception;

/**
 * Validação numérica
 *
 * @version    0.1
 * @package    validador
 * @author     Eustábio J. Silva Jr. (Original de Pablo Dall'Oglio)
 * @copyright  Copyright (c) 2020 
 * @license    http://www.ageueletro.com.br
 */
class ValidadorNumerico extends ValidadorCampo
{
    /**
     * valida um valor fornecido
     * @param $rotulo Identifica o valor a ser validado no caso de exceção
     * @param $valor O valor a ser validado
     * @param $parametros Parametros adicionais para validação (valor mínimo)
     */
    public function valida($rotulo, $valor, $parametros = NULL)
    {
        if (!is_numeric($valor))
        {
            throw new Exception('O campo ^1 deve ser numérico', $rotulo);
        }
    }
}