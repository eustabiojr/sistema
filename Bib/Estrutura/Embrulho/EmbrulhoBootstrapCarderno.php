<?php
namespace Estrutura\Embrulho;

use Ageunet\Widgets\Recipiente\GCaderno;
use Ageunet\Widgets\Base\GElemento;

/**
 * Decorador de grade de dados bootstrap para Ageunet Framework
 * 
 * @version 0.1
 * @package embalagem
 * @author Eustábio J. Silva Jr.
 * @author Pablo Dall'Oglio
 * @license http://www.adianti.com.br/framework-license
 * @wrapper GCarderno
 */
class EmbrulhoBootstrapCarderno
{
    private $decorado;
    private $propriedades;
    private $sentido;
    private $divisoes;

    /**
     * Método construtor
     */
    public function __construct(Caderno $carderno)
    {
        $this->decorado     = $carderno;
        $this->propriedades = array();
        $this->sentido      = '';
        $this->divisoes     = array(2, 10);
    }

    /**
     * Redireciona chamadas para o objeto decodrado
     */
    public function __call($metodo, $parametros)
    {
        return call_user_func_array(array($this->decorado, $metodo), $parametros);
    }

    /**
     * Redireciona atribuições para o objeto decodrado
     */
    public function __set($propriedade, $valor)
    {
        $this->propriedades[$propriedade] = $valor;
    }

    /**
     * Configura o sentido das tabs
     * @param $sentido Sentido das tabs (esquerda direita)
     */
    public function defSentidoTabs($sentido, $divisoes = null) 
    {
        if ($sentido) {
            $this->sentido = 'tabs-'.$sentido;
            if ($divisoes) {
                $this->divisoes = $divisoes;
            }
        }
    }

    /**
     * Exibe a grade de dados decorada
     */
    public function exibe()
    {
        $renderizado = $this->decorado->renderiza();
        $renderizado->{'role'} = 'tabpanel';
        unset($renderizado->{'class'});
        $renderizado->{'class'} = 'tabembalagem';

        foreach ($this->propriedades as $propriedade => $valor) {
            $renderizado->$propriedade = $valor;
        }

        $sessoes = $renderizado->obtFilhos();
        if ($sessoes) {
            foreach ($sessoes as $secao) {
                if ($secao->{'class'} == 'nav nav-tabs') {
                    $secao->{'class'} = "nav nav-tabs " . $this->sentido;
                    if ($this->sentido) {
                        $secao->{'class'} .= ' flex-column';
                    }
                    $secao->{'role'} = 'tablist';
                    $tabs = $secao;
                }
                if ($secao->{'class'} == 'spacer') {
                    $secao->{'style'} = 'display:none';
                }
                if ($secao->{'class'} == 'frame tab-content') {
                    $secao->{'class'} = 'tab-content';
                    $painel = $secao;
                }
            }
        }

    }
}