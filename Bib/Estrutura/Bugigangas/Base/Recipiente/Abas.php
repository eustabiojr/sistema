<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Autor: Eustábio J. Silva Jr. 
 * Data: 30/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe Navs 
 */
class Abas extends Elemento 
{
    /**
     * Método construtor
     */
    public function __construct(array $abas = array(), array $parametros = array(),)
    {
        parent::__construct('div');
        
        $div = new Elemento('div');
        $div->class = 'tab-content';
        $div->id    = $parametros['id'];

        if (isset($abas)) {

            foreach($abas as $chave => $valor) {
                $aba = new Elemento('div');
                if ($parametros['ativo'] === $chave ) {
                    $aba->class               = 'tab-pane fade show active';
                } else {
                    $aba->class               = 'tab-pane fade';
                }
                $aba->id                  = $chave;
                $aba->role                = 'tabpanel';
                $aba->{'aria-labelledby'} = 'home-tab';
                $aba->adic($valor);

                $div->adic($aba);
            }
        }
        parent::adic($div);
    }
}