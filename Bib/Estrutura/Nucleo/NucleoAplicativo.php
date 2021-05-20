<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Nucleo;

use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Util\MapaClasse;
use Estrutura\Bugigangas\Util\VisaoExcecao;
use Estrutura\Controle\Pagina;

use Exception;
use ReflectionMethod;

/**
 * Classe NucleoAplicativo
 * 
 * Estrutura básica do aplicativo
 */
class NucleoAplicativo
{
    private static $roteador;

    private static $router;
    private static $request_id;
    
    /**
     * Execute class/method based on request
     *
     * @param $depura Activate Exception debug
     */
    public static function rodar($depura = FALSE)
    {
        self::$request_id = uniqid();
        
        $ini = ConfigAplicativo::obt();
        $servico = isset($ini['general']['request_log_service']) ? $ini['general']['request_log_service'] : '\SystemRequestLogService';
        $classe   = isset($_REQUEST['classe'])    ? $_REQUEST['classe']   : '';
        $estatico  = isset($_REQUEST['estatico'])   ? $_REQUEST['estatico']  : '';
        $metodo  = isset($_REQUEST['metodo'])   ? $_REQUEST['metodo']  : '';
        
        $conteudo = '';
        set_error_handler(array('AdiantiCoreApplication', 'errorHandler'));
        
        if (!empty($ini['general']['request_log']) && $ini['general']['request_log'] == '1')
        {
            if (empty($ini['general']['request_log_types']) || strpos($ini['general']['request_log_types'], 'web') !== false)
            {
                self::$request_id = $servico::register( 'web');
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
                    $pagina = new $classe( $_REQUEST );
                    ob_start();
                    $pagina->exibe( $_REQUEST );
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
                        new Mensagem('error', $e->getMessage());
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
}