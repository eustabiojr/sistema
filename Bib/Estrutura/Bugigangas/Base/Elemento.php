<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 10/04/2020
 ********************************************************************************************/
# espaço de nomes
namespace Estrutura\Bugigangas\Base;

/**
 * Classe Elemento
 */
class Elemento {
    # propriedades
    protected $nometag;
    protected $propriedades;
    protected $filhos;

    /**
     * Método __construct
     */
    public function __construct($nome)
    {
        $this->nometag = $nome;
    }

    /**
     * Método __set
     */
    public function __set($nome, $valor)
    {
        $this->propriedades[$nome] = $valor;
    }

    /**
     * Método __get
     */
    public function __get($nome)
    {
        return $this->propriedades[$nome] ?? NULL;
    }

    /**
     * Método adic
     */
    public function adic($filho)
    {
        $this->filhos[] = $filho;
    }

    /**
     * Método exibe
     */
    public function exibe() 
    {
        $this->abre();
        echo "\n";
        
        if ($this->filhos) {
            foreach ($this->filhos as  $filho) {
                if (is_object($filho)) {
                    $filho->exibe();
                } else if ((is_string($filho)) OR (is_numeric($filho))) {
                    # se for texto
                    echo $filho;
                }
            }
            $this->fecha(); # fecha tag
        }
    }

    /**
     * Método abre
     */
    private function abre() 
    {
        echo "<{$this->nometag}";
        if ($this->propriedades) {
            foreach ($this->propriedades as $nome => $valor) {
                if (is_scalar($valor)) {
                    echo " {$nome}=\"{$valor}\"";
                }
            }
        }
        echo '>';
    }

    /**
     * Método __toString
     */
    public function __toString()
    {
        ob_start();
        $this->exibe();
        $conteudo = ob_get_clean();
        return $conteudo;
    }

    /**
     * Método fecha
     */
    private function fecha() 
    {
        echo "</{$this->nometag}>\n";
    }
}