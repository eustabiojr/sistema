<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Form\Campo;
use Estrutura\Bugigangas\Form\InterfaceElementoForm;

/**
 * Class Combo
 */
class Combo extends Campo implements InterfaceElementoForm
{
    private $itens;
    protected $propriedades;

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
        # atribui as propriedades da tag
        $tag = new Elemento('select'); 
        $tag->class = 'combo';
        $tag->name = $this->nome;
        $tag->style = "width: {$this->tamanho}";
        
        $opcao = new Elemento('option');
        $opcao->adic('');
        $opcao->value = '0';

        # adiciona opção ao combo
        $tag->adic($opcao);
        if ($this->itens) {
            # percorre os itens adicionados
            foreach ($this->itens as $chave => $item) {
                # cria uma tag <option> para o item
                $opcao = new Elemento('option');
                $opcao->value = $chave;
                $opcao->adic($item);

                # caso seja a opção selecionada
                if ($chave == $this->valor)  {
                    # seleciona o item do combo
                    $opcao->selected = 1;
                }
                # adiciona a opção ao combo
                $tag->adic($opcao);
            }
        }

        # verifica se o campo é editável
        if (!parent::obtEditavel()) {
            $tag->readonly = "1";
        }

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                $tag->$propriedade = $valor;
            }
        }
        $tag->exibe();
    }
}