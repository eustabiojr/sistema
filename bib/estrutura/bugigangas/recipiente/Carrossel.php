<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 17/05/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;

class Carrossel extends Elemento
{
    private $carrossel_interno;

    public function __construct($id = NULL)
    {
        parent::__construct('div');

        $this->id    = $id;
        $this->class = 'carousel slide';
        $this->{'data-ride'} = 'carousel';

        $this->carrossel_interno = new Elemento('div');
        $this->carrossel_interno->class = 'carousel-inner';

        parent::adic($this->carrossel_interno);
    }

    public function adicItemCarrossel($img, $ativo = NULL, $alt = '')
    {
        $item = new Elemento('div');

        $item->class  = ($ativo) ? 'carousel-item active' : 'carousel-item'; # Adicion propriedade da tag

        $imagem = new Elemento('img');
        $imagem->src   = $img;
        $imagem->class = 'd-block w-100';
        $imagem->alt = $alt;

        $item->adic($imagem); # Adiciona contéudo entre as tags abre e fecha

        $this->carrossel_interno->adic($item);
    }

    public function adicControleVoltar($referencia, $etiqueta)
    {
        $controle = new Elemento('a');
        $controle->class = 'carousel-control-prev';
        $controle->href  = $referencia;
        $controle->role  = 'button';

        $icone = new Elemento('span');
        $icone->{'class'} = 'carousel-control-prev-icon';
        $icone->{'aria-hidden'} = 'true';
        $icone->adic('');

        $rotulo = new Elemento('span');
        $rotulo->class = 'sr-only';
        $rotulo->adic($etiqueta);

        $controle->adic($icone);
        $controle->adic($rotulo);

        parent::adic($controle);
    }

    public function adicControleProximo($referencia, $etiqueta)
    {
        $controle = new Elemento('a');
        $controle->class = 'carousel-control-next';
        $controle->href  = $referencia;
        $controle->role  = 'button';

        $icone = new Elemento('span');
        $icone->{'class'} = 'carousel-control-next-icon';
        $icone->{'aria-hidden'} = 'true';
        $icone->adic('');


        $rotulo = new Elemento('span');
        $rotulo->class = 'sr-only';
        $rotulo->adic($etiqueta);

        $controle->adic($icone);
        $controle->adic($rotulo);

        parent::adic($controle);
    }
}