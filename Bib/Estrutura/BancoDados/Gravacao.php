<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\BancoDados;

use Exception;
use Estrutura\BancoDados\Transacao;
use Estrutura\BancoDados\InterfaceGravacao;

/**
 * Classe RegistroAtivo
 * 
 * Esta é uma classe abstrata, portanto não será instanciada diretamente
 */
abstract class Gravacao extends InterfaceGravacao {
    
    protected $dados; 

    /**
     * Método __construct (construtor)
     */
    public function __construct($id = NULL)
    {
        if ($id) {
            $objeto = $this->carrega($id);
            if ($objeto) {
                $this->doArray($objeto->paraArray());
            }
        }
    }

    /**
     * Método __clone (duplica)
     */
    public function __clone()
    {
        unset($this->dados['id']);
    }

    /**
     * Método __set
     */
    public function __set($prop, $valor) 
    {
        if (method_exists($this, 'def_'.$prop)) {
            call_user_func(array($this, 'def_'.$prop), $valor);
        } else {
            if ($valor === NULL){
                unset($this->dados[$prop]);
            } else {
                $this->dados[$prop] = $valor; 
            }
        }
    }

    /**
     * Método __get
     */
    public function __get($prop) 
    {
        if (method_exists($this, 'obt_'.$prop)) {
            return call_user_func(array($this, 'obt_'.$prop));
        } else {
            if (isset($this->dados[$prop])){
                return $this->dados[$prop];
            } 
        }
    }

    /**
     * Método __isset
     */
    public function __isset($prop) {
        return isset($this->dados[$prop]);
    }

    /**
     * Método obtEntidade
     */
    private function obtEntidade()
    {
        $classe = get_class($this);
        return constant("{$classe}::NOMETABELA");
    }

    /**
     * Método doArray
     */
    public function doArray($dados) 
    {
        $this->dados = $dados;
    }

    /**
     * Método paraArray
     */
    public function paraArray() 
    {
        return $this->dados;
    }

    /**
     * Método grava
     * 
     * Neste método criamos várias instruções SQL de forma automática. 
     * Incluíndo INSERT, UPDATE
     */
    public function grava() 
    {
        $preparado = $this->prepara($this->dados);

        if (empty($this['id']) OR (!$this->carrega($this->id))) {
            if (empty($this->dados['id'])) {
                $this->id = $this->obtUltimo() + 1;
                $preparado['id'] = $this->id;
            }

            # cria uma instrução INSERT
            $sql = "INSERT INTO {$this->obtEntidade()} " . 
            '(' . implode(', ', array_keys($preparado)) . ' )';
            ' VALUES ' . 
            '(' . implode(', ', array_values($preparado)) . ' )';

        } else {
            # monta a string UPDATE
            $sql = "UPDATE {$this->obtEntidade()}";
            # monta os pares:coluna=valor
            if ($preparado) {
                foreach ($preparado as $coluna => $valor) {
                    if ($coluna !== 'id') {
                        $def[] = "{$coluna} = {$valor}";
                    } # fim do IF interno nível 3
                } # fim do FOREACH
            }# fim do IF nível 2

            $sql .= ' SET ' . implode(', ', $def);
            $sql .= ' WHERE id=' . (int) $this->dados['id'];
        }# fim do ELSE

        # obtém transação ativa
        if ($conexao = Transacao::obt()) {
            Transacao::hist($sql); ### RECURSO A SER IMPLEMENTADO: histórico (log)
            $resultado = $conexao->exec($sql);
            return $resultado;
        } else {
            throw new Exception('Não há transação ativa!');
        }
    } # FIM do método 'grava()'

    /**
     * Método carrega
     */
    public function carrega($id) 
    {
        # monta instrução SELECT
        $sql  = "SELECT * FROM {$this->obtEntidade()}";
        $sql .= ' WHERE id=' . (int) $id;

        if ($conexao = Transacao::obt()) {
            Transacao::hist($sql);
            $resultado = $conexao->query($sql);

            # caso tenha retornado algum dado
            if ($resultado) {
                # retorna os dados em forma de objeto
                $objeto = $resultado->fetchObject(get_class($this));
            }
            return $objeto;
        } else {
            throw new Exception('Não há transação ativa!');
        }
    } # FIM do método 'carrega($id)'

    /**
     * Método apaga
     */
    public function apaga($id = NULL) 
    {
        # O ID é o parâmetro ou a propriedade ID
        $id = $id ?? $this->id;

        # monta a string UPDATE
        $sql  = "DELETE FROM {$this->obtEntidade()}";
        $sql .= ' WHERE id=' . (int) $this->dados['id'];

        # obtém transação ativa
        if ($conexao = Transacao::obt()) {
            Transacao::hist($sql);
            $resultado = $conexao->exec($sql);
            return $resultado;
        } else {
            throw new Exception('Não há transação ativa!');
        }
    } # FIM do método 'apaga($id = NULL)'

    /**
     * Método localiza
     */
    public static function localiza($id)
    {
        $nomeclasse = get_called_class();
        $ra = new $nomeclasse;
        return $ra->carrega($id);
    }

    /**
     * Método obtUltimo
     */
    private function obtUltimo() 
    {
        if ($conexao = Transacao::obt()) {
            $sql  = "SELECT max(id) FROM {$this->obtEntidade()}";

            Transacao::hist($sql);
            $resultado = $conexao->query($sql);

            # retorna os dados do bd
            $linha = $resultado->fetch();
            return $linha[0];
        } else {
            throw new Exception('Não há transação ativa!');
        }
    } # FIM do método 'obtUltimo()'

    /**
     * Método prepara
     */
    public function prepara($dados) {
        $preparado = array();
        foreach ($dados as $chave => $valor) {
            if(is_scalar($valor)) {
                $preparado[$chave] = $this->escapa($valor);
            }
        }
        return $preparado;
    } # FIM do método 'prepara($dados)'

    /**
     * Método escapa
     * 
     * Este método está simplificado. É necessário implementar 
     * melhor este método por questões de segurança
     */
    public function escapa($valor) {
        # aqui apenas verificamos se é string ou que não está vazio
        if (is_string($valor) AND (!empty($valor))) {
            # Adiciona \ em aspas
            $valor = addslashes($valor);
            return "'$valor'";
        # já aqui verificamos se o valor é um booleano (VERDADEIRO ou FALSO)
        } else if(is_bool($valor)) { 
            return $valor ? 'TRUE' : 'FALSE';
        # caso seja diferente de string em branco retornamos o valor
        } else if ($valor !== '') {
            return $valor;
        # se for qualquer outra coisa retornamos nulo.
        } else {
            return "NULL";
        }
    } # FIM do método 'escapa($valor)'
}
