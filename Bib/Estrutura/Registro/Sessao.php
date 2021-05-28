<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 16/03/2021
 **************************************************************************************/
# Espaço de nomes
namespace Estrutura\Registro;

use SessionHandlerInterface;

/**
 * Classe Sessao 
 */
class Sessao implements InterfaceRegistro
{
    private static $agora;

    /**
     * Método Construtor
     */
    public function __construct(SessionHandlerInterface $tratador = NULL, $caminho = NULL)
    {
        if ($caminho)
        {
            session_save_path($caminho);
        }

        if ($tratador)
        {
            session_set_save_handler($tratador, true);
        }

        // caso não exista sessão aberta
        if (!session_id()) 
        {
            session_start();
        }
    }

    /**
     * Retorna se o serviço está ativo
     */
    public static function ativado()
    {   
        if (!session_id())
        {
            return session_start();
        }
        return TRUE;
    }

    /**
     * Define o valor para a variável
     * @param $var Nome da variável
     * @param $valor Valor da variável
     */
    public static function defValor($var, $valor)
    {
        if (defined('NOME_APLICATIVO'))
        {
            $_SESSION[NOME_APLICATIVO][$var] = $valor;
        } else {
            $_SESSION[$var] = $valor;
        }
    }

    /**
     * Método obtValor
     * 
     * Retorna o valor para uma variável
     * @param $var Nome variável
     */
    public static function obtValor($var)
    {
        if (defined('NOME_APLICATIVO'))
        {
            if (isset($_SESSION[NOME_APLICATIVO][$var]))
            {
                return $_SESSION[NOME_APLICATIVO][$var];
            } 
        } else {
            if (isset($_SESSION[$var])) {
                return $_SESSION[$var];
            }
        }
    }

    /**
     * Limpa o valor para uma variável
     * @param $var Nome variável
     */
    public static function apagValor($var)
    {
        if (defined('NOME_APLICATIVO'))
        {
            unset($_SESSION[NOME_APLICATIVO][$var]);
        } else {
            unset($_SESSION[$var]);
        }
    }

    /**
     * Regenera id
     */
    public static function regenera()
    {
        session_regenerate_id();
    }

    /*********
     * Limpa sessão
     */
    public static function limpa()
    {
        self::liberaSessao();
    }

    /**
     * Método liberaSessao
     */
    public static function liberaSessao()
    {
        if (defined('NOME_APLICATIVO'))
        {
            $_SESSION[NOME_APLICATIVO] = array();
        } else {
            $_SESSION[] = array();
        }       
    }

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Método atualizaAtividade
     * 
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

        $carencia = self::$agora + (2 * 60 * 60);
        
        Sessao::defValor('tempo', $carencia);

        #echo "<p>: Carência: " . Sessao::obtValor('tempo') . ", agora: " . self::$agora . "</p>" . PHP_EOL;
    }

    # verificaForcaBruta()
    /**
     * Método verificaAtividade
     * 
     * Por razões de segurança este método deve ser usado para fazer logout, caso o usuário
     * fique inativo por muito tempo na página.
     */
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
            #echo "<p>Logado</p>" . PHP_EOL;
            return true;
        } else {
            
            self::defValor('logado', FALSE);

            #echo "<p>Não logado</p>" . PHP_EOL;
            return false;
        }
    }
}