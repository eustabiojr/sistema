<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Bugigangas\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\Botao;

/**
 * Classe EmbrulhoForm
 */
class EmbrulhoForm 
{
    private $decorado;
    private $elemento;
    private $tipoLinha;
    private $titulo_cabecalho;

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
     * Método defTipoLinha
     */
    public function defTipoLinha($tipo = '')
    {
        $this->tipoLinha = $tipo;
    }

    /**
     * Método defTipoLinha
     */
    public function obtTipoLinha()
    {
        return $this->tipoLinha;
    }

    /**
     * Método defTituloCabecalho
     */
    public function defTituloCabecalho($titulo = '')
    {
        $this->titulo_cabecalho = $titulo;
    }

    /**
     * Método obtTituloCabecalho
     */
    public function obtTituloCabecalho()
    {
        return $this->titulo_cabecalho;
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        $this->elemento = new Elemento('form');
        $this->elemento->name  = $this->decorado->obtNome();
        $this->elemento->class = "form-horizontal";
        $this->elemento->enctype = "multipart/form-data";
        $this->elemento->method  = 'post';
        $this->elemento->width   = '100%';

        $this->criaLinhaForm($this->decorado->obtCampos(), $this->obtTipoLinha(), $this->obtTituloCabecalho());

        $grupo = new Elemento('div');
        $i = 0;

        # Os botões abaixo 
        foreach ($this->decorado->obtAcoes() as $rotulo => $acao) {
            $nome = strtolower(str_replace(' ', '_', $rotulo));
            $botao = new Botao($nome);
            $botao->defNomeForm($this->decorado->obtNome());
            $botao->defAcao($acao, $rotulo);
            # 
            if ($this->obtTipoLinha() == 1) {
                $botao->class = 'w-100 btn btn-lg btn-primary';
            } else {
                $botao->class = 'btn ' . ( ($i==0) ? 'btn-success' : 'btn-default');   
            }
            $grupo->adic($botao);
            $i++;
        }
        # 
        $cartao = new Cartao($this->decorado->obtTitulo());
        $cartao->adic($this->elemento);
        $cartao->adicRodape($grupo);
        $cartao->exibe();
    }

    /**
     * Método criaLinhaForm 
     * 
     */
    private function criaLinhaForm($campos, $tipo = 1, $msg = '')
    {
        switch($tipo) {
            case 1:
                if($campos) {

                    $imagem = new Elemento('img');
                    $imagem->class  = 'mb-4';
                    $imagem->src    = '/Aplicativo/Imagens/Ageu17.png';
                    $imagem->alt    = '';
                    $imagem->width  = 129;
                    $imagem->height = 78;

                    $h1 = new Elemento('h1');
                    $h1->class = 'h3 mb-3 fw-normal';
                    $h1->adic($msg);

                    $this->elemento->adic($imagem);
                    $this->elemento->adic($h1);

                    /**
                     * O laço abaixo é repetido para cada campo do formulário
                     */
                    foreach ($campos as $campo) {
         
                        $rotulo = new Elemento('label');
                        $rotulo->class = 'visually-hidden';
                        $rotulo->adic($campo->obtRotulo());
        
                        $this->elemento->adic($rotulo);
                        $this->elemento->adic($campo);
                    }

                    $entrada = new Elemento('input');
                    $entrada->type = 'checkbox';
                    $entrada->value = 'Ficar conectado';
                    $entrada->adic("Ficar conectado");
                    $rotulo = new Elemento('label');
                    $rotulo->adic($entrada);

                    $div = new Elemento('div');
                    $div->class = 'checkbox mb-3';
                    $div->adic($rotulo);

                    $this->elemento->adic($div);
                }
            break;

            default:
                if($campos) {
                    /**
                     * O laço abaixo é repetido para cada campo do formulário
                     */
                    foreach ($campos as $campo) {
                        
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
                        $this->elemento->adic($grupo);
                    }
                }
            break;
        }
    }
}