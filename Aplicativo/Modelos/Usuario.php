<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 20/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Gravacao;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Sessao\Sessao;

/**
 * Classe Estado
 */
class Usuario extends Gravacao {
    const NOMETABELA = 'usuario';

    public function fazLogin($nome, $senha)
    {
        $conexao = Transacao::obt();

        # limpando as strings
        $nome  = $this->fazLimpesa($nome);
        $senha = md5($this->fazLimpesa($senha));
        
        # usando consultas preparadas (é mais seguro)
        $sql = 'SELECT * FROM usuario WHERE nome = :nome AND senha = :senha'; #  LIMIT 1
        $declaracao = $conexao->prepare($sql);
        $declaracao->bindValue(':nome', $nome);
        $declaracao->bindValue(':senha', $senha);

        #$resultado = $declaracao->fetchAll(PDO::FETCH_ASSOC);
        $declaracao->execute();

        # se retornar algum registro
        if ($declaracao->rowCount() == 1) {

            
            /*
            # verificar se o usuario esta bloqueado
            if (verifica_forca_bruta($id_usuario, $sql) == true) {

                return false;
            } else {

            }*/
            return true;
        }
    }

    /**
     * Método fazLimpesa
     * 
     * Faz a limpeza da string passada
     */
    private function fazLimpesa($string)
    {
        # Expressões regulares
        # |[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i
        return $nome = preg_replace('/[^[:alnum:]_]/', '', $string); # /[^0-9]+/, # /[^a-zA-Z0-9_\-]+/
    }
}