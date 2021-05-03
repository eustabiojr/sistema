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

 /**
  * Class Rotulo
  */
class Arquivo extends Campo implements InterfaceBugiganga
{   
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id = $this->nome . '_' . mt_rand(1000000000, 1999999999);
        $this->classeUploader = 'ServicoUploaderAgeu';
        $this->tratadorArquivo = FALSE;
    }
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
        $tag->type = 'file';
        $tag->style = "width: {$this->tamanho}";

        # caso o campo não seja editável
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