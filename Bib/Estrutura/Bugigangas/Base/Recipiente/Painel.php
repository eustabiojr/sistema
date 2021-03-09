<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Base\Recipiente;

use Estrutura\Bugigangas\Base\Elemento;

/**
 * Classe Painel
 */
class Painel extends Elemento 
{
    private $corpo;
    private $rodape;

    public function __construct($titulo_painel = NULL)
    {
        parent::__construct('div');
        $this->class = 'panel panel-default';

        if ($titulo_painel) {
            $cabecalho = new Elemento('div');
            $cabecalho->class = 'panel-heading';

            $rotulo = new Elemento('h4');
            $rotulo->adic($titulo_painel);

            $titulo = new Elemento('div');
            $titulo->class = 'panel-title';
            $titulo->adic($rotulo);
            $cabecalho->adic($titulo);
            parent::adic($cabecalho);
        }

        $this->corpo = new Elemento('div');
        $this->corpo->class = 'panel-body';
        parent::adic($this->corpo);

        $this->rodape = new Elemento('div');
        $this->rodape->{'class'} = 'panel-footer';
    }

    public function adic($conteudo)
    {
        $this->corpo->adic($conteudo);
    }

    public function adicRodape($rodape)
    {
        $this->rodape->adic($rodape);
        parent::adic($this->rodape);
    }
}