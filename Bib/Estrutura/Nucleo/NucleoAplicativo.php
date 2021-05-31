<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Nucleo;

use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Util\VisaoExcecao;
use Estrutura\Nucleo\MapaClasse;
use Estrutura\Controle\Pagina;
use Estrutura\Bugigangas\Base\Script;

use Exception;
use ReflectionMethod;
use ErrorException;

/**
 * Classe NucleoAplicativo
 * 
 * Estrutura básica do aplicativo
 */
class NucleoAplicativo
{
    private static $roteador;
    private static $id_solicitacao;
    
    /**
     * Execute class/method based on request
     *
     * @param $depura Activate Exception debug
     */
    public static function rodar($depura = FALSE)
    {
        self::$id_solicitacao = uniqid();
        
        $ini = ConfigAplicativo::obt();
        $servico  = isset($ini['geral']['servico_hist_solicitacao']) ? $ini['geral']['servico_hist_solicitacao'] : '\SistemaServicoHistSolicitacao';
        $classe   = isset($_REQUEST['classe'])   ? $_REQUEST['classe']   : ''; 
        $estatico = isset($_REQUEST['estatico']) ? $_REQUEST['estatico']  : '';
        $metodo   = isset($_REQUEST['metodo'])   ? $_REQUEST['metodo']  : '';
        
        $conteudo = '';
        set_error_handler(array('NucleoAplicativo', 'tratadorErro'));
        
        if (!empty($ini['geral']['request_log']) && $ini['geral']['request_log'] == '1')
        {
            if (empty($ini['geral']['tipos_hist_solicitacao']) || strpos($ini['geral']['tipos_hist_solicitacao'], 'web') !== false)
            {
                self::$id_solicitacao = $servico::registra( 'web');
            }
        }
        
        self::filtraEntrada();
        
        if (in_array(strtolower($classe), array_map('strtolower', MapaClasse::obtClassesInternas()) ))
        {
            ob_start();
            new Mensagem( 'erro', "A classe interna <b><i><u>{$classe}</u></i></b> não pode ser executada");
            $conteudo = ob_get_contents();
            ob_end_clean();
        }
        else if (class_exists($classe))
        {
            if ($estatico)
            {
                $rf = new ReflectionMethod($classe, $metodo);
                if ($rf-> isStatic ())
                {
                    call_user_func(array($classe, $metodo), $_REQUEST);
                }
                else
                {
                    call_user_func(array(new $classe($_REQUEST), $metodo), $_REQUEST);
                }
            }
            else
            {
                try
                {
                    $pagina = new $classe($_REQUEST);
                    ob_start();
                    $pagina->exibe($_REQUEST);
	                $conteudo = ob_get_contents();
	                ob_end_clean();
                }
                catch(Exception $e)
                {
                    ob_start();
                    if ($depura)
                    {
                        new VisaoExcecao($e);
                        $conteudo = ob_get_contents();
                    }
                    else
                    {
                        new Mensagem('erro', $e->getMessage());
                        $conteudo = ob_get_contents();
                    }
                    ob_end_clean();
                }
            }
        }
        else if (function_exists($metodo))
        {
            call_user_func($metodo, $_REQUEST);
        }
        else if (!empty($classe))
        {
            new Mensagem('erro', "Classe <b><i><u>{$classe}</u></i></b> não encontrada " . '.<br>' . "Verifique a classe ou o nome do arquivo.".'.');
        }
        
        if (!$estatico)
        {
            echo Pagina::obtCSSCarregado();
        }
        echo Pagina::obtJSCarregado();
        
        echo $conteudo;
    }

    /**
     * Execute internal method
     */
    public static function executa($classe, $metodo, $solicitacao, $pontofinal = null)
    {
        self::$id_solicitacao = uniqid();
        
        $ini = ConfigAplicativo::obt();
        $servico = isset($ini['geral']['servico_hist_solicitacao']) ? $ini['geral']['servico_hist_solicitacao'] : '\SystemRequestLogService'; 
        
        if (!empty($ini['geral']['request_log']) && $ini['geral']['request_log'] == '1')
        {
            if (empty($pontofinal) || empty($ini['geral']['tipos_hist_solicitacao']) || strpos($ini['geral']['tipos_hist_solicitacao'], $pontofinal) !== false)
            {
                self::$id_solicitacao = $servico::registra( $pontofinal );
            }
        }
        
        if (class_exists($classe))
        {
            if (method_exists($classe, $metodo))
            {
                $rf = new ReflectionMethod($classe, $metodo);
                if ($rf->isStatic())
                {
                    $response = call_user_func(array($classe, $metodo), $solicitacao);
                }
                else
                {
                    $response = call_user_func(array(new $classe($solicitacao), $metodo), $solicitacao);
                }
                return $response;
            }
            else
            {
                throw new Exception("O método {\"$classe::$metodo\"} não foi encontrado"); 
            }
        }
        else
        {
            throw new Exception("A classe {$classe} não foi encontrado");
        }
    }

