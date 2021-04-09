<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

/**
 * Classe GrupoCartao (Em desenvolvimento)
 */
class GrupoCartao
{
    private $itens;

    /**
     * Método 
     */
    public function __construct()
    {
        $this->class = 'card-group';
    }

    /**
     * Método adicItens
     */
    public function adicItens($titulo = NULL, $tipo_titulo = NULL, array $imagem = array(), array $links = array())
    {
        $this->itens[] = array($titulo, $tipo_titulo, $imagem, $links);
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        if ($this->itens) {
            # percorre cada uma das opções de radio
            foreach ($this->itens as $indice => $item) {
                #
                $cartao = new Cartao($item[0], $item[1], $item[2], $item[3]);
                $cartao->adic("Texto");
                $cartao->adicTituloCorpo("O titulo");
                $cartao->adicTextoCorpo("texto texto texto");
                $cartao->adicLinkCorpo('#');
                $cartao->adicRodape("Atualizado a pouco");
            }
        }
    }
}