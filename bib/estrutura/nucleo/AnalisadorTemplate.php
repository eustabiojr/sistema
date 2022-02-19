<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Nucleo;

use Estrutura\Controle\Pagina;
use Estrutura\Registro\Sessao;
use Exception;

/**
 * Template parser
 *
 * @version    7.1
 * @package    core
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class AnalisadorTemplate
{
    /**
     * Parse template and replace basic system variables
     * @param $conteudo raw template
     */
    public static function analisa($conteudo)
    {
        $ini       = ConfigAplicativo::obt();
        $tema     = $ini['geral']['tema'];
        $bibliotecas = file_get_contents("app/templates/{$tema}/libraries.html");
        $classe     = isset($_REQUEST['class']) ? $_REQUEST['class'] : '';
        
        if ((Sessao::obtValor('login') == 'admin'))
        {
            if (!empty($ini['geral']['ficha']))
            {
                if (file_exists("app/templates/{$tema}/builder-menu.html"))
                {
                    $construtor_menu = file_get_contents("Aplic/Templates/{$tema}/builder-menu.html");
                    $conteudo = str_replace('<!--{BUILDER-MENU}-->', $construtor_menu, $conteudo);
                }
            }
        }
        else
        {
            $conteudo = str_replace('<!--[IFADMIN]-->',  '<!--',  $conteudo);
            $conteudo = str_replace('<!--[/IFADMIN]-->', '-->',   $conteudo);
        }
        
        if (!isset($ini['permissao']['registra_usuario']) OR $ini['permissao']['registra_usuario'] !== '1')
        {
            $conteudo = str_replace(['<!--[CREATE-ACCOUNT]-->', '<!--[CREATE-ACCOUNT]-->'], ['<!--', '-->'], $conteudo);
        }
        
        if (!isset($ini['permissao']['redefine_senha']) OR $ini['permissao']['redefine_senha'] !== '1')
        {
            $conteudo = str_replace(['<!--[RESET-PASSWORD]-->', '<!--[RESET-PASSWORD]-->'], ['<!--', '-->'], $conteudo);
        }
        
        $conteudo   = str_replace('{BIBLIOTECAS}',  $bibliotecas, $conteudo);
        $conteudo   = str_replace('{classe}',       $classe, $conteudo);
        $conteudo   = str_replace('{template}',     $tema, $conteudo);
        $conteudo   = str_replace('{idioma}',      'portugues', $conteudo);
        $conteudo   = str_replace('{depuracao}',     isset($ini['geral']['depuracao']) ? $ini['geral']['depuracao'] : '1', $conteudo);
        $conteudo   = str_replace('{login}',        Sessao::obtValor('login'), $conteudo);
        $conteudo   = str_replace('{usuario}',      Sessao::obtValor('usuario'), $conteudo);
        $conteudo   = str_replace('{emailusuario}', Sessao::obtValor('emailusuario'), $conteudo);
        $conteudo   = str_replace('{frontpage}',    Sessao::obtValor('frontpage'), $conteudo);
        $conteudo   = str_replace('{userunitid}',   Sessao::obtValor('userunitid'), $conteudo);
        $conteudo   = str_replace('{userunitname}', Sessao::obtValor('userunitname'), $conteudo);
        $conteudo   = str_replace('{query_string}', $_SERVER["QUERY_STRING"], $conteudo);
        
        $css       = Pagina::obtCSSCarregado();
        $js        = Pagina::obtJSCarregado();
        $conteudo   = str_replace('{CABECALHO}', $css.$js, $conteudo);
        
        return $conteudo;
    }
}
