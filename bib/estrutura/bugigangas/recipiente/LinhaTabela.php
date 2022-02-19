<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 09/06/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;
use Exception;

/**
 * LinhaTabela - Representa um linha dentro da tabela
 * 
 * @version    0.1
 * @package    widget
 * @subpackage form
 * @author     Pablo DallOglio (E modificado por Eustábio J. Silva Jr.)
 * @copyright  Copyright (c) 2020 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class LinhaTabela extends Elemento
{
    private $secao;

    /**
     * Classe Construtor
     */
    public function __construct($secao = 'tbody') {
        parent::__construct('tr');
        $this->secao  = $secao;
    }

    /**
     * Adiciona uma célula nova (GCelulaTabela) a linha da tabela
     * @param $valor Conteudo da Célula
     * @return GCelulaTabela
     */
    public function adicCelula($valor) {
        if (is_null($valor)) {
            throw new Exception("Método {__METHOD__} não aceita valores NULOS");
        } else {
            # Cria a célula de tabela
            $celula = new CelulaTabela($valor, $this->secao == 'thead' ? 'th' : 'td');

            parent::adic($celula);
            // Retorna o objeto célula
            return $celula;
        }
    }

    /**
     * Adiciona a multi-célula célular da tabela
     * @param $celulas Cada argumento é uma linha de células
     */
    public function adicMultiCelulas()
    {
        $embrulho = new CaixaH;

        $args = func_get_args();
        if ($args) {
            foreach ($args as $arg) {
                $embrulho->adic($arg);
            }
        }
    }

    /**
     * Limpa quaisquer elementos filho
     */
    public function limpaFilhos()
    {
        $this->filhos = array();
    }
}