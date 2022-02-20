<?php
/**
 * Tradutor Rota
 *
 * @version    7.0
 * @package    core
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TradutorRota
{
    public static function traduz($url, $formato = TRUE)
    {
        /**
         // manual entries
         $rotas = array();
         $rotas['class=TipoProdutoList'] = 'tipo-produto-list';
         $rotas['class=TipoProdutoList&metodo=onReload'] = 'tipo-produto-list';
         $rotas['class=TipoProdutoForm&metodo=onEdit']   = 'tipo-produto-edit';
         $rotas['class=TipoProdutoForm&metodo=onDelete'] = 'tipo-produto-ondelete';
         $rotas['class=TipoProdutoForm&metodo=delete']   = 'tipo-produto-delete';
         */
        
        // automatic parse .htaccess
        $rotas = self::analisaHTAccess();
        
        $chaves = array_map('strlen', array_keys($rotas));
        array_multisort($chaves, SORT_DESC, $rotas);
        
        foreach ($rotas as $padrao => $curto)
        {
            $url_nova = self::substitui($url, $padrao, $curto);
            if ($url !== $url_nova)
            {
                return $url_nova;
            }
        }
        
        foreach ($rotas as $padrao => $curto)
        {
            // ignore default page loading methods
            $padrao = str_replace(['&metodo=aoRecarregar', '&metodo=aoExibir'], ['',''], $padrao);
            $url_nova = self::substitui($url, $padrao, $curto);
            if ($url !== $url_nova)
            {
                return $url_nova;
            }
        }
        
        if ($formato)
        {
            return 'inicio.php?'.$url;
        }
        
        return $url;
    }
    
    /**
     * Substitui URL com padrão pela versão curta
     * @param $url full original URL
     * @param $padrao pattern to be replaced
     * @param $curto short version
     */
    private static function substitui($url, $padrao, $curto)
    {
        if (strpos($url, $padrao) !== FALSE)
        {
            $url = str_replace($padrao.'&', $curto.'?', $url);
            $url = str_replace($padrao, $curto, $url);
        }
        return $url;
    }
    
    /**
     * Parse HTAccess routes
     * Analisa rotas HTAcess
     * returns ARRAY[action] = route
     *     Ex: ARRAY["class=TipoProdutoList&metodo=onReload"] = "tipo-produto-list"
     */
    public static function analisaHTAccess()
    {
        $rotas = [];
        if (file_exists('.htaccess'))
        {
            $regras = file('.htaccess');
            foreach ($regras as $regra)
            {
                $partes_regra = explode(' ', $regra);
                if ($partes_regra[0] == 'RewriteRule')
                {
                    $origem = $partes_regra[1];
                    $alvo = $partes_regra[2];
                    $origem = str_replace(['^', '$'], ['',''], $origem);
                    $alvo = str_replace('&%{QUERY_STRING}', '', $alvo);
                    $alvo = str_replace(' [NC]', '', $alvo);
                    $alvo = str_replace('inicio.php?', '', $alvo);
                    $rotas[$alvo] = $origem;
                }
            }
        }
        
        return $rotas;
    }
}