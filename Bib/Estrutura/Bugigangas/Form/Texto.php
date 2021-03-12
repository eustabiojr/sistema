<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Form\Campo;
use Estrutura\Bugigangas\Form\InterfaceElementoForm;

 /**
  * Class Rotulo
  */
class Texto extends Campo implements InterfaceElementoForm
{
    private $largura;
    private $altura = 100;

    /**
     * Método defTamanho
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->size = $largura;
        if (isset($altura)) {
            $this->altura = $altura;
        }
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        # atribui as propriedades da tag
        $tag = new Elemento('textarea'); 
        $tag->class = 'field';
        $tag->name = $this->nome;
        $tag->style = "width: {$this->tamanho}; height:{$this->altura}";

        # caso o campo não seja editável
        if (!parent::obtEditavel()) {
            $tag->readonly = "1";
        }

        $tag->adic(htmlspecialchars($this->valor));

        if ($this->propriedades) {
            foreach ($this->propriedades as $propriedade => $valor) {
                $tag->$propriedade = $valor;
            }
        }
        $tag->exibe();
    }
}