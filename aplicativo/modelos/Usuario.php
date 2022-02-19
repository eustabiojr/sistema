<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 20/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Gravacao;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;

/**
 * Classe Estado
 */
class Usuario extends Gravacao {
    const NOMETABELA = 'usuario';

    private $usuario;
    private $senha;

    /**
     * Método defUsuario
     */
    public function defUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Método obtUsuario
     */
    public function obtUsuario()
    {
        return $this->usuario;
    } 
    
    /**
     * Método defUsuario
     */
    public function defSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * Método obtUsuario
     */
    public function obtSenha()
    {
        return $this->senha;
    } 

    public function validaEntrada()
    {
        $conexao = Transacao::obt();

        try {
            if (isset($this->usuario) AND isset($this->senha)) {
                # limpando as strings
                $nome  = $this->fazLimpesa($this->obtUsuario());
                $senha = md5($this->fazLimpesa($this->obtSenha()));
                
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
            } else {
                throw new Exception('Dados de usuário não definidos!');
            }
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
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
