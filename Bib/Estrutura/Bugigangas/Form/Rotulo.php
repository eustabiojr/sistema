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
class Rotulo extends Campo implements InterfaceBugiganga
{
    protected $estiloEmbutido;
    protected $id;

    /**
     * Método __construct
     */
    public function __construct($valor, $cor = null, $tamanhofonte = null, $decoracao = null, $tamanho = null)
    {
        $this->id        = 'rotulo_' . \mt_rand(1000000000, 1999999999);
        $this->defValor($valor);

        if (!empty($cor)) {
            $this->defCorFonte($cor);
        }

        if (!empty($tamanhofonte)) {
            $this->defTamanhoFonte($tamanhofonte);
        }

        if (!empty($decoracao)) {
            $this->defEstiloFonte($decoracao);
        }

        if (!empty($tamanho)) {
            $this->defTamanho($tamanho);
        }

        # Cria um elemento novo
        $this->tag = new Elemento('label');
    }

    /**
     * Define o tamanho da fonte
     * @param $tamanho Tamano da fonte em pixels
     */
    public function defTamanhoFonte($tamanho)
    {
        $this->estiloEmbutido->{'font_size'} = (strpos($tamanho, 'px') OR strpos($tamanho, 'pt')) ? $tamanho : $tamanho.'pt';
    }

    /**
     * Define o estilo
     * @param $decoracao decorações de texto (b=bold, i=italic, u=underline)
     */
    public function defEstiloFonte($decoracao) 
    {
        if (strpos(strtolower($decoracao), 'b') !== FALSE) {
            $this->estiloEmbutido->{'font-weight'} = 'bold';
        }
        if (strpos(strtolower($decoracao), 'i') !== FALSE) {
            $this->estiloEmbutido->{'font-style'} = 'italic';
        }
        if (strpos(strtolower($decoracao), 'u') !== FALSE) {
            $this->estiloEmbutido->{'font-decoration'} = 'underline';
        }
    }

    /**
     * Define a fonte face
     * @param $fonte Font family 
     */
    public function defFaceFonte($fonte) 
    {
        $this->estiloEmbutido->{'font_family'} = $fonte;
    }

    /**
     * Define a cor da fonte
     * @param $fonte cor da fonte
     */
    public function defCorFonte($cor) 
    {
        $this->estiloEmbutido->{'color'} = $cor;
    }

    /**
     * Método adic
     */
    public function adic($filho)
    {
        $this->tag->adic($filho);
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        $this->tag->adic($this->valor);
        $this->tag->exibe();
    }
}