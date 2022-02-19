<?php
namespace Ageunet\Validacao;

use Ageunet\Validacao\ValidadorCampo;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * Validação de Email
* valida um valor fornecido
* @param $rotulo Identifica o valor a ser validado no caso de exceção
* @param $valor O valor a ser validado
* @param $parametros Parametros adicionais para validação
*/
class ValidadorEmail extends ValidadorCampo
{
    /**
     * Validate a given value
     */
    public function valida($rotulo, $valor, $parametros = NULL)
    {
        if (!empty($valor))
        {
            $filtro = filter_var(trim($valor), FILTER_VALIDATE_EMAIL);
            
            if ($filtro === FALSE)
            {
                throw new Exception(NucleoTradutor::traduz('O campo &1 contém um e-mail inválido'), $rotulo);
            }
        }
    }
}
