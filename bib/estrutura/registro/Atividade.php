<?php
/** ***********************************************************************************
 * Sistema Agenet
 * 
 * Data: 19/02/2022
 **************************************************************************************/

use Estrutura\Registro\Sessao;

/**
 * Classe Atividade
 * 
 * Esta classe tem como objetivo monitorar a interação do usuário com a página. Caso, o usuário 
 * deixe de interagir por mais tempo que o definido, a sessão será expirada automáticamente.
 * 
 * @author Eustábio J. Silva Jr. 
 * Data: 19/02/2022
 */
class Atividade 
{
    private static $agora;

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
     * estiver acessando as páginas em tempo menor que o tempo de 
     * atividade estabelecido na sessão.
     * 
     * NOTA: CERTAMENTE, CRIAREMOS UMA NOVA CLASSE PARA IMPLEMENTAR ESTE RECURSO (Eustabio Jr. em 19/02/2022)
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
            
            Sessao::defValor('logado', FALSE);

            #echo "<p>Não logado</p>" . PHP_EOL;
            return false;
        }
    }
}