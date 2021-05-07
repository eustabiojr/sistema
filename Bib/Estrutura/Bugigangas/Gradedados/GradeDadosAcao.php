<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Bugigangas\Gradedados;

use Estrutura\Controle\Acao;
use Exception;

/**
  * Classe GradeDadosAcao
  */
 class GradeDadosAcao extends Acao
 {
    private $campo;
    private $campos;
    private $imagem;
    private $rotulo;
    private $classeBotao;
    private $usaBotao;
    private $exibeCondicao;

    /**
     * Método construtor da classe
     * @param $acao Callback a ser executado
     * @param $parametros = array de parametros
     */
    public function __construct($acao, $parametros = null) 
    {
        parent::__construct($acao, $parametros);

        if ($parametros) {
            $this->defCampos(parent::obtCampoParametros());
        }
    }

    /**
     * Define qual propriedade de Registro Ativo será passada junto com a ação
     * @param $campo Propriedade do Registro Ativo
     */
    public function defCampo($campo)
    {
        $this->campo = $campo;

        $this->defParametro('chave', '{' . $campo . '}');
        $this->defParametro($campo,  '{' . $campo . '}');
    }

    /**
     * Define qual propriedade de Registro Ativo será passada junto com a ação
     * @param $campo Propriedade do Registro Ativo
     */
    public function defCampos($campos)
    {
        $this->campos = $campos;

        if ($campos) {
            if (empty($this->campo) && empty(parent::obtParametro('chave'))) {
                $this->defParametro('chave', '{'.$campos[0].'}');
            }

            foreach ($campos as $campo) {
                $this->defParametro($campo, '{'.$campo.'}');
            }
        }
    }

    /**
     * Retorna a propriedade de Registro Ativo que será passada junto com a ação
     */
    public function obtCampo()
    {
        return $this->campo;
    }

    /**
     * Retorna a propriedade de Registro Ativo que será passada junto com a ação
     */
    public function obtCampos()
    {
        return $this->campos;
    }

    /**
     * Retorna se existe pelo menos um campo definido
     */
    public function campoDefinido()
    {
        return (!empty($this->campo) OR !empty($this->campos));
    }

    /**
     * Define um ícone para a ação
     * @param $imagem O caminho da imagem
     */
    public function defImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    /**
     * Retorna o ícone da ação
     */
    public function obtImagem()
    {
        return $this->imagem;
    }

    /**
     * Define o rótulo para a ação
     * @param $rotulo Uma string contendo o texto do rótulo
     */
    public function defRotulo($rotulo)
    {
        $this->rotulo = $rotulo;
    }

    /**
     * Retorna o rótulo para a ação
     */
    public function obtRotulo()
    {
        return $this->rotulo;
    }

    /**
     * Define a classe do botão
     * @param $classeBotao Uma string contendo a classe css do botão
     */
    public function defClasseBotao($classeBotao)
    {
        $this->classeBotao = $classeBotao;
    }

    /**
     * Retorna a classe do botão
     */
    public function obtClasseBotao()
    {
        return $this->classeBotao;
    }

    /**
     * Define se a ação usará um botão normal
     * @param $usaBotao Um booleano
     */
    public function defUsaBotao($usaBotao)
    {
        $this->usaBotao = $usaBotao;
    }

    /**
     * Retorna se a ação usará um botão normal
     */
    public function obtUsaBotao()
    {
        return $this->usaBotao;
    }

    /**
     * Define um callback que deve ser válido para exibir a ação
     * @param Callback $exibeCondicao Condição de exibição da ação
     */
    public function defExibeCondicao($exibeCondicao)
    {
        $this->exibeCondicao = $exibeCondicao;
    }

    /**
     * Retorna a condição de exibição da ação
     */
    public function obtExibeCondicao()
    {
        return $this->exibeCondicao;
    }

    /**
     * Prepara a ação para uso sobre o objeto
     * @param $objeto Objeto dados
     */
    public function prepara($objeto)
    {
        if (!$this->campoDefinido()) {
            throw new Exception('O campo para aço ' . parent::paraString() . '.<br>' . 'Use o método defCampo()');
        }

        if ($campo = $this->obtCampo()) {
            if (!isset($objeto->$campo)) {
                throw new Exception("O campo {$campo} não existe ou contém valor NULO");
            }
        }

        if ($campos = $this->obtCampos()) {
            $campo = $campos[0];

            if (!isset($objeto->$campo)) {
                throw new Exception("O campo {$campo} não existe ou contém valor NULO");
            }
        }
        return parent::prepara($objeto);
    }

    /**
     * Converte a ação em uma URL
     * @param $formata_acao = formata ação com documento ou javascript (ajax=no)
     */
    public function serialize($formata_acao = TRUE)
    {
        if (\is_array($this->acao) AND is_object($this->acao[0]))
        {
            if (isset($_REQUEST['offset'])) {
                $this->defParametro('offset', $_REQUEST['offset']);
            }

            if (isset($_REQUEST['offset'])) {
                $this->defParametro('offset', $_REQUEST['offset']);
            }

            if (isset($_REQUEST['limite'])) {
                $this->defParametro('limite', $_REQUEST['limite']);
            }

            if (isset($_REQUEST['pagina'])) {
                $this->defParametro('pagina', $_REQUEST['pagina']);
            }

            if (isset($_REQUEST['primeira_pagina'])) {
                $this->defParametro('primeira_pagina', $_REQUEST['primeira_pagina']);
            }

            if (isset($_REQUEST['ordem'])) {
                $this->defParametro('ordem', $_REQUEST['ordem']);
            }
        }
        if (parent::ehEstatico()) {
            $this->defParametro('estatico', '1');
        }
        return parent::serializa($formata_acao);
    }
 }