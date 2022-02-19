<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 09/06/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Controle\Acao;
use Exception;

/**
 * Recipiente de diálogo JQuery
 * 
 * Pretendo ajustar essa classe para que ela use JavaScript puro em vez de JQuery
 * 
 * @version 0.1
 * @package widgets
 * @subpackage recipiente
 * @author Pablo Dall'Oglio (E modificado por Eustábio J. Silva Jr.)
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DialogoJS extends Elemento
{
    private $acoes;
    private $largura;
    private $altura;
    private $topo;
    private $esquerdo;
    private $modal;
    private $arrastavel;
    private $redimensionavel;
    private $usaBotaoOK;
    private $ordemPilha;
    private $acaoFechar;
    private $escapeFechar;
    private $classeDialogo;

    /**
     * Classe Construtor
     * 
     * @param $nome nome do widget
     */
    public function __construct()
    {
        parent::__construct('div');
        $this->usaBotaoOK = TRUE;
        $this->topo = NULL;
        $this->esquerdo = NULL;
        $this->modal = 'true';
        $this->arrastavel = 'true';
        $this->redimensionalvel = 'true';
        $this->ordemPilha = 2000;
        $this->fechaEscape = true;
        $this->classeDialogo = '';

        $this->{'id'} = 'dialogo_jspuro_' . mt_rand(1000000000, 1999999999);
        $this->{'style'} = "overflow: auto";
    }

    /**
     * Desabilita escape ao fechar
     */
    public function desabilitaEscape()
    {
        $this->fechaEscape = false;
    }

    /**
     * Desabilita rolagem
     */
    public function desabilitaRolagem()
    {
        $this->{'style'} = "overflow: hidden";
    }

    /**
     * Configura classe Dialogo
     * 
     * @param $classe nome classe
     */
    public function defClasseDialogo($classe)
    {
        $this->classeDialogo = $classe;
    }

    /**
     * Configura a ação fechar
     */
    public function defAcaoFechar(Acao $acao)
    {
        if ($acao->ehEstatico()) {
            $this->acaoFechar = $acao;
        } else {
            $acao_string = $acao->paraString();
            throw new Exception("A ação {$acao_string} deve ser estática para ser usada em " . __METHOD__);
        }
    }

    /**
     * Define se usaremos botão OK
     * @param $booleano booleano
     */
    public function defUsaBotaoOK($bool) 
    {
        $this->usaBotaoOK = $bool;
    }

    /**
     * Define o titulo do diálogo
     * @param $titulo titulo
     */
    public function defTitulo($titulo)
    {
        $this->{'title'} = $titulo;
    }

    /**
     * Liga/Desliga modal
     * @param $modal Booleano
     */
    public function defModal($bool)
    {
        $this->modal = $bool ? 'true' : 'false';
    }

    /**
     * Liga/Desliga redimensionamento
     * @param $modal Booleano
     */
    public function defRedimensionavel($bool)
    {
        $this->redimensionavel = $bool ? 'true' : 'false';
    }

    /**
     * Liga/Desliga arrastável
     * @param $modal Booleano
     */
    public function defArrastavel($bool)
    {
        $this->arrastavel = $bool ? 'true' : 'false';
    }

    /**
     * Retorna o ID do elemento
     */
    public function obtId()
    {
        return $this->{'id'};
    }

    /**
     * Define o tamanho do diálogo
     * 
     * @param $largura largura
     * @param $altura altura
     */
    public function defTamanho($largura, $altura)
    {
        $this->largura = $largura < 1 ? "\$(window).width() * $largura" : $largura;

        if (is_null($altura)) {
            $this->altura = "'auto'";
        } else {
            $this->altura = $altura < 1 ? "\$(window).height() * $altura" : $altura;
        }
    }

    /**
     * Define a largura mínima da janela entre percentual e absoluto
     * 
     * @param $porcentagem largura
     * @param $absoluto largura
     */
    public function defLarguraMin($porcentagem, $absoluto)
    {
        $this->largura = "Math.min(\$(window).width() * $porcentagem, $absoluto)";
    }

    /**
     * Define a posição do diálogo
     * @param $esquerdo esquerdo
     * @param $topo topo
     */
    public function defPosicao($esquerdo, $topo)
    {
        $this->esquerdo = $esquerdo;
        $this->topo = $topo;
    }

    /**
     * Adiciona um obtão JS ao diálgo
     * 
     * @param $rotulo rótulo do botão
     * @param $acao Ação JS
     */
    public function adicAcao($rotulo, $acao)
    {
        $this->acoes[] = array($rotulo, $acao);
    }

    /**
     * Define a ordem da pilha (zIndex)
     * 
     * @param $ordem Ordem da pilha
     */
    public function defOrdemPilha($ordem)
    {
        $this->ordemPilha = $ordem;
    }

    /**
     * Exibe o widget na tela
     */
    public function exibe()
    {
        $codigo_acao = '';

        if ($this->acoes) {
            foreach ($this->acoes as $array_acao) {
                $rotulo = $array_acao[0];
                $acao   = $array_acao[1];
                $codigo_acao .= "\"{$rotulo}\": function() { $acao },";
            }
        }

        $botao_ok = '';

        if ($this->usaBotaoOK) {
            $botao_ok = ' OK: function() { document.querySelector(this).remove(); }';
        }

        $esquerdo = $this->esquerdo ? $this->esquerdo : 0;
        $topo     = $this->topo     ? $this->topo : 0;

        $string_pos = '';
        $id = $this->{'id'};

        $acao_string = 'undefined'; # não pode ser função, devido a ele ser testado dentro de gdialogojquery.js

        if (isset($this->acaoFechar)) {
            $acao_string = $this->acaoFechar->serializa(FALSE);
            $acao_fechar = "function() { __ageunet_ajax_exec('{$acao_string}') }";
        }

        $escapa_ao_fechar = $this->escapeFechar ? 'true' : 'false';
        parent::adic(Script::cria("gdialogojspuro_inicio( '#{$id}', {$this->modal}, {$this->arrastavel}, {$this->redimensionavel}, {$this->largura},
        {$this->altura}, {$topo}, {$esquerdo}, {$this->ordemPilha}, { {$codigo_acao} {$botao_ok} }, $acao_fechar, $escapa_ao_fechar, '{$this->classeDialogo}' ); ", FALSE));
        parent::exibe();
    }

    /**
     * Fecha o diálogo
     */
    public function fecha()
    {
        parent::adic(Script::cria('document.querySelector( "#' . $this->{'id'} . '" ).remove();', false));
    }

    /**
     * Fecha janela pelo ID
     */
    public static function fechaPeloId($id)
    {
        Script::cria('document.querySelector( "#' . $id . '" ).remove();');
    }

    /**
     * Fecha todos os diálogos GDialogoJQuery
     */
    public static function fechaTudo()
    {
        if (!isset($_REQUEST['pesquisa_ajax']) OR $_REQUEST['pesquisa_ajax'] !== '1') {
            # 
            Script::cria( ' document.querySelector(\'[widget="GJanela"]\').remove(); ' );
        }
    }
}