<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 12/04/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;

/**
 * Cclass GrupoCartao
 * 
 */
class GrupoCartao extends Elemento
{
    private $itens; 
    private $grupo;

    public function adicItem($item)
    {
       $this->itens = $item;
    }

    public function obtItens()
    {
        return $this->itens;
    }

    public function exibe()
    {
        parent::__construct('div');

        $this->class = 'card-group';

        #echo "<pre>";
        #print_r($this->itens);

        # Verdadeiro se propriedade do objeto 'item' tiver pelo menos um índice.
        #
        # No caso a propriedade '$this->itens' está vazia se estiver sendo chamada no construtor
        # Quando algum item é adicionado, o construtor já foi chamado (e nesse momento a referida
        # propriedade está vazia, e por isso, o IF é sempre avaliado como FALSO 
        if ($this->itens) {
            foreach ($this->itens as $indice => $valor) {
                $cartao = new Cartao("Nome Cabeçalho");
                $cartao->adicTitulo($this->itens[$indice]['descricao']);
                #$cartao->adic("<b>Conteudo adicionado</b>");
                $cartao->adicTexto($this->itens[$indice]['caracteristicas']);
                $cartao->adicSubtitulo($this->itens[$indice]['valor']);
                $cartao->adicRodape($this->itens[$indice]['desconto']);

                parent::adic($cartao);
            }
            parent::exibe();
        }
    }
}
