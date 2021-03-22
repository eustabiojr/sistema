<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 16/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Sessao;

/**
 * Classe Sessao 
 */
class Sessao 
{
    private static $agora;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * Método defValor
     */
    public static function defValor($var, $valor) 
    {
        $_SESSION[$var] = $valor;
    }

    /**
     * Método obtValor
     */
    public static function obtValor($var)
    {
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        }
    }

    /**
     * Método liberaSessao
     */
    public static function liberaSessao()
    {
        $_SESSION = array();
        session_destroy();
    }

    /**
     * Quando vamos definir o tempo de atividade?
     * 
     * A sessão é iniciada na página inicio.php
     * 
     * Quando devemos renovar o tempo de atividade?
     * 
     * O tempo de atividade deve ser renovado sempre que o usuário
     * estiver acessando as páginas em tempor menor que o tempo de 
     * atividade estabelecido na sessão.
     */
    public static function atualizaAtividade()
    {
        self::$agora = time();

        $carencia = self::$agora + (30);
        
        Sessao::defValor('tempo', $carencia);

        #echo "<p>: Carência: " . Sessao::obtValor('tempo') . ", agora: " . self::$agora . "</p>" . PHP_EOL;
    }

    # verificaForcaBruta()
    public static function verificaAtividade()
    {
        self::$agora = time();

        #echo "<p>: Carência: " . Sessao::obtValor('tempo') . ", agora: " . self::$agora . "</p>" . PHP_EOL;

        /**
         * Caso 'tempo' não seja menor que agora. 
         * 
         * agora: 30; 
         * atividade = agora - 10; 
         * 
         * atividade: 20;
         * 
         * atividade > agora;
         * 
         * resultado = agora - atividade;
         */
        if (Sessao::obtValor('tempo') > self::$agora) {

            self::atualizaAtividade();
            self::defValor('logado', TRUE);
            #echo "<p>Logado</p>" . PHP_EOL;
            return true;
        } else {

            self::defValor('logado', FALSE);

            #echo "<p>Não logado</p>" . PHP_EOL;
            return false;
        }
    }
}