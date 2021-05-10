<?php 
namespace Estrutura\Embrulho;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Embalagem\FormRapido;
use Estrutura\Bugigangas\Form\InterfaceBugiganga;
use Estrutura\Bugigangas\Form\InterfaceElementoForm;
use Estrutura\Bugigangas\Form\Oculto;
use Estrutura\Bugigangas\Form\Rotulo;

/**
 * Decorador de formulário bootstrap para Framework Ageunet
 * 
 * @version 0.1
 * @package embalagem
 * @author Eustábio J. Silva Jr.
 * @author Pablo Dall'Oglio
 * @license http://www.adianti.com.br/framework-license
 * @wrapper GFormRapido
 */
class EmbalagemFormBootstrap implements InterfaceElementoForm
{
    private $decorado;
    private $grupoCorrente; 
    private $elemento;

    /**
     * Método construtor
     */
    public function __construct(FormRapido $form, $classe = 'form-horizontal')
    {
        $this->decorado = $form;

        $this->elemento = new Elemento('form');
        $this->elemento->{'class'}     = $classe;
        $this->elemento->{'type'}      = 'bootstrap';
        $this->elemento->{'enctype'}   = "multipart/form-data";
        $this->elemento->{'method'}    = 'post';
        $this->elemento->{'name'}      = $this->decorado->obtNome();
        $this->elemento->{'id'}        = $this->decorado->obtNome();
        $this->elemento->{'naovalida'} = '';
    }

    /**
    * Liga/Desliga validação de cliente
    */
    public function defValidacaoCliente($bool)
    {
        if($bool) {
            unset($this->elemento->{'naovalida'});
        } else {
                $this->elemento->{'naovalida'} = '';
        }
    }

    /**
     * Retorna elemento renderizador
     */
    public function obtElemento()
    {
        return $this->elemento;
    }

    /**
     * Redireciona chamadas para o objeto decorado
     */
    public function __call($metodo, $parametros)
    {
        return call_user_func_array(array($this->decorado, $metodo), $parametros);
    }

    /**
     * Define o nome do formulário
     */
    public function defNome($nome)
    {
        return $this->decorado->defNome($nome);
    }

    /**
     * Obtém o nome do formulário
     */
    public function obtNome()
    {
        return $this->decorado->obtNome();
    }

    /**
     * Adiciona um campo ao formulário
     */
    public function adicCampo(InterfaceBugiganga $campo)
    {
        return $this->decorado->adicCampo($campo);
    }

    /**
     * Apaga um campo do formulário
     */
    public function apagCampo(InterfaceBugiganga $campo)
    {
        return $this->decorado->apagCampo($campo);
    }

    /**
     * Define campos do formulário
     */
    public function defCampos($campos)
    {
        return $this->decorado->defCampos($campos);
    }

    /**
     * Retorna campo do formulário
     */
    public function obtCampo($nome)
    {
        return $this->decorado->obtCampo($nome);
    }

    /**
     * Retorna os campos do formulário
     */
    public function obtCampos()
    {
        return $this->decorado->obtCampos();
    }

    /**
     * Limpa formulário
     */
    public function limpa()
    {
        return $this->decorado->limpa();
    }

    /**
     * Define os dados do formulário
     */
    public function defDados($objeto)
    {
        return $this->decorado->defDados($objeto);
    }

    /**
     * Obtém dados do formulário
     */
    public function obtDados($classe = 'stdClass')
    {
        return $this->decorado->obtDados($classe);
    }

    /**
     * Valida dados do formulário
     */
    public function valida()
    {
        return $this->decorado->valida(); 
    }

