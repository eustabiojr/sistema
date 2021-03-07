<?php
/********************************************************************************************
 * Sistema - Rest
 * 
 * Autor: Eustábio Júnior
 * Data: 07/03/2021
 ********************************************************************************************/

 header('Content-Type: application/json; charset=UTF-8');

 # Carregamento automático das classes da estrutura do sistema
include_once "Bib/Estrutura/Nucleo/AutoCarregadorEstrutura.php";
$ce = new Estrutura\Nucleo\AutoCarregadorEstrutura();
$ce->adicEspacoNome('Estrutura','Bib/Estrutura');
$ce->registra();

# Carregamento automático do aplicativo
include_once "Bib/Estrutura/Nucleo/AutoCarregadorAplic.php";
$ca = new Estrutura\Nucleo\AutoCarregadorAplic();
$ca->adicPasta('Aplicativo/Controladores');
$ca->adicPasta('Aplicativo/Modelos');
$ca->adicPasta('Aplicativo/Servicos');
$ca->registra();

/**
 * Classe ExemploServidorRest
 */
class ExemploServidorRest {

    /**
     * Método executa
     */
    public static function executa($requisicao) 
    {
        $classe = isset($requisicao['classe']) ?? '';
        $metodo = isset($requisicao['metodo']) ?? '';
        $resposta = NULL;

        try {
            if (class_exists($classe)) {
                if (method_exists($classe, $metodo)) {
                    $resposta = call_user_func(array($classe, $metodo), $requisicao);
                    return json_encode(array('status' => 'erro', 'dados' => $resposta)); 
                } else {
                    $mensagem_erro = "Método {$classe}::{$metodo} não encontrado";
                    return json_encode(array('status' => 'erro', 'dados' => $mensagem_erro));
                }
            } else {
                $mensagem_erro = "Classe {$classe} não encontrada";
                return json_encode(array('status' => 'erro', 'dados' => $mensagem_erro)); 
            }
        } catch (Exception $e) {
            return json_encode(array('status' => 'erro', 'dados' => $e->getMessage()));
        }
    }
}

//-------------------------------------------------------------------------------------------------- 
print ExemploServidorRest::executa($_REQUEST);