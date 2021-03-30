<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;

/**
 * Class EmbrulhoGradedados
 */
class EmbrulhoGradedados
{
    private $decorado;

    /**
     * Método __construct
     */
    public function __construct(Gradedados $gradedados)
    {
        $this->decorado = $gradedados;
    }

    /**
     * Método __call
     */
    public function __call($metodo, $parametros)
    {
        return call_user_func_array(array($this->decorado, $metodo), $parametros);
    }

    /**
     * Método __set
     */
    public function __set($atributo, $valor)
    {
        $this->decorado->$atributo = $valor;
    }

    public function exibe() 
    {
        $elemento = new Elemento('table');
        $elemento->class = 'table table-striped table-hover';

        # cria o cabeçalho
        $tcabecalho = new Elemento('thead');
        $elemento->adic($tcabecalho);
        $this->criaCabecalhos($tcabecalho);

        # cria o corpo
        $tcorpo = new Elemento('tbody');
        $elemento->adic($tcorpo);

        $itens = $this->decorado->obtItens();
        foreach ($itens as $item) {
            $this->criaItem($tcorpo, $item);
        }

        $cartao = new Cartao;
        $cartao->type = 'gradedados';
        $cartao->adic($elemento);
        $cartao->exibe();
    }

    public function criaCabecalhos($tcabecalho)
    {
        # adiciona uma linha à tabela
        $linha = new Elemento('tr');
        $tcabecalho->adic($linha);

        $acoes   = $this->decorado->obtAcoes();
        $colunas = $this->decorado->obtColunas();

        # adicona células para as ações
        if ($acoes) {
            foreach($acoes as $acao) {
                $celula = new Elemento('th');
                $celula->width = '40px';
                $linha->adic($celula);
            }
        }

        # adiciona as células para os títulos das colunas
        if ($colunas) {
            # percorre as colunas da listagem
            foreach ($colunas as $coluna) {
                # obtém as propriedades da coluna
                $rotulo  = $coluna->obtRotulo();
                $alinh   = $coluna->obtAlinhamento();
                $largura = $coluna->obtLargura();

                $celula = new Elemento('th');
                $celula->adic($rotulo);
                $celula->style = "text-align: $alinh";
                $celula->width = $largura;
                $linha->adic($celula);

                # verifica se a coluna tem uma ação
                if ($coluna->obtAcao()) {
                    $url = $coluna->obtAcao();
                    $celula->onclick = "document.location='$url'";
                }
            }
        }
    }

    public function criaItem($tcorpo, $item)
    {
        $linha = new Elemento('tr');
        $tcorpo->adic($linha);

        $acoes   = $this->decorado->obtAcoes();
        $colunas = $this->decorado->obtColunas();

        # verifica se a listagem possui ações
        if ($acoes) {
            # percorre as ações
            foreach ($acoes as $acao) {
                # obtém as propriedades da ação
                $url    = $acao['acao']->serializa();
                $rotulo = $acao['rotulo'];
                $imagem = $acao['imagem'];
                $campo  = $acao['campo'];

                # obtém o campo do objeto que será passado adiante
                $chave = $item->$campo;

                # cria um link
                $link = new Elemento('a');
                $link->href = "{$url}&chave={$chave}&{$campo}={$chave}";

                # verifica se o link será com imagem ou com texto
                if ($imagem) {
                    # adiciona a imagem ao link
                    $i = new Elemento('i');
                    $i->class = $imagem;
                    $i->title = $rotulo;
                    $i->adic('');
                    $link->adic($i);
                } else {
                    # adiciona o rótulo de texto ao link
                    $link->adic($rotulo);
                }

                $elemento = new Elemento('td');
                $elemento->adic($link);
                $elemento->align = 'center';

                # adiciona a célula à linha
                $linha->adic($elemento);
            }
        }

        if ($colunas) {
            # percorre as colunas da Gradedados
            foreach ($colunas as $coluna) {
                $nome    = $coluna->obtNome();
                $alinh   = $coluna->obtAlinhamento();
                $largura = $coluna->obtLargura();
                $funcao  = $coluna->obtTransformador();
                $dados = $item->$nome;

                # verifica se há função para transformar os dados
                if ($funcao) {
                    # aplica a função sobre os dados
                    $dados = call_user_func($funcao, $dados);
                }

                $elemento = new Elemento('td');
                $elemento->adic($dados);
                $elemento->align = $alinh;
                $elemento->width = $largura;

                # adiciona a célula na linha
                $linha->adic($elemento);
            }
        }
    }
}