<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Menu;

use SimpleXMLElement;
use Exception;
use DOMDocument;
use DOMElement;
use Estrutura\Utilidades\ConversaoString;

/**
 * Analsador Menu
 * 
 * @version 0.1
 * @package widget
 * @subpackage menu
 * @author Pabro Dall'Oglio (Modificado por: Eustábio J. Silva Júnior)
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class AnalisadorMenu
{
    private $caminhos;
    private $caminho;

    /**
     * Analisa um arquivo XML de menu
     * @param $arquivo_xml caminho do arquivo
     */
    public function __construct($arquivo_xml)
    {
        $this->caminho = $arquivo_xml;

        if (\file_exists($arquivo_xml)) {
            $string_menu = ConversaoString::garanteUnicode(file_get_contents($arquivo_xml));
            $xml = new SimpleXMLElement($string_menu);

            foreach ($xml as $elementoXml) {
                $atributos = $elementoXml->attributes;
                $rotulo = (string) $atributos['rotulo'];
                $acao   = (string) $elementoXml->acao;
                $icone  = (string) $elementoXml->icone;

                if (substr($rotulo, 0, 3) == '_g{') {
                    $rotulo = _g(substr($rotulo, 3, -1), 3, -1);
                }
                $this->analisador($elementoXml->menu->itemmenu, array($rotulo));
            }
        } else {
            throw new Exception('Arquivo não encontrado : ' . $arquivo_xml);
        }
    }

    /**
     * Analisa um XMLElement lendo as entradas do menu
     * @param $xml Um objeto SimpleXMLElement
     */
    private function analisador($xml, $caminho) 
    {
        $i = 0;
        if ($xml) {
            foreach ($xml as $elementoXml) {
                $atributos = $elementoXml->attributes();
                $rotulo = (string) $atributos['rotulo'];
                $acao   = (string) $elementoXml->acao;

                if (substr($rotulo, 0, 3) == '_g{') {
                    $rotulo = _g(substr($rotulo, 3, -1), 3, -1);
                }

                if (strpos($acao, '#') !== FALSE) {
                    list ($acao, $metodo) = explode('#', $acao);
                }
                $icone = (string) $elementoXml->icone;

                if ($elementoXml->menu) {
                    $this->analisador($elementoXml->menu->itemmenu, array_merge($caminho, array($rotulo)));
                }

                # apenas nós filhos tem ações
                if ($acao) {
                    $this->caminhos[$acao] = array_merge($caminho, array($rotulo));
                }
            }
        }
    }

    /**
     * Retorna um array indexado de programas
     */
    public function obtProgramasIndexados()
    {
        $programas = [];
        foreach ($this->caminhos as $acao => $caminho) {
            $programas[$acao] = array_pop($caminho);
        }
        return $programas;
    }

    /**
     * Retorna o controlador do caminho
     */
    public function obtCaminho($controlador)
    {
        return $this->caminhos[$controlador] ?? null;
    }

    /**
     * Verifica se o modulo existe
     */
    public function moduloExiste($modulo)
    {
        $doc_xml = new DOMDocument;
        $doc_xml->load($this->caminho);
        $doc_xml->encoding = 'utf-8';

        foreach ($doc_xml->getElementsByTagName('itemmenu') as $noh) {
            $noh_rotulo = $noh->getAttribute('rotulo');
            foreach ($noh->childNodes as $subnoh) {
                if ($subnoh instanceof DOMElement) {
                    if ($subnoh->tagName == 'menu' AND $noh_rotulo == $modulo) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Obtém Módulos
     */
    public function obtModulos()
    {
        $doc_xml = new DOMDocument;
        $doc_xml->preserveWhiteSpace = false;
        $doc_xml->formatOutput = true;
        $doc_xml->load($this->caminho);
        $doc_xml->encoding = 'utf-8';

        $modulos = [];

        foreach ($doc_xml->getElementsByTagName('itemmenu') as $noh) {
            $noh_rotulo = $noh->getAttribute('rotulo');
            foreach ($noh->childNodes as $subnoh) {
                if ($subnoh instanceof DOMElement) {
                    if ($subnoh->tagName == 'menu') {
                        $modulos[$noh_rotulo] = $noh_rotulo;
                    }
                }
            }
        }
        return $modulos;
    }
}