    /**
     * Exibe o formulário decorado
     */
    public function exibe()
    {
        $camposPorLinha = $this->decorado->obtCamposPorLinha();
        if ($this->elemento->{'class'} == 'form-horizontal') {
            $larguraClasse = array(1 => array(3,9), 2 => array(2,4), 3 => array(2,2));
            $classeRotulo  = $larguraClasse[$camposPorLinha][0];
            $campoClasse   = $larguraClasse[$camposPorLinha][1];
        }

        $contaCampo = 0;

        $linhas_entrada = $this->decorado->obtLinhasEntrada();

        if ($linhas_entrada) {
            foreach ($linhas_entrada as $linha_entrada) {
                $rotulo_campo   = $linha_entrada[0];
                $campos         = $linha_entrada[1];
                $obrigatorio    = $linha_entrada[2];
                $linha_original = $linha_entrada[3];

                # formulário vertical não agrupa elementos, apenas altera a classe de grade agrupamento de formulário
                if (empty($this->grupoCorrente) OR ($contaCampo % $camposPorLinha) == 0 OR (strpos($this->elemento->{'class'}, 'form-vertical') !== FALSE)) {
                    // adiciona o campo ao recipiente
                    $this->grupoCorrente = new Elemento('div');

                    foreach ($linha_original->obtPropriedades() as $propriedade => $valor) {
                        $this->grupoCorrente->$propriedade = $valor;
                    }

                    $this->grupoCorrente->{'class'}  = 'row tformrow from-group'; // ###
                    $this->grupoCorrente->{'class'} .= ( (strpos($this->elemento->{'class'}, 'form-vertical') !== FALSE) ? ' col-sm-'.(12/$camposPorLinha) : '');
                    $this->elemento->adic($this->grupoCorrente);
                }

                $grupo = $this->grupoCorrente;

                if ($rotulo_campo instanceof Rotulo) {
                    $rotulo = $rotulo_campo;
                } else {
                    $rotulo = new Elemento('label');
                    $rotulo->adic($rotulo_campo);
                }

                if ($this->elemento->{'class'} == 'form-inline'){
                    $rotulo->{'style'} = 'padding-left: 3px; font-weight: bold';
                } else {
                    $rotulo->{'style'} = 'font-weight: bold; margin-bottom: 3px';
                    if ($this->elemento->{'class'} == 'form-horizontal') {
                        $rotulo->{'class'} = 'col-sm-'.$classeRotulo.' control-label';
                    } else {
                        $rotulo->{'class'} = ' control-label';
                    }
                }

                if (count($campos) == 1 AND $campos[0] instanceof Oculto) {
                    $grupo->adic('');
                    $grupo->{'style'} = 'display: none';
                } else {
                    $grupo->adic($rotulo);
                }

                if ($this->elemento->{'class'} !== 'form-inline') {
                    $col = new Elemento('div');
                    if ($this->elemento->{'class'} == 'form-horizontal') {
                        $col->{'class'} = 'col-sm-'.$campoClasse . ' fb-field-container';
                    }

                    $grupo->adic($col);
                }

                foreach ($campos as $campo) {
                    if ($this->elemento->{'class'} == 'form-inline') {
                        $rotulo->{'style'} .= ';float: left';
                        $grupo->adic(BootstrapConstrutorFormulario::embalaCampo($campo, 'inline-block'));
                    } else {
                        $col->adic(BootstrapConstrutorFormulario::embalaCampo($campo, 'inline-block'));
                    }
                }

                $contaCampo++;
            }
        }

        if ($this->decorado->obtBotoesAcao()) {
            $grupo = new Elemento('div');
            $grupo->{'class'} = 'form-group';
            $col = new Elemento('div');

            if ($this->elemento->{'class'} == 'form-horizontal') {
                $col->{'class'} = 'col-sm-offset-'.$classeRotulo.' col-sm-'.$campoClasse;
            }

            $i = 0;
            foreach ($this->decorado->obtBotoesAcao() as $acao) {
                $col->adic($acao);
                $i++;
            }

            $grupo->adic($col);
            $this->elemento->adic($grupo);
        }

        $this->elemento->{'width'} = '100%';
        $this->elemento->exibe();
    }
}