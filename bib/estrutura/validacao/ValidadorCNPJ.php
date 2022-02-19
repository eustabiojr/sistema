<?php
namespace Ageunet\Validacao;

use Ageunet\Validacao\ValidadorCampo;
use Exception;

/**
 * Validação de CNPJ
 *
 */
class ValidadorCNPJ extends ValidadorCampo
{
    /**
     * Validate a given value
     */
    public function valida($rotulo, $valor, $parametros = NULL)
    {
        $cnpj = preg_replace( "@[./-]@", "", $valor );
        if( strlen( $cnpj ) <> 14 or !is_numeric( $cnpj ) )
        {
            throw new Exception('O campo ^1 não contém um CNPJ válido', $rotulo);        }
        $k = 6;
        $soma1 = 0;
        $soma2 = 0;
        for( $i = 0; $i < 13; $i++ )
        {
            $k = $k == 1 ? 9 : $k;
            $soma2 += ( substr($cnpj, $i, 1) * $k );
            $k--;
            if($i < 12)
            {
                if($k == 1)
                {
                    $k = 9;
                    $soma1 += ( substr($cnpj, $i, 1) * $k );
                    $k = 1;
                }
                else
                {
                    $soma1 += ( substr($cnpj, $i, 1) * $k );
                }
            }
        }
        
        $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
        $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;
        
        $valido = ( substr($cnpj, 12, 1) == $digito1 and substr($cnpj, 13, 1) == $digito2 );
        
        if (!$valido)
        {
            throw new Exception('O campo ^1 não contém um CNPJ válido', $rotulo);
        }
    }
}
