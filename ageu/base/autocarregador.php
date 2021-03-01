<?php
/**
 * Auto-carregador de classes
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 */
//---------------------------------------------------------------------------------------------------
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
    public function registra()
    {
        # echo "<p> Inicializado </p>" . PHP_EOL;
        
        spl_autoload_register(function($classe) {

            # echo '<pre>' . print_r($classe) . '</pre>' . PHP_EOL;

            $ce = explode("\\", $classe);
            $nome_classe = array_pop($ce);

            foreach($this->listaNamespace() as $namespace) {
                $caminho_parcial = str_replace("\\", DIRECTORY_SEPARATOR, $namespace);
                $caminho = '/' . $caminho_parcial . '/';
 
                /**
                 * Aqui estamos 'pegando' a pasta raiz de documentos do servidor web do site
                 * Neste caso, a definição da pasta raiz é automatica. Entretanto, temos a desvantagem
                 * de usar apenas uma pasta raiz.
                 * 
                 * Outra opção: Seria definir a pasta raiz na configuração para cada espaço de nomes.
                 * Esta última seria uma opção mais flexível.
                 */
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

    /**
     * Método listaNamespace
     */
    protected function listaNamespace() {
        return $this->espaco_nome;
    }
}

