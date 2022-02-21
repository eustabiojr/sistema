<?php
namespace Estrutura\Validacao;

use Estrutura\Nucleo\NucleoTradutor;
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
            throw new Exception(NucleoTradutor::traduz('O campo &1 não pode ser menor que &2 caracteres'), $rotulo, $comprimento);
        }
    }
}
