<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Util;

#use Adianti\Widget\Container\TScroll;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Recipiente\Tabela;
use Exception;

/**
 * Exception visualizer
 *
 * @version    7.1
 * @package    widget
 * @subpackage util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class VisaoExcecao
{
    /**
     * Constructor method
     */
    function __construct(Exception $e)
    {
        $erro_array = $e->getTrace();
        $tabela = new Tabela;
        $linha=$tabela->adicLinha();
        $linha->adicCelula('<b>' . $e->getMessage(). '</b><br>');
        $linha=$tabela->adicLinha();
        $linha->adicCelula('&nbsp;');
        
        foreach ($erro_array as $erro)
        {
            $arquivo = isset($erro['file']) ? $erro['file'] : '';
            $linha = isset($erro['line']) ? $erro['line'] : '';
            $arquivo = str_replace(CAMINHO, '', $arquivo);
            
            $linha=$tabela->adicLinha();
            $linha->adicCelula('File: '.$arquivo. ' : '. $linha);
            $linha=$tabela->adicLinha();
            $args = array();
            if ($erro['args'])
            {
                foreach ($erro['args'] as $arg)
                {
                    if (is_object($arg))
                    {
                        $args[] = get_class($arg). ' object';
                    }
                    else if (is_array($arg))
                    {
                        $array_param = array();
                        foreach ($arg as $valor)
                        {
                            if (is_object($valor))
                            {
                                $array_param[] = get_class($valor);
                            }
                            else if (is_array($valor))
                            {
                                $array_param[] = 'array';
                            }
                            else
                            {
                                $array_param[] = $valor;
                            }
                        }
                        $args[] = implode(',', $array_param);
                    }
                    else
                    {
                        $args[] = (string) $arg;
                    }
                }
            }
            $classe = isset($erro['class']) ? $erro['class'] : '';
            $tipo  = isset($erro['type'])  ? $erro['type']  : '';
            
            $linha->adicCelula('&nbsp;&nbsp;<i>'.'<font color=green>'.$classe.'</font>'.
                                            '<font color=olive>'.$tipo.'</font>'.
                                            '<font color=darkblue>'.$erro['function'].'</font>'.
                                            '('.'<font color=maroon>'.implode(',', $args).'</font>'.')</i>');
        }
        
        ob_start();
        $tabela->exibe();
        $conteudo = ob_get_clean();
        
        new Mensagem('erro', $conteudo, NULL, "Exceção");
    }
}
