<?php
namespace Estrutura\Validacao;

use Estrutura\Nucleo\NucleoTradutor;
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
            throw new Exception(NucleoTradutor::traduz('O campo &1 não pode ser maior que &2 caracteres'), $rotulo, $comprimento);     
        }
    }
}