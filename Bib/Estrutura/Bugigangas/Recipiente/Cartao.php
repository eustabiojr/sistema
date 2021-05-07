<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 12/04/2020
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Recipiente;

# Espaço de nomes
use Estrutura\Bugigangas\Base\Elemento;

 /**
  * Classe Cartao
  */
 class Cartao extends Elemento
 {  
    private $corpo;
    private $imagem;
    private $texto;
    private $rodape;

    /**
     * Quando for vários cartões, poderemos ter: card-group, card-deck, card-columns. 
     * 
     * O cartão mais simples possuem apenas: card e card-body
     * 
     * O cartão "card" pode ter: card-header (<div>), card-img (<img>), list-group (<ul>), list-group-item (<li>).
     * 
     * O cartão "card-body" (<div>) podem possuir: card-title (<h5>), card-subtitle (<h6>), card-text (<p>),
     * card-link (<a>). 
     * 
     * E no rodapé "card-footer".
     */
 
     /**
      * Método Construtor
      */
    public function __construct($cabecalho = NULL, $alinhamento = NULL)
    {
        parent::__construct('div');

        $alin = isset($alinhamento) ? "card $alinhamento" : 'card';
        $this->class = $alin;

        $this->imagem = new Elemento('img');
        #$this->imagem->src = '...';
        $this->imagem->{'class'}  = 'card-img-top';
        #$this->imagem->alt = '...';

        parent::adic($this->imagem);

        if ($cabecalho) {

            $cabecalho_cartao = new Elemento('div');
            $cabecalho_cartao->class = 'card-header';
            $cabecalho_cartao->adic($cabecalho);
    
            parent::adic($cabecalho_cartao);
        }

        $this->corpo = new Elemento('div');
        $this->corpo->class = 'card-body';
        parent::adic($this->corpo);

        $this->texto = new Elemento('p');
        $this->texto->{'class'} = 'card-text';

        $this->rodape = new Elemento('div');
        $this->rodape->{'class'} = 'card-footer';
    }

    /** 
     * Método adic (Aqui adicionamos conteúdo)
     */
    public function adic($conteudo)
    {
        $this->corpo->adic($conteudo);
    }

    /**
     * Método adicTitulo (Para conformidade com Bootstrap 4)
     */
    public function adicTitulo($titulo_cartao)
    {
        $titulo = new Elemento('h5');
        $titulo->class  = 'card-title'; # Adicion propriedade da tag
        $titulo->adic($titulo_cartao); # Adiciona contéudo entre as tags abre e fecha

        $this->corpo->adic($titulo);
    }

    /**
     * Método adicTitulo (Para conformidade com Bootstrap 4)
     */
    public function adicSubtitulo($subtitulo_cartao)
    {
        $subtitulo = new Elemento('h6');
        $subtitulo->class  = 'card-subtitle'; # Adiciona propriedade da tag
        $subtitulo->adic($subtitulo_cartao); # Adiciona contéudo entre as tags abre e fecha

        $this->corpo->adic($subtitulo);
    }

    /**
     * Método adicTexto (Para conformidade com Bootstrap 4)
     */
    public function adicTexto($texto)
    {
        $this->texto->adic($texto);
        $this->corpo->adic($this->texto);
    }

    public function adicRodape($rodape)
    {
        $this->rodape->adic($rodape);
        parent::adic($this->rodape);
    }
 }
 