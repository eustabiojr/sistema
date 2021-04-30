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
  * Class Rotulo
  */
class Oculto extends Campo implements InterfaceElementoForm
{
    # propriedades
    protected $propriedades;

    /**
     * Método exibe
     */
    public function exibe()
    {
        # atribui as propriedades da tag
        $tag = new Elemento('input'); 
        $tag->class = 'field';
        $tag->name = $this->nome;
        $tag->value = $this->valor;
        $tag->type = 'hidden';
        $tag->style = "width: {$this->tamanho}";

        # caso o campo não seja editável
        if (!parent::obtEditavel()) {
            $tag->readonly = "readonly";
        }

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                $tag->$propriedade = $valor;
            }
        }
        $tag->exibe();
    }
}