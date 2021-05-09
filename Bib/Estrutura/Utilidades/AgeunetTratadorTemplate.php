<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 14/06/2020
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Utilidades;

#use Matematica\Analisador;
use Exception;
use Matematica\Analisador;

/**
* Tratamento de Template
* @version 0.1
* @package util
* @author Pablo Dall'Oglio (Alterado por Eustábio J. Silva Jr.)
* @copyright Copyright (c) 2020
* @license ??
*/
class AgeunetTratadorTemplate
{
    /**
     * Substitui a string com propriedades do objeto dentro {padrao}
     * @param $conteudo String com o padrão
     * @param $objeto Qualquer objeto
     */
    public static function substitui($conteudo, $objeto, $moldar = null, $metodos_substituicao = false)
    {
        if ($metodos_substituicao) {
            # metodos de substituição
            $metodos = get_class_methods($objeto);
            if ($metodos) {
                foreach ($metodos as $metodo) {
                    if (stristr($conteudo, "{$metodo}()") !== FALSE) {
                        $conteudo = str_replace('{' . $metodo . '()}', $objeto->$metodo(), $conteudo);
                    }
                }
            }
        }

        if (preg_match_all('/\{(.*?)\}/', $conteudo, $combinacoes)) {
            foreach ($combinacoes[0] as $combinacao) {
                $propriedade = substr($combinacao, 1, -1);

                if (strpos($propriedade, '->') !== FALSE) {
                    $partes = explode('->', $propriedade);
                    $recipiente = $objeto;
                    foreach ($partes as $parte) {
                        if (is_object($recipiente)) {
                            $resultado = $recipiente->$parte;
                            $recipiente = $resultado;
                        } else {
                            throw new Exception("Tentativa de acesso à uma propriedade não existente {$propriedade}");
                        }
                    }
                    $valor = $resultado;
                } else {
                    $valor = $objeto->$propriedade;
                }

                # to cast
                if ($moldar) {
                    settype($valor, $moldar);
                }
                $conteudo = \str_replace($combinacao, $valor, $conteudo);
            }
        }
        return $conteudo;
    }

    /**
     * Avalia expressão matemática
     */
    public static function avaliaExpressao($expressao) 
    {
        $analisador = new Analisador();
        $expressao = \str_replace('+', ' + ', $expressao);
        $expressao = \str_replace('-', ' - ', $expressao);
        $expressao = \str_replace('*', ' * ', $expressao);
        $expressao = \str_replace('/', ' / ', $expressao);
        $expressao = \str_replace('(', ' ( ', $expressao);
        $expressao = \str_replace(')', ' ) ', $expressao);

        return $analisador->avalia($expressao);
    }

    /**
     * Substitui algumas funções PHP
     */
    public static function substituiFuncoes($conteudo)
    {
        if ((strpos($conteudo, 'date_format') === false) AND (strpos($conteudo, 'number_format') === false) AND (strpos($conteudo, 'evaluate') === false)) {
            return $conteudo;
        }

        preg_match_all('/evaluate\(([-+\/\d\.\s\(\))*]*)\)/', $conteudo, $combinacoes3);

        if (count($combinacoes3 > 0)) {
            foreach ($combinacoes3[0] as $chave => $valor) {
                $cru        = $combinacoes3[0][$chave];
                $expressao  = $combinacoes3[1][$chave];

                $resultado = self::avaliaExpressao($expressao);
                $conteudo = \str_replace($cru, $resultado, $conteudo);
            }
        }

        $mascara_data = [];
        $mascara_data[] = '/date_format\(([0-9]{4}-[0-9]{2}-[0-9]{2}),\s*\'([A-z_\/\-0-9\s\:\,\.]*)\'\)/'; # máscara 2000-12-31
        $mascara_data[] = '/date_format\(([0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}),\s*\'([A-z_\/\-0-9\s\:\.\,]*)\'\)/'; # máscara 2000-12-31 10:31:58
        $mascara_data[] = '/date_format\(([0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}\.[0-9]+),\s*\'([A-z_\/\-0-9\s\:\.\,]*)\'\)/'; # máscara 2000-12-31 10:31:58.16809
        $mascara_data[] = '/date_format\((\s*),\s*\'([A-z_\/\-0-9\s\:\.\,]*)\'\)/'; # Máscara em branco

        foreach ($mascaras_data as $mascara_data) {
            \preg_match_all($mascara_data, $conteudo, $combinacoes1);

            if (count($combinacoes1) > 0) {
                foreach ($combinacoes1[0] as $chave => $valor) {
                    $cru     = $combinacoes1[0][$chave];
                    $data    = $combinacoes1[1][$chave];
                    $mascara = $combinacoes1[2][$chave];

                    if (!empty(trim($data))) {
                        $conteudo = \str_replace($cru, data_format(date_create($data), $mascara), $conteudo);
                    } else {
                        $conteudo = \str_replace($cru, '', $conteudo);
                    }
                }
            }
        }

        \preg_match_all('/number_format\(\s*([\d+\.\d]*)\s*,\s*([0-9])+\s*,\s*\'(\,*\.*)\'\s*,\s*\'(\,*\.*)\'\s*\)/', $conteudo, $combinacoes2);

        if (count($combinacoes2) > 0) {
            foreach ($combinacoes2[0] as $chave => $valor) {
                $cru      = $combinacoes2[0][$chave];
                $numero   = $combinacoes2[1][$chave];
                $decimais = $combinacoes2[2][$chave];
                $sep_dec  = $combinacoes2[3][$chave];
                $sep_mil  = $combinacoes2[4][$chave];
                if (!empty(trim($numero))) {
                    $conteudo = \str_replace($cru, \number_format($numero, $decimais, $sep_dec, $sep_mil), $conteudo);
                } else {
                    $conteudo = \str_replace($cru, '', $conteudo);
                }
            }
        }
        return $conteudo;
    }

    /**
     * Processo atribuição de variável
     * @param $conteudo Conteúdo do template
     * @param $substituicoes Substituições das variável do template
     */
    public static function processoAtribuicao($conteudo, &$substituicoes)
    {
        $mascaras = [];
        $mascaras[] = '/\{\%\s*def\s*([A-z_]*)\s*\+=\s*([-+\/\d\.\s\(\))*]*) \%\}/';
        $mascaras[] = '/\{\%\s*def\s*([A-z_]*)\s*=\s*([-+\/\d\.\s\(\))*]*) \%\}/';

        foreach ($mascaras as $chave_masc => $mascara) {
            \preg_match_all($mascara, $conteudo, $combinacoes1);

            if (count($combinacoes1) > 0) {
                foreach ($combinacoes1[0] as $chave => $valor) {
                    $variavel  = $combinacoes1[1][$chave];
                    $expressao = $combinacoes1[2][$chave];
    
                    if ($chave_masc == 0) {
                        if (!isset($substituicoes['principal'][$variavel])) {
                            $substituicoes['principal'][$variavel] = 0;
                        }
                        $substituicoes['principal'][$variavel] += (float) self::avaliaExpressao($expressao);
                    } else if ($chave_masc == 1) {
                        $substituicoes['principal'][$variavel] += (float) self::avaliaExpressao($expressao);
                    }
                }
            }
        }
        # echo '<pre>'; var_dump($substituicoes);echo '</pre>';
        # {% def total += avalia( {{preco}} * {{quantidade}} )%}
    }
}