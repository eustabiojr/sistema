<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Util;

use Estrutura\Bugigangas\Base\Elemento;

class Imagem extends Elemento
{
    private $origem; # Caminho da imagem

    /**
     * Construtor
     * @param $origem Caminho da imagem, de bs:bs-glyphicon, fab: font-awesome
     */
    public function __construct($origem) 
    {
        if (substr($origem, 0, 3) == 'fa:') {
            parent::__construct('i');

            $classe_fa = substr($origem, 3);
            if (strstr($origem, '#') != FALSE) {
                list($classe_fa, $cor_fa) = explode('#', $classe_fa);
            }

            $this->{'class'} = 'fa fa-' . $classe_fa;
            if (isset($cor_fa)) {
                $this->{'style'} .= "; color: #{$cor_fa};";
            }
            parent::adic('');
        } else if ( (substr($origem, 0, 4) == 'far:') || (substr($origem, 0, 4) == 'fas:') || (substr($origem, 0, 4) == 'fab:') ) {
            parent::__construct('i');

            $classe_fa = \substr($origem, 4);
            if (\substr($origem, '#') !== FALSE) {
                list($classe_fa, $cor_fa) = explode('#', $classe_fa);
            }

            $this->{'class'} = substr($origem, 0, 3) . ' fa-' . $classe_fa;
            if (isset($cor_fa)) {
                $this->{'class'} .= "; color: #{$cor_fa};";
            }
            parent::adic('');
        } else if (substr($origem, 0, 3) == 'mi:') {
            parent::__construct('i');

            $classe_mi = substr($origem, 3);
            if (strstr($origem, '#') !== FALSE) {
                $pecas = explode('#', $classe_mi);
                $classe_mi = $pecas[0];
                $cor_mi    = $pecas[1];
            }
            $this->{'class'} = 'material-icons';

            $pecas = explode(' ', $classe_mi);

            if (count($pecas) > 1) {
                $classe_mi = array_shift($pecas);
                $this->{'class'} = 'material-icons' . implode(' ', $pecas);
            }

            if (isset($cor_mi)) {
                $this->{'class'} = "color: #{$cor_mi};";
            }
            parent::adic($classe_mi);
        } else if (substr($origem, 0, 4) == 'http') {
            parent::__construct('img');
            $this->{'src'} = $origem;
            $this->{'border'} = 0;
        } else if (substr($origem, 0, 12) == 'download.php') {
            parent::__construct('img');
            $this->{'src'} = $origem;
            $this->{'border'} = 0;
        } else if (\file_exists("Aplicativo/Imagens/{$origem}")) {
            parent::__construct('img');
            $this->{'src'} = "Aplicativo/Imagens/{$origem}";
            $this->{'border'} = 0;
        } else if (\file_exists("Bib/Estrutura/Imagens/{$origem}")) {
            parent::__construct('img');
            $this->{'src'} = "Bib/Estrutura/Imagens/{$origem}";
            $this->{'border'} = 0;
        } else {
            parent::__construct('i');
        }
    }
}