<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 12/04/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;

/**
 * Cclass GrupoCapa
 * 
 */
class GrupoCapa extends Elemento
{
    private $itens_obj; 
    private $grupo;

    public function adicItem($item_obj)
    {
       $this->itens_obj[] = $item_obj;
    }

    public function obtItens()
    {
        return $this->itens;
    }

    public function exibe()
    {
        parent::__construct('div');

        $this->class = 'card-deck';

        # Verdadeiro se propriedade do objeto 'item' tiver pelo menos um índice.
        #
        # No caso a propriedade '$this->itens' está vazia se estiver sendo chamada no construtor
        # Quando algum item é adicionado, o construtor já foi chamado (e nesse momento a referida
        # propriedade está vazia, e por isso, o IF é sempre avaliado como FALSO 
        #echo '<pre>';
        #print_r($this->itens_obj);
        if ($this->itens_obj) {
            foreach ($this->itens_obj as $indice => $item) {
                
                $cartao = new Cartao("Oferta!");
                $cartao->adicTitulo($item->descricao);
                #$cartao->adic("<b>Conteudo adicionado</b>");
                $cartao->adicTexto($item->caracteristicas);
                $cartao->adicSubtitulo($item->valor);
                $cartao->adicRodape($item->desconto);

                parent::adic($cartao);
            }
            parent::exibe();
        }
    }
}
