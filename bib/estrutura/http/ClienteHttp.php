<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
namespace Estrutura\Http;

use Exception;

/**
 * Basic HTTP Client solicitacao
 *
 * @version    7.1
 * @package    core
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ClienteHttp
{
    /**
     * Execute a HTTP solicitacao
     *
     * @param $url URL
     * @param $metodo method type (GET,PUT,DELETE,POST)
     * @param $params solicitacao body
     */
    public static function solicitacao($url, $metodo = 'POST', $params = [], $autorizacao = null)
    {
        $ch = curl_init();
        
        if ($metodo == 'POST' OR $metodo == 'PUT')
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_POST, true);
     
        }
        else if ($metodo == 'GET' OR $metodo == 'DELETE')
        {
            $url .= '?'.http_build_query($params);
        }
       
        $padroes = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_CUSTOMREQUEST => $metodo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CONNECTTIMEOUT => 10
        );
        
        if (!empty($autorizacao))
        {
            $padroes[CURLOPT_HTTPHEADER] = ['Authorization: '. $autorizacao];
        }
        
        curl_setopt_array($ch, $padroes);
        $saida = curl_exec ($ch);
        
        if ($saida === false)
        {
            throw new Exception( curl_error($ch) );
        }
        
        curl_close ($ch);
        
        $retorno = (array) json_decode($saida);
        
        if (json_last_error() !== JSON_ERROR_NONE)
        {
            throw new Exception('O retorno não é um JSON válido. Verifique a URL');
        }
        
        if (!empty($retorno['status']) && $retorno['status'] == 'error') {
            throw new Exception(!empty($retorno['data']) ? $retorno['data'] : $retorno['message']);
        }
        
        if (!empty($retorno['error'])) {
            throw new Exception($retorno['error']['message']);
        }
        
        if (!empty($retorno['errors'])) {
            throw new Exception($retorno['errors']['message']);
        }
        return $retorno['data'];
    }
}