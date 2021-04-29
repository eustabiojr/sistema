<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 28/04/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Bugigangas\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe EmbalaItem (Ainda não testado)
 */
class EmbalaItem
{
    private $grupo;
    private $rotulo;
    private $entrada;
    
    public function __construct() {}

    public function defGrupo(Int $tamanho = NULL)
    {
        $this->grupo = new Elemento('div');
        if ($tamanho === NULL) {
            $this->grupo->class = 'col-md-4';
        } else {
            $this->grupo->class = 'col-md-' . $tamanho;
        }
    }

    public function defRotulo()
    {
        $this->rotulo = new Elemento('label');
        $this->rotulo->class = 'form-label';
    }

    public function defEntrada()
    {
        $this->entrada = new Elemento('input');
        $this->entrada->type = 'text';
        $this->entrada->class = 'form-control';  # form-select'     
    }

    public function exibe()
    {
        $this->grupo->adic($this->rotulo);
        $this->grupo->adic($this->entrada);
    }
}