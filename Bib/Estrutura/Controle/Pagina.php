<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 01/03/2021
 **************************************************************************************/

# Espaço de nomes
namespace Estrutura\Controle;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe Pagina 
 * 
 * Esta classe futuramente deverá extender a classe Elemento.
 */
class Pagina extends Elemento {

    /**
     * Método __construct
     */
    public function __construct()
    {
        parent::__construct('div');
    }

    /**
     * Método exibe
     */
    public function exibe() {

        # Só entra no IF caso exista dados GET
        if ($_GET) {

            $classe = $_GET['classe'] ?? NULL;
            $metodo = $_GET['metodo'] ?? NULL;

            # caso a variável $classe esteja definida, entra no IF
            if ($classe) {

                /**
                 * testa se o nome da classe fornecido pelo URI é igual ao nome da classe
                 * do objeto atual. Se for igual retorna para na variável $objeto, o objeto 
                 * atual. Caso contrário retorna uma nova instancia da classe requisitada.
                 */
                $objeto = $classe == get_class($this) ? $this : new $classe; # 'Sim' : 'Não';
                
                if (method_exists($objeto, $metodo)) {

                    /**
					 * Essa função é interessante. Pois com ela podemos chamar o método classe
					 * por meio de parametros enviados pela URL. Ou seja, chamamos aquela 
                     * classe que herda a classe 'Pagina', e seus métodos.
					 */
                    call_user_func(array($objeto, $metodo), $_GET);
                }
            }
        }
        parent::exibe();
    }
}

