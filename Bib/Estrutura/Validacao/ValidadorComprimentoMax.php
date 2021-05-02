<?php
namespace Ageunet\Validacao;

use Ageunet\Validacao\ValidadorCampo;
use Exception;

/**
 * Validação de comprimento máximo
 * @param $rotulo Identifica o valor a ser validado no caso de exceção
 * @param $valor O valor a ser validado
 * @param $parametros Parametros adicionais para validação
 */
class ValidadorComprimentoMax extends ValidadorCampo
{
    /**
     * Validate a given value
     */
    public function valida($rotulo, $valor, $parametros = NULL)
    {
        $comprimento = $parametros[0];
        
        if (strlen($valor) > $comprimento)
        {
            throw new Exception('O campo ^1 não pode ter mais de ^2 caracteres', $rotulo, $comprimento);     
        }
    }
}