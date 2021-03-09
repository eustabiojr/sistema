<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 08/03/2021
 ********************************************************************************************/

 # Espaço de nomes
namespace Estrutura\Bugigangas\Form;

abstract class Campo implements InterfaceElementoForm
{
    protected $nome;
    protected $tamanho;
    protected $valor;
    protected $editavel;
    protected $tag;
    protected $rotuloForm;
    protected $propriedades;

    public function __construct($nome)
    {
        self::defEditavel(true);
        self::defNome($nome);
    }

    public function  defPropriedade($nome, $valor)
    {
        $this->propriedades[$nome] = $valor;
    }

    public function  obtPropriedade($nome)
    {
        return $this->propriedades[$nome];
    }
}