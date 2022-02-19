<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Exception;

/**
 * Traço salva arquivo
 *
 * @version    7.1
 * @package    base
 * @author     Nataniel Rabaioli
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
trait TracoSalvaArquivo
{
    /**
     * Save file
     * @param $objeto      Active Record
     * @param $dados        Form data
     * @param $nome_entrada  Input field name
     * @param $caminho_alvo Target file path
     */
    public function salvaArquivo($objeto, $dados, $nome_entrada, $caminho_alvo)
    {
        $dados_arquivo = json_decode(urldecode($dados->$nome_entrada));
        
        if (isset($dados_arquivo->nomeArquivo))
        {
            $pk = $objeto->obtChavePrimaria();
            
            $caminho_alvo.= '/' . $objeto->$pk;
            $caminho_alvo = str_replace('//', '/', $caminho_alvo);
            
            $arquivo_origem = $dados_arquivo->nomeArquivo;
            $arquivo_alvo = strpos($dados_arquivo->nomeArquivo, $caminho_alvo) === FALSE ? $caminho_alvo . '/' . $dados_arquivo->nomeArquivo : $dados_arquivo->nomeArquivo;
            $arquivo_alvo = str_replace('tmp/', '', $arquivo_alvo);
            
            $classe = get_class($objeto);
            $armazena_obj = new $classe;
            $armazena_obj->$pk = $objeto->$pk;
            $armazena_obj->$nome_entrada = $arquivo_alvo;
            
            $apagArquivo = null;
            
            if (!empty($dados_arquivo->apagArquivo))
            {
                $armazena_obj->$nome_entrada = '';
                $dados_arquivo->nomeArquivo = '';
                
                if (is_file(urldecode($dados_arquivo->apagArquivo)))
                {
                    $apagArquivo = urldecode($dados_arquivo->apagArquivo);
                    
                    if (file_exists($apagArquivo))
                    {
                        unlink($apagArquivo);
                    }
                }
            }
    
            if (!empty($dados_arquivo->novoArquivo))
            {
                if (file_exists($arquivo_origem))
                {
                    if (!file_exists($caminho_alvo))
                    {
                        if (!mkdir($caminho_alvo, 0777, true))
                        {
                            throw new Exception('Permissão negada' . ': '. $caminho_alvo);
                        }
                    }
                    
                    // if the user uploaded a source file
                    if (file_exists($caminho_alvo))
                    {
                        // move to the target directory
                        if (! rename($arquivo_origem, $arquivo_alvo))
                        {
                            throw new Exception("Erro enquanto copiava o arquivo para {$arquivo_alvo}");
                        }
                        
                        $armazena_obj->$nome_entrada = $arquivo_alvo;
                    }
                }
            }
            elseif ($dados_arquivo->nomeArquivo != $apagArquivo)
            {
                $armazena_obj->$nome_entrada = $dados_arquivo->nomeArquivo;
            }
            
            $armazena_obj->grava();
            
            if ($armazena_obj->$nome_entrada)
            {
                $dados_arquivo->nomeArquivo = $armazena_obj->$nome_entrada;
                $dados->$nome_entrada = urlencode(json_encode($dados_arquivo));
            }
            else
            {
                $dados->$nome_entrada = '';
            }
            
            return $armazena_obj;
        }
    }
    
    /**
     * Save files
     * @param $objeto      Active Record
     * @param $dados        Form data
     * @param $nome_entrada  Input field name
     * @param $caminho_alvo Target file path
     * @param $arquivos_modelo Files Active Record
     * @param $campo_arquivo  File field in arquivos_modelo
     * @param $chave_estrangeira Foreign key to $objeto
     */
    public function salvaArquivos($objeto, $dados, $nome_entrada, $caminho_alvo, $arquivos_modelo, $campo_arquivo, $chave_estrangeira)
    {
        $pk = $objeto->obtChavePrimaria();
        
        $apagArquivos      = [];
        $form_arquivos    = [];
        $caminho_alvo  .= '/' . $objeto->$pk;
        $caminho_alvo   = str_replace('//', '/', $caminho_alvo);
        $objetos_final = [];
        
        if (isset($dados->$nome_entrada) AND $dados->$nome_entrada)
        {
            foreach ($dados->$nome_entrada as $chave => $info_arquivo)
            {            
                $dados_arquivo = json_decode(urldecode($info_arquivo));
                
                if (!empty($dados_arquivo->nomeArquivo))
                {
                    $arquivo_origem = $dados_arquivo->nomeArquivo;
                    $arquivo_alvo = $caminho_alvo . '/' . $dados_arquivo->nomeArquivo;
                    $arquivo_alvo = str_replace('tmp/', '', $arquivo_alvo);
                    
                    $form_arquivo = [];
                    $form_arquivo['apagArquivo']  = false;
                    $form_arquivo['idArquivo']   = (isset($dados_arquivo->idArquivo) AND $dados_arquivo->idArquivo) ? $dados_arquivo->idArquivo : null;
                    $form_arquivo['nomeArquivo'] = $dados_arquivo->nomeArquivo;
                    
                    if (!empty($dados_arquivo->apagArquivo))
                    {
                        $form_arquivo['apagArquivo'] = true;
                        
                        if (!empty($dados_arquivo->idArquivo))
                        {
                            $arquivo = $arquivos_modelo::find($dados_arquivo->idArquivo);
                            
                            if ($arquivo)
                            {
                                if ($arquivo->$campo_arquivo AND is_file($arquivo->$campo_arquivo))
                                {
                                    unlink( $arquivo->$campo_arquivo );
                                }
                                $arquivo->delete();
                            }
                        }
                    }
                    else if (!empty($dados_arquivo->idArquivo))
                    {
                        $objetos_final[] = $arquivos_modelo::find($dados_arquivo->idArquivo);
                    }
                    
                    if (!empty($dados_arquivo->novoArquivo))
                    {
                        if (file_exists($arquivo_origem))
                        {
                            if (!file_exists($caminho_alvo))
                            {
                                if (!mkdir($caminho_alvo, 0777, true))
                                {    
                                    throw new Exception('Permissão negada' . ': '. $caminho_alvo);
                                }
                            }
                        
                            // if the user uploaded a source file
                            if (file_exists($caminho_alvo))
                            {
                                // move to the target directory
                                if (! rename($arquivo_origem, $arquivo_alvo))
                                {
                                    throw new Exception("Erro enquanto copiava o arquivo para {$arquivo_alvo}");
                                }
                                
                                $arquivo_modelo = new $arquivos_modelo;
                                $arquivo_modelo->$campo_arquivo = $arquivo_alvo;
                                $arquivo_modelo->$chave_estrangeira = $objeto->$pk;
                                
                                $arquivo_modelo->grava();
                                $objetos_final[] = $arquivo_modelo;
                                
                                $pk_detail = $arquivo_modelo->obtChavePrimaria();
                                $form_arquivo['idArquivo'] = $arquivo_modelo->$pk_detail;
                                $form_arquivo['nomeArquivo'] = $arquivo_alvo;
                            }
                        }
                    }
                    
                    if ($form_arquivo and !$form_arquivo['apagArquivo'])
                    {
                        $form_arquivos[] = $form_arquivo;
                    }
                }
            }
            
            $dados->$nome_entrada = $form_arquivos;
        }
        
        return $objetos_final;
    }
    
    /**
     * Save files comma separated
     * @param $objeto      Active Record
     * @param $dados        Form data
     * @param $nome_entrada  Input field name
     * @param $caminho_alvo Target file path
     */
    public function salvaArquivosPorVirgula($objeto, $dados, $nome_entrada, $caminho_alvo)
    {
        $salva_arquivos = [];
        $apagArquivos   = [];
        $form_arquivos = [];
        
        $pk = $objeto->obtChavePrimaria();
        $caminho_alvo.= '/' . $objeto->$pk;
        
        if (isset($dados->$nome_entrada) AND $dados->$nome_entrada)
        {
            foreach ($dados->$nome_entrada as $chave => $info_arquivo)
            {            
                $dados_arquivo = json_decode(urldecode($info_arquivo));
                
                $arquivo_origem = $dados_arquivo->nomeArquivo;
                $arquivo_alvo = $caminho_alvo . '/' . $dados_arquivo->nomeArquivo;
                $arquivo_alvo = str_replace('tmp/', '', $arquivo_alvo);
                
                $salva_arquivo = $dados_arquivo->nomeArquivo;
                
                $form_arquivo = [];
                $form_arquivo['apagArquivo']  = false;
                $form_arquivo['idArquivo']   = (isset($dados_arquivo->idArquivo) AND $dados_arquivo->idArquivo) ? $dados_arquivo->idArquivo : null;
                $form_arquivo['nomeArquivo'] = $dados_arquivo->nomeArquivo;
                
                if (!empty($dados_arquivo->apagArquivo))
                {
                    $form_arquivo['apagArquivo'] = true;
                    $salva_arquivo = null;
                    
                    if (file_exists( urldecode($dados_arquivo->apagArquivo) ))
                    {
                        unlink( urldecode($dados_arquivo->apagArquivo) );
                    }
                }
                
                if (!empty($dados_arquivo->novoArquivo))
                {
                    if (file_exists($arquivo_origem))
                    {
                        if (!file_exists($caminho_alvo))
                        {
                            if (!mkdir($caminho_alvo, 0777, true))
                            {    
                                throw new Exception('Permissão negada' . ': '. $caminho_alvo);
                            }
                        }
                    
                        // if the user uploaded a source file
                        if (file_exists($caminho_alvo))
                        {
                            // move to the target directory
                            if (! rename($arquivo_origem, $arquivo_alvo))
                            {
                                throw new Exception("Erro enquanto copiava o arquivo para {$arquivo_alvo}");
                            }
                            
                            $form_arquivo['idArquivo'] = $arquivo_alvo;
                            $form_arquivo['nomeArquivo'] = $arquivo_alvo;
                            
                            $salva_arquivo = $arquivo_alvo;
                        }
                    }
                }
                
                if ($salva_arquivo)
                {
                    $salva_arquivos[] = $salva_arquivo;
                }
                
                if ($form_arquivo and !$form_arquivo['apagArquivo'])
                {
                    $form_arquivos[] = $form_arquivo;
                }                
            }
            
            $classe = get_class($objeto);
            $armazena_obj = new $classe;
            $armazena_obj->$pk = $objeto->$pk;
            $armazena_obj->$nome_entrada = implode(',', $salva_arquivos);
            $armazena_obj->grava();
            
            $dados->$nome_entrada = $form_arquivos;
        }
    }
}
