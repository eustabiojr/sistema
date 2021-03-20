<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Recipiente\Painel;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\Botao;

/**
 * Classe EmbrulhoForm
 */
class EmbrulhoForm 
{
    private $decorado;

    /**
     * Método Construtor
     */
    public function __construct(Form $form)
    {
        $this->decorado = $form;
    }

    /**
     * Método __call
     */
    public function __call($metodo, $parametros)
    {
        return call_user_func_array(array($this->decorado,  $metodo), $parametros);
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        $elemento = new Elemento('form');
        $elemento->name  = $this->decorado->obtNome();
        $elemento->class = "form-horizontal";
        $elemento->enctype = "multipart/form-data";
        $elemento->method  = 'post';
        $elemento->width   = '100%';

        /**
         * O laço abaixo é repetido para cada campo do formulário
         */
        foreach ($this->decorado->obtCampos() as $campo) {
            $grupo = new Elemento('div');
            $grupo->class = 'mb-3';

            $rotulo = new Elemento('label');
            $rotulo->class = 'form-label';
            $rotulo->adic($campo->obtRotulo());

            $col = new Elemento('div');
            $col->class = 'col-sm-10';
            $col->adic($campo);
            $campo->class = 'form-control';

            $grupo->adic($rotulo);
            $grupo->adic($col);
            $elemento->adic($grupo);
        }

        $grupo = new Elemento('div');
        $i = 0;

        # Os botões abaixo 
        foreach ($this->decorado->obtAcoes() as $rotulo => $acao) {
            $nome = strtolower(str_replace(' ', '_', $rotulo));
            $botao = new Botao($nome);
            $botao->defNomeForm($this->decorado->obtNome());
            $botao->defAcao($acao, $rotulo);
            $botao->class = 'btn ' . ( ($i==0) ? 'btn-success' : 'btn-default');
            $grupo->adic($botao);
            $i++;
        }

        $painel = new Painel($this->decorado->obtTitulo());
        $painel->adic($elemento);
        $painel->adicRodape($grupo);
        $painel->exibe();
    }
}