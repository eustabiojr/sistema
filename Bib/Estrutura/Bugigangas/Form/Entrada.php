<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;

 /**
  * Class Rotulo
  */
class Entrada extends Campo implements InterfaceElementoForm
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
        $tag->type = 'text';
        $tag->style = "width: {$this->tamanho}";

        # caso o campo não seja editável
        if (!parent::obtEditavel()) {
            $tag->readonly = "1";
        }

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                if ($valor === NULL) {
                    $tag->$propriedade = NULL;
                } else {
                    $tag->$propriedade = $valor;
                }
            }
        } 
        $tag->exibe();
    }
}