    /**
     * Filter specific framework commands
     */
    public static function filtraEntrada()
    {
        if ($_REQUEST)
        {
            foreach ($_REQUEST as $chave => $valor)
            {
                if (is_scalar($valor))
                {
                    if ( (substr(strtoupper($valor),0,7) == '(SELECT') OR (substr(strtoupper($valor),0,6) == 'NOESC:'))
                    {
                        $_REQUEST[$chave] = '';
                        $_GET[$chave]     = '';
                        $_POST[$chave]    = '';
                    }
                }
                else if (is_array($valor))
                {
                    foreach ($valor as $sub_chave => $sub_valor)
                    {
                        if (is_scalar($sub_valor))
                        {
                            if ( (substr(strtoupper($sub_valor),0,7) == '(SELECT') OR (substr(strtoupper($sub_valor),0,6) == 'NOESC:'))
                            {
                                $_REQUEST[$chave][$sub_chave] = '';
                                $_GET[$chave][$sub_chave]     = '';
                                $_POST[$chave][$sub_chave]    = '';
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Configura callback roteador
     */
    public static function defRoteador(Callable $callback)
    {
        self::$roteador = $callback;
    }

    /**
     * Obtém callback roteador
     */
    public static function obtRoteador()
    {
        return self::$roteador;
    }

    //-------------------------------------------------------------------------------------------------------------------- 
    /**
     * Execute a specific method of a class with parameters
     *
     * @param $classe nome da classe
     * @param $metodo nome do método
     * @param $parametros array de parametros
     */
    public static function executaMetodo($classe, $metodo = NULL, $parametros = NULL)
    {
        self::vaiParaPagina($classe, $metodo, $parametros);
    }
    
    /**
     * Process request and insert the result it into template
     */
    public static function processaSolicitacao($template)
    {
        ob_start();
        NucleoAplicativo::rodar();
        $conteudo = ob_get_contents();
        ob_end_clean();
        
        $template = str_replace('{conteudo}', $conteudo, $template);
        
        return $template;
    }
     
    /**
     * Goto a page
     *
     * @param $classe nome da classe
     * @param $metodo nome do método
     * @param $parametros array de parametros
     */
    public static function vaiParaPagina($classe, $metodo = NULL, $parametros = NULL, $callback = NULL)
    {
        unset($parametros['static']);
        $consulta = self::constroiConsultaHttp($classe, $metodo, $parametros);
        
        Script::cria("__ageunet_vaipara_pagina('{$consulta}');", true, 1);
    }
    
    /**
     * Carrega a página
     *
     * @param $classe nome da classe
     * @param $metodo nome do método
     * @param $parametros array de parametros
     */
    public static function carregaPagina($classe, $metodo = NULL, $parametros = NULL)
    {
        $consulta = self::constroiConsultaHttp($classe, $metodo, $parametros);
        
        Script::cria("__ageunet_carrega_pagina('{$consulta}');", true, 1);
    }
    
    /**
     * Carrega a url da página
     *
     * @param $classe nome da classe
     * @param $metodo nome do método
     * @param $parametros array de parametros
     */
    public static function carregaURLPagina($consulta)
    {
        Script::cria("__ageunet_carrega_pagina('{$consulta}');", true, 1);
    }
    
    /**
     * Post data
     *
     * @param $classe nome da classe
     * @param $metodo nome do método
     * @param $parametros array de parametros
     */
    public static function postaDados($nomeForm, $classe, $metodo = NULL, $parametros = NULL)
    {
        $url = array();
        $url['classe']  = $classe;
        $url['metodo'] = $metodo;
        unset($parametros['classe']);
        unset($parametros['metodo']);
        $url = array_merge($url, (array) $parametros);
        
        Script::cria("__ageunet_post_dados('{$nomeForm}', '".http_build_query($url)."');");
    }
    
    /**
     * Build HTTP Query
     *
     * @param $classe nome da classe
     * @param $metodo nome do método
     * @param $parametros array de parametros
     */
    public static function constroiConsultaHttp($classe, $metodo = NULL, $parametros = NULL)
    {
        $url = array();
        $url['classe']  = $classe;
        if ($metodo)
        {
            $url['metodo'] = $metodo;
        }
        unset($parametros['classe']);
        unset($parametros['metodo']);
        $consulta = http_build_query($url);
        $callback = self::$roteador;
        $url_curta = null;
        
        if ($callback)
        {
            $consulta  = $callback($consulta, TRUE);
        }
        else
        {
            $consulta = 'inicio.php?'.$consulta;
        }
        
        if (strpos($consulta, '?') !== FALSE)
        {
            return $consulta . ( (is_array($parametros) && count($parametros) > 0) ? '&'.http_build_query($parametros) : '' );
        }
        else
        {
            return $consulta . ( (is_array($parametros) && count($parametros) > 0) ? '?'.http_build_query($parametros) : '' );
        }
    }
    
    /**
     * Recarrega o aplicativo
     */
    public static function recarrega()
    {
        Script::cria("__ageunet_vaipara_pagina('inicio.php')");
    }
    
    /**
     * Register URL
     *
     * @param $pagina URL to be registered
     */
    public static function registraPagina($pagina)
    {
        Script::cria("__ageunet_registra_estado('{$pagina}', 'user');");
    }
    
    /**
     * Handle Catchable Errors
     */
    public static function tratadorErro($erro_nro, $erro_str, $arquivo_erro, $linha_erro)
    {
        if ( $erro_nro === E_RECOVERABLE_ERROR )
        {
            throw new ErrorException($erro_str, $erro_nro, 0, $arquivo_erro, $linha_erro);
        }
        
        return false;
    }
    
    /**
     * Get request headers
     */
    public static function obtCabecalhos()
    {
        $cabecalhos = array();
        foreach ($_SERVER as $chave => $valor)
        {
            if (substr($chave, 0, 5) == 'HTTP_')
            {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($chave, 5)))));
                $cabecalhos[$header] = $valor;
            }
        }
        
        if (function_exists('getallheaders'))
        {
            $todoscabecalhos = getallheaders();
            
            if ($todoscabecalhos)
            {
                return $todoscabecalhos;
            }
            
            return $cabecalhos;
        }
        return $cabecalhos;
    }
    
    /**
     * Returns the execution id
     */
    public static function obtSolicitaId() 
    {
        return self::$id_solicitacao;
    }
}