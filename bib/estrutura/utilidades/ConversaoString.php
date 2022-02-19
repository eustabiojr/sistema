<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 15/03/2021
 ************************************************************************************/

namespace Estrutura\Utilidades;

 /**
  * Tratamento de String
  * @version 0.1
  * @package util
  * @author Pablo Dall'Oglio (Alterado por Eustábio J. Silva Jr.)
  * @copyright Copyright (c) 2020
  * @license ??
  */
 class ConversaoString
{
    /**
     * Obtém string com sublinhados da string camel case
     */
    public static function camelCaseDeSublinhado($string, $espacos = FALSE)
    {
        $palavras = explode('_', mb_strtolower($string));

        $retorno = '';
        foreach ($palavras as $palavra) {
            $retorno .= ucfirst(trim($palavra));
            if($espacos) {
                $retorno .= ' ';
            }
        }
        return $retorno;
    }

    public static function SublinhadoDeCamelCase($string, $espacos = FALSE)
    {
        $saida = mb_strtolower(preg_replace('/(a-z)(A-Z)/', '$'.'1_$'.'2', $string));
        if ($espacos) {
            $saida = str_replace(' ', '_', trim($saida));
        }
        return $saida;
    }

    /**
     * Remove acentos da string
     */
    public static function removeAcentos($str)
    {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ',
        'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç',
        'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý',
        'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē',
        'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ',
        'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ',
        'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ',
        'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š',
        'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ',
        'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ',
        'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D',
        'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a',
        'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u',
        'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 
        'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 
        'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 
        'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 
        'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 
        'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 
        'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 
        'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 
        'AE', 'ae', 'O', 'o');
        return \str_replace($a, $b, $str);
    }

    /**
     * Obtém a string como Unicode quando necessário
     */
    public static function garanteUnicode($conteudo)
    {
        if (\extension_loaded('mbstring') && \extension_loaded('iconv')) {
            $cod_como = mb_detect_encoding($conteudo, ['UTF-8', 'ISO-8859-1','ASCII'], TRUE);
            if ($cod_como !== 'UTF-8') {
                $convertido = iconv($cod_como, "UTF-8", $conteudo);
                if ($convertido === false) {
                    return $conteudo;
                }
                return $conteudo;
            }
        } else {
            if (utf8_encode(\utf8_decode($conteudo)) !== $conteudo) { # # Caso não seja UTF
                return utf8_encode($conteudo);
            }
        }
        return $conteudo;
    }

    /**
     * Retorna a lesma da string
     */
    public static function lesma($conteudo)
    {
        $conteudo = self::garanteUnicode($conteudo);

        $tabela = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', ''=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r'
        );
    
        $conteudo =strtr($conteudo, $tabela);

        $conteudo = mb_strtolower($conteudo);
        # Corta quaisquer caracteres indesejados
        $conteudo = preg_replace("/[^a-Z0-9_\s-]/", "", $conteudo);
        # Limpa traços múltiplos ou espaços em branco
        $conteudo = preg_replace("/[\s-]/", " ", $conteudo);
        # Converte espaços em branco e sublinhados para traço
        $conteudo = preg_replace("/[\s_]/", "-", $conteudo);

        return $conteudo;
    }

    /**
     * Substitui entre texto
     * @param $str Texto a ser substituído
     * @param $ponto_inicio Marca de inicio
     * @param $ponto_final Marca o fim
     * @param $substituicao Texto a ser inserido
     * @param $inclui_limites se a marca de limite será substituída
     */
    public static function substituiEntre($str, $ponto_inicio, $ponto_final, $substituicao, $inclui_limites = true)
    {
        $pos = strpos($str, $ponto_inicio);
        if ($pos === false) {
            return $str;
        }
        $inicio = $pos + ($inclui_limites ? strlen($ponto_inicio) : 0);

        $pos = strpos($str, $ponto_final, $inicio);
        if ($pos === false) {
            return $str;
        }
        $fim = ($inclui_limites ? $pos : $pos + strlen($ponto_final));

        return \substr_replace($str, $substituicao, $inicio, $fim - $inicio);
    }

    public static function obtEntre($str, $ponto_inicio, $ponto_final, $substituicao, $inclui_limites = true)
    {
        $pos = strpos($str, $ponto_inicio);
        $inicio = $pos === false ? 0 : $pos + ($inclui_limites ? strlen($ponto_inicio) : 0);

        $pos = strpos($str, $ponto_final, $inicio);
        $fim = $pos === false ? strlen($str) : ($inclui_limites ? $pos : $pos + strlen($ponto_final));

        return substr($str, $inicio, $fim - $inicio);
    }
}