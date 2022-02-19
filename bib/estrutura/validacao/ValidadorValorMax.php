<?php
namespace Ageunet\Validacao;

use Ageunet\Validacao\ValidadorCampo;
use Ageunet\Nucleo\AgeunetNucleoTradutor as AgeunetTradutor;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * Validação valor máximo
 *
 * @version    0.1
 */
class ValidadorValorMax extends ValidadorCampo
{
    /**
     * Validate a given value
     * @param $label Identifies the value to be validated in case of exception
     * @param $value Value to be validated
     * @param $parameters aditional parameters for validation (max value)
     */
    public function valida($rotulo, $valor, $parametros = NULL)
    {
        $valormax = $parametros[0];
        
        if ($valor > $valormax)
        {
            throw new Exception(NucleoTradutor::traduz('O campo &1 não pode ser maior que &2'), $rotulo, $valormax);
        }
    }
}