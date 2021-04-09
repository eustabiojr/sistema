<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/04/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

/**
 * Classe GrupoCartao
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
        $this->itens[] = array();
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
                $cartao = new Cartao();
            }

        }
    }
}