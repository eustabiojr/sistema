<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 01/04/2021
 ********************************************************************************************/

 # Espaço de nomes
 namespace Estrutura\Bugigangas\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;

class GrupoEntradaForm 
{
    protected $campo_decorado; 

    public function __construct($campo)
    {
        $this->campo_decorado = $campo;
    }

    public function exibe($itens_grupo)
    {
        $div = new Elemento('div');
        $div->class = 'row g-3';

        foreach ($itens_grupo as $item) {

            $rotulo = new Elemento('label');
            $rotulo->class = 'form-label';
            $rotulo->adic($item);
    
            $div_grupo = new Elemento('div');
            $div_grupo->class = 'col-md-4';
            $div_grupo->adic($rotulo);
            $this->campo_decorado->class = 'form-control';
            $div_grupo->adic($this->campo_decorado);

            $div->adic($div_grupo);
        }
        $div->adic($div_grupo);
    }
}