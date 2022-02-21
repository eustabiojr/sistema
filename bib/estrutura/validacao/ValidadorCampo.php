<?php
namespace Estrutura\Validacao;

/**
 * ValidadorCampo abstract validation class
 */
abstract class ValidadorCampo
{
    /**
     * @version    0.1
     * @package    validador
     * @author     Eustábio J. Silva Jr. (Original de Pablo Dall'Oglio)
     * @copyright  Copyright (c) 2020 Eustábio Jesus da Silva Júnior
     * @license    http://www.ageueletro.com.br
     */
    abstract public function valida($rotulo, $valor, $parametros = NULL);
}
