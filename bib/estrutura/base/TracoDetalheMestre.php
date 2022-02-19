<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Estrutura\Registro\Sessao;
use Exception;

/**
 * Master Detail Trait
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
trait TracoDetalheMestre
{
    /**
     * Store an item from details session into database
     * @param $modelo Model class name
     * @param $chave_estrangeira Detail foreign key name
     * @param $objeto_mestre Master object
     * @param $id_detalhe Detail key in session
     * @param $transformador Function to be applied over the objects
     */
    public function gravaItens($modelo, $chave_estrangeira, $objeto_mestre, $id_detalhe, Callable $transformador = null)
    {
        $chavep_mestre  = $objeto_mestre->obtChavePrimaria();
        $id_mestre      = $objeto_mestre->$chavep_mestre;
        $detalhe_objetos = [];
        $detalhe_itens   = Sessao::obtValor("{$id_detalhe}_itens");
        
        if ($detalhe_itens) 
        {
            $id_detalhes = [];
            foreach ($detalhe_itens as $chave => $item)
            {
                foreach ($item as $chave_item => $valor)
                {
                    unset($item[$chave_item]);
                    $item[str_replace("{$id_detalhe}_", '', $chave_item)] = $valor;
                }
                
                $detalhe_objeto = new $modelo;
                $detalhe_objeto->doArray($item);
                $chavep_detalhe   = $detalhe_objeto->obtChavePrimaria();
                
                if (is_int($chave))
                { 
                    $detalhe_objeto->$chavep_detalhe = $chave;
                }
                
                $detalhe_objeto->$chave_estrangeira = $id_mestre;
                
                if ($transformador)
                {
                    call_user_func($transformador, $objeto_mestre, $detalhe_objeto);
                }
                
                $detalhe_objeto->__session__id = $chave;
                $detalhe_objeto->grava();
                $detalhe_objetos[] = $detalhe_objeto;
                $id_detalhes[] = $detalhe_objeto->$chavep_detalhe;
            }
            
            $repositorio = $modelo::where($chave_estrangeira, '=', $id_mestre);
            if ($id_detalhes)
            {
                $repositorio->where($chavep_detalhe, 'not in', $id_detalhes);
            }
            $repositorio->apaga(); 
        }
        else
        {
            $modelo::where($chave_estrangeira, '=', $id_mestre)->apaga();
        }
        
        return $detalhe_objetos;
    }
    
    /**
     * Load items for detail into session
     * @param $modelo Model class name
     * @param $chave_estrangeira Detail foreign key name
     * @param $objeto_mestre Master object
     * @param $id_detalhe Detail key in session
     * @param $transformador Function to be applied over the objects
     */
    public function carregaItens($modelo, $chave_estrangeira, $objeto_mestre, $id_detalhe, Callable $transformador = null)
    {
        $chavep_mestre  = $objeto_mestre->obtChavePrimaria();
        $id_mestre    = $objeto_mestre->$chavep_mestre;
        $detalhe_itens = [];
        $objetos      = $modelo::where($chave_estrangeira, '=', $id_mestre)->load();
        
        if ($objetos)
        {
            foreach ($objetos as $detalhe_objeto)
            {
                $chavep_detalhe  = $detalhe_objeto->obtChavePrimaria();
                $objeto_array = $detalhe_objeto->toArray();
                
                $itens = [];
                foreach ($objeto_array as $atributo => $valor) 
                {
                    $itens["{$id_detalhe}_{$atributo}"] = $valor;
                }
                
                if ($transformador)
                {
                    $itens_objeto = (object) $itens;
                    call_user_func($transformador, $objeto_mestre, $detalhe_objeto, $itens_objeto);
                    $itens = (array) $itens_objeto; 
                }
                
                $detalhe_itens[$detalhe_objeto->$chavep_detalhe] = $itens;
            }    
        }
        
        Sessao::obtValor("{$id_detalhe}_items", $detalhe_itens);
        
        return $objetos;
    }
}
