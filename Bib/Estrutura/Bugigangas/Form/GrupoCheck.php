<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe GrupoCheck 
 */
class GrupoCheck extends Campo implements InterfaceBugiganga
{
    private $esboco = "vertical";
    private $itens;

    /**
     * Método defEsboco
     */
    public function defEsboco($dir)
    {
        $this->esboco = $dir;
    }

    /**
     * Método adicItens
     */
    public function adicItens($itens)
    {
        $this->itens = $itens;
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        if ($this->itens) {
            # percorre cada uma das opções de radio
            foreach ($this->itens as $indice => $rotulo) {
                $botao = new BotaoCheck("{$this->nome}[]");
                $botao->defValor($indice);

                # verifica se deve ser marcado
                if (in_array($indice, (array) $this->valor)) {
                    $botao->defPropriedade('checked', '1');
                }

                $obj = new Rotulo($rotulo);
                $obj->adic($botao);
                $obj->exibe();
                if ($this->esboco == 'vertical') {
                    # exibe uma tag de quebra de linha
                    $br = new Elemento('br');
                    $br->exibe();
                    echo PHP_EOL;
                }
            }

        }
    }
}