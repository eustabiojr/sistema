<?php
/********************************************************************************************
 * Sistema Ageunet
 * Data: 09/06/2020
 ********************************************************************************************/
namespace Ageunet\Widgets\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Recipiente\LinhaTabela;

/**
 * Cria um esboço de tabela, com linhas e colunas
 * 
 * @version    0.1
 * @package    widget
 * @subpackage form
 * @author     Pablo DallOglio (E modificado por Eustábio J. Silva Jr.)
 * @copyright  Copyright (c) 2020 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Tabela extends Elemento
{
    private $secao;

    /**
     * Método construtor da classe
     */
    public function __construct()
    {
        parent::__construct('table');
        $this->secao = null;
    }

    /**
     * Cria um a tabela
     */
    public static function cria($propriedades)
    {
        // Chamada recursiva 
        $tabela = new Tabela;
        foreach ($propriedades as $propriedade => $valor) {
            $tabela->$propriedade = $valor;
        }
        return $tabela;
    }

    /**
     * Adiciona seção
     */
    public function adicSecao($tipo)
    {
        if ($tipo == 'thead') {
            $this->secao = new Elemento('thead');
        } else if ($tipo == 'tbody') {
            $this->secao = new Elemento('tbody');
        } else if ($tipo == 'tfoot') {
            $this->secao = new Elemento('tfoot');
        }
        parent::adic($this->secao);

        return $this->secao;
    }

    /**
     * Adiciona uma nova linha (objeto LinhaTabela) para a tabela
     * @return LinhaTabela
     */
    public function adicLinha()
    {
        # Cria uma nova linha da tabela
        $linha = new LinhaTabela($this->secao ? $this->secao->obtNome() : 'tbody');

        # Adiciona esta linha ao elemento tabela
        if (isset($this->secao)) {
            $this->secao->adic($linha);
        } else {
            parent::adic($linha);
        }
        return $linha;
    }

    /**
     * Adiciona uma nova linha (objeto LinhaTabela) com muitas células
     * @param $celulas Cada argumento é uma célula de linha
     * @return LinhaTabela
     */
    public function adicGrupoLinha()
    {
        # cria uma nova linha na tabela
        $linha = $this->adicLinha();

        $args = func_get_args();
        if ($args) {
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    $inst = $linha;
                    \call_user_func_array(array($inst, 'adicMultiplasCelulas'), $arg);
                } else {
                    $linha->adicCelula($arg, ($this->secao && $this->secao->obtNome() == 'thead') ? 'th' : 'td');
                }
            }
        }
        return $linha;
    }

    /**
     * Cria uma tabela 
     * @param $dados_array com dados crús
     * @param $propriedades_tabela Array de propriedades CSS para tabela
     * @param $propriedades_cabecalho Array de propriedades CSS para o cabeçalho
     * @param $propriedades_corpo Array de propriedades CSS para o corpo
     */
    public static function deDados($dados_array, $propriedades_tabela = null, $propriedades_cabecalho = null, $propriedades_corpo = null)
    {
        $tabela = new self;
        if ($propriedades_tabela) {
            foreach ($propriedades_tabela as $prop => $valor) {
                $tabela->$prop = $valor;
            }
        }

        $cabecalho = array_keys($dados_array[0] ?? array());

        $tcabecalho = new Elemento('thead');
        $tabela->adic($tcabecalho);

        $tr = new LinhaTabela;
        $tcabecalho->adic($tr);
        foreach ($cabecalho as $celula) {
            $td = $tr->adicCelula((string) $celula);
            if ($propriedades_cabecalho) {
                foreach ($propriedades_cabecalho as $prop => $valor) {
                    $td->$prop = $valor;
                }
            }
        }

        $tcorpo = new Elemento('tbody');
        $tabela->adic($tcorpo);

        $i = 0;
        foreach ($dados_array as $linha) {
            $tr = new LinhaTabela;
            $tcorpo->adic($tr);
            $tr->{'class'} = ($i %2 == 0) ? 'add' : 'even';

            foreach ($cabecalho as $chave) {
                $celula = $linha[$chave] ?? '';
                $td = $tr->adicCelula((string) $celula);
                if ($propriedades_corpo) {
                    foreach ($propriedades_corpo as $prop => $valor) {
                        $td->$prop = $valor;
                    }
                }
            }
            $i++;
        }
        return $tabela;
    }
}