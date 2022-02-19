<?php
namespace Ageunet\Validacao;

use Ageunet\Validacao\ValidadorCampo;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
 * Validação de campo obrigatória
 *
 * @version    0.1
 * @package    validador
 * @author     Eustábio J. Silva Jr. (Original de Pablo Dall'Oglio)
 * @copyright  Copyright (c) 2020 
 * @license    http://www.ageueletro.com.br
 */
class ValidadorObrigatorio extends ValidadorCampo
{
    /**
     * Validate a given value
     * @param $rotulo Identifies the value to be validated in case of exception
     * @param $valor O valor do campo a ser validado
     * @param $parametros aditional parameters for validation
     */
    public function valida($rotulo, $valor, $parametros = NULL)
    {
        $vazio_escalar = function($teste) {
            return ( is_scalar($teste) AND !is_bool($teste) AND trim($teste) == '' );
        };
        
        if ( (is_null($valor))
          OR ($vazio_escalar($valor))
          OR (is_array($valor) AND count($valor)==1 AND isset($valor[0]) AND $vazio_escalar($valor[0]))
          OR (is_array($valor) AND empty($valor)) )
        {
            throw new Exception(NucleoTradutor::traduz('O campo &1 é necessário'), $rotulo);
        }
    }
}