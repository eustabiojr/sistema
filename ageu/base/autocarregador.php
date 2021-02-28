<?php
/**
 * Auto-carregador de classes
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 */

/**
 * Pretendo criar uma variável array para registrar os namespace que serão consultados 
 * durante o carregamento das classes
 */
class AutoCarregadorClasses {

    private $espaco_nome = []; 

    /**
     * Construtor
     * 
     * Lembrete: a função spl_autoload_register só será chamada quando encontrar
     * a tentativa de instanciar uma classe.
     */
    public function inicializa($classe)
    {
        #echo "<p> Inicializado </p>" . PHP_EOL;
        
        spl_autoload_register(function($classe) {

            #echo '<pre>' . print_r($classe) . '</pre>' . PHP_EOL;

            $ce = explode("\\", $classe);
            $nome_classe = array_pop($ce);

            foreach($this->listaNamespace() as $namespace) {
                $caminho_parcial = str_replace("\\", "/", $namespace);
                $caminho = '/' . $caminho_parcial . '/';
 
                $fonte = $_SERVER['DOCUMENT_ROOT'] . $caminho . strtolower($nome_classe) . '.php';
                if (file_exists($fonte)) { 
                    #echo '<pre>' . $fonte . '</pre>' . PHP_EOL;
                    include_once $fonte;
                }
                #echo "<p>Espaço de nome (2): " . $caminho_parcial . "</p>" . PHP_EOL;
            }
        });
    }

    /**
     * Método adicNamespace
     */
    public function adicNamespace($namespace) {
        $this->espaco_nome[] = $namespace;
    }

    protected function listaNamespace() {
        return $this->espaco_nome;
    }
}

