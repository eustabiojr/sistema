<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\BancoDados;

/**
 * Classe Criterio
 */
class Criterio extends Expressao
{
    # propriedades da classe
    private $expressoes;
    private $operadores;
    private $propriedades;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        $this->expressoes = [];
        $this->filtros    = array();

        $this->propriedades['order']     = '';
        $this->propriedades['offset']    = 0;
        $this->propriedades['direction'] = '';
        $this->propriedades['group']     = '';
    }

    /**
     * Cria criterio do array de filtros
     */
    public static function cria($filtros_simples, $propriedades = null)
    {
        $criterio = new Criterio;
        if ($filtros_simples)
        {
            foreach ($filtros_simples as $operador_esquerdo => $operador_direito)
            {
                $criterio->adic(new Filtro($operador_esquerdo, '=', $operador_direito));
            }
        }

        if ($propriedades)
        {
            foreach ($propriedades as $propriedade => $valor)
            {
                if (!empty($valor))
                {
                    $criterio->defPropriedade($propriedade, $valor);
                }
            }
        }
    }

    /**
     * Quando clonar critério
     */
    public function __clone()
    {
        $expressoesNovas = [];
        foreach ($this->expressoes as $chave => $valor)
        {
            $expressoesNovas[$chave] = clone $valor;
        }
        $this->expressoes = $expressoesNovas;
    }

    /**
     * Método adic
     */
    public function adic(Expressao $expressao, $operador = self::OPERADOR_E)
    {
        # na primeira vez não precisamos concatenar
        if (empty($this->expressoes)) {
            $operador_logico = NULL;
        }

        $this->expressoes[] = $expressao;
        $this->operadores[] = $operador;
    }

    /** 
    * Retorna se o critério está vazio 
    * 
    */
    public function estaVazio()
    {
        return count($this->expressoes) == 0;
    }

    /**
     * Retorna as variáveis preparadas
     */
    public function obtVarsPreparadas()
    {
        $varsPreparadas = [];
        if (is_array($this->expressoes))
        {
            if (count($this->expressoes) > 0)
            {
                foreach ($this->expressoes as $expressao)
                {
                    $varsPreparadas = array_merge($varsPreparadas, (array) $expressao->obtVarsPreparadas());
                }
                return $varsPreparadas;
            }
        }
    }

    /**
     * Método despeja
     */
    public function despeja($preparado = FALSE) 
    {
        # concatena a lista de expressões
        if (is_array($this->expressoes))
        {
            if (count($this->expressoes) > 0)
            {
                $resultado = '';
                foreach ($this->expressoes as $i => $expressao)
                {
                    $operador = $this->operadores[$i];
                    # concatena o operador com sua respectiva expressão
                    $resultado .= $operador . $expressao->despeja($preparado) . ' ';
                }
            }
            $resultado = trim($resultado);
            if ($resultado) 
            {
                return "({$resultado})";
            }
        }
    }

    /**
     * Método defPropriedade
     */
    public function defPropriedade($propriedade, $valor)
    {
        if (isset($valor)) {
            $this->propriedades[$propriedade] = $valor;
        } else {
            $this->propriedades[$propriedade] = NULL;
        }
    }

    /**
     * redefine propriedades de critério
     */
    public function redefinePropriedades()
    {
        $this->propriedades['limit'] = NULL;
        $this->propriedades['order'] = NULL;
        $this->propriedades['offset'] = NULL;
        $this->propriedades['group'] = NULL;
    }

    /**
     * define propriedades apartir de array
     */
    public function defPropriedades($propriedades)
    {
        if (isset($propriedades['order']) AND $propriedades['order'])
        {
            $this->propriedades['order'] = addslashes($propriedades['order']);
        }
        if (isset($propriedades['offset']) AND $propriedades['offset'])
        {
            $this->propriedades['offset'] = addslashes($propriedades['offset']);
        }
        if (isset($propriedades['direction']) AND $propriedades['direction'])
        {
            $this->propriedades['direction'] = addslashes($propriedades['direction']);
        }
    }    

    /**
     * Método obtPropriedade
     */
    public function obtPropriedade($propriedade)
    {
        if (isset($this->propriedades[$propriedade])) {
            return $this->propriedades[$propriedade];
        }
    }
}
