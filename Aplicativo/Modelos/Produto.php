<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Gravacao;

/**
* Classe Produto
*/
class Produto extends Gravacao 
{
  const NOMETABELA = 'produto';

  private $fabricante;

  public function obtNomeFabricante()
  {
    if (empty($this->fabricante)) {
      $this->fabricante = new Fabricante($this->id_fabricante);
    }
    return $this->fabricante->nome;
  }
}