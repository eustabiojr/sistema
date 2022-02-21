<?php

use Estrutura\Base\BuscaPadrao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embalagem\ComboBD;
use Estrutura\Bugigangas\Embalagem\GrupoRadioBD;
use Estrutura\Bugigangas\Embalagem\GrupoVerificacaoBD;
use Estrutura\Bugigangas\Embalagem\ListaClassificacaoBD;
use Estrutura\Bugigangas\Embalagem\MultiBuscaBD;
use Estrutura\Bugigangas\Embalagem\SelecionaBD;
use Estrutura\Bugigangas\Form\Arquivo;
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\BotaoBusca;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Cor;
use Estrutura\Bugigangas\Form\Data;
use Estrutura\Bugigangas\Form\Deslizante;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\Giratorio;
use Estrutura\Bugigangas\Form\GrupoRadio;
use Estrutura\Bugigangas\Form\GrupoVerifica;
use Estrutura\Bugigangas\Form\ListaClassificacao;
use Estrutura\Bugigangas\Form\MultiBusca;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Bugigangas\Form\Seleciona;
use Estrutura\Bugigangas\Form\Senha;
use Estrutura\Bugigangas\Form\Texto;
use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Bugigangas\Gradedados\GradeDadosAcao;
use Estrutura\Bugigangas\Gradedados\NavegacaoPagina;
use Estrutura\Bugigangas\Recipiente\BlocoDeNotas;
use Estrutura\Bugigangas\Recipiente\Moldura;
use Estrutura\Bugigangas\Recipiente\Painel;
use Estrutura\Bugigangas\Recipiente\Tabela;
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\ConfigAplicativo;
use Estrutura\Validacao\ValidadorObrigatorio;

/**
 * Interface builder that takes a XML file save by Adianti Studio Designer and renders the form into the interface.
 *
 * @version    7.0
 * @package    wrapper
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 * @alias      TUIBuilder
 */
class ConstrutorUI extends Painel
{
    protected $controlador;
    protected $form;
    protected $campos;
    protected $acoes;
    protected $camposPorNome;
    
    /**
     * Construtor Classe 
     * @param $largura Panel width
     * @param $altura Panel height
     */
    public function __construct($largura, $altura)
    {
        parent::__construct($largura, $altura);
        $this->campos       = array();
        $this->acoes      = array();
        $this->camposPorNome = array();
    }
    
    /**
     * Return the found actions
     */
    public function obtAcoes()
    {
        return $this->acoes;
    }
    
    /**
     * Parse XML form file
     * @param $nomearquivo XML form file path
     */
    public function analisaArquivo($nomearquivo)
    {
        $xml = new SimpleXMLElement(file_get_contents($nomearquivo));
        $widgets = $this->analisaElemento($xml);
        
        if ($widgets)
        {
            foreach ($widgets as $widget)
            {
                if ($widget instanceof Moldura)
                {
                    $tamanho = $widget->obtTamanho();
                    parent::defTamanho( $tamanho[0], $tamanho[1] );
                }
                
                if ($widget instanceof BlocoDeNotas)
                {
                    $tamanho = $widget->obtTamanho();
                    parent::defTamanho( $tamanho[0], $tamanho[1] + 40); // spacings
                }
            }
        }
    }
    
    /**
     * 
     */
    public function criarRotulo($propriedades)
    {
        $widget = new Rotulo((string) $propriedades->{'name'});
        $widget->defValor((string) $propriedades->{'value'});
        $widget->defCorFonte((string) $propriedades->{'color'});
        $widget->defTamanhoFonte((string) $propriedades->{'size'});
        $widget->defEstiloFonte((string) $propriedades->{'style'});
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criarBotao($propriedades)
    {
        $widget = new Botao((string) $propriedades->{'name'});
        $widget->defImagem((string) $propriedades->{'icon'});
        $widget->defRotulo((string) $propriedades->{'value'});
        //if (is_callable(array($this->controlador, (string) $propriedades->{'action'})))
        {
            $widget->defAcao(new Acao(array($this->controlador, (string) $propriedades->{'action'})), (string) $propriedades->{'value'});
        }
        $this->campos[] = $widget;
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criarEntrada($propriedades)
    {
        $widget = new Entrada((string) $propriedades->{'name'});
        $widget->defValor((string) $propriedades->{'value'});
        $widget->defMascara((string) $propriedades->{'mask'});
        $widget->defTamanho((int) $propriedades->{'width'});
        if (isset($propriedades->{'maxlen'})) // added later (not in the first version)
        {
            $widget->defComprimentoMax((int) $propriedades->{'maxlen'});
        }
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
        
        if (isset($propriedades->{'required'}) AND $propriedades->{'required'} == '1') // added later (not in the first version)
        {
            $widget->adicValidacao((string) $propriedades->{'name'}, new ValidadorObrigatorio);
        }
        
        $widget->defEditavel((string) $propriedades->{'editable'});
        $this->campos[] = $widget;
        $this->camposPorNome[(string)$propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criarGiratorio($propriedades)
    {
        $widget = new Giratorio((string) $propriedades->{'name'});
        $widget->defIntervalo((int) $propriedades->{'min'}, (int) $propriedades->{'max'}, (int) $propriedades->{'step'});
        $widget->defValor((string) $propriedades->{'value'});
        $widget->defTamanho((int) $propriedades->{'width'});
        
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
        
        $widget->defEditavel((string) $propriedades->{'editable'});
        $this->campos[] = $widget;
        $this->camposPorNome[(string)$propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criarControleDeslizante($propriedades)
    {
        $widget = new Deslizante((string) $propriedades->{'name'});
        $widget->defIntervalo((int) $propriedades->{'min'}, (int) $propriedades->{'max'}, (int) $propriedades->{'step'});
        $widget->defValor((string) $propriedades->{'value'});
        $widget->defTamanho((int) $propriedades->{'width'});
        
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
        
        $widget->defEditavel((string) $propriedades->{'editable'});
        $this->campos[] = $widget;
        $this->camposPorNome[(string)$propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaSenha($propriedades)
    {
        $widget = new Senha((string) $propriedades->{'name'});
        $widget->defValor((string) $propriedades->{'value'});
        $widget->defTamanho((int) $propriedades->{'width'});
        $widget->defEditavel((string) $propriedades->{'editable'});
        
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
        
        if (isset($propriedades->{'required'}) AND $propriedades->{'required'} == '1') // added later (not in the first version)
        {
            $widget->adicValidacao((string) $propriedades->{'name'}, new ValidadorObrigatorio);
        }
        
        $this->campos[] = $widget;
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaData($propriedades)
    {
        $widget = new Data((string) $propriedades->{'name'});
        $widget->defValor((string) $propriedades->{'value'});
        $widget->defTamanho((int) $propriedades->{'width'});
        $widget->defEditavel((string) $propriedades->{'editable'});
        
        if ((string) $propriedades->{'mask'})
        {
            $widget->defMascara((string) $propriedades->{'mask'});
        }
        
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
        
        if (isset($propriedades->{'required'}) AND $propriedades->{'required'} == '1') // added later (not in the first version)
        {
            $widget->adicValidacao((string) $propriedades->{'name'}, new ValidadorObrigatorio);
        }
        
        $this->campos[] = $widget;
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaArquivo($propriedades)
    {
        $widget = new Arquivo((string) $propriedades->{'name'});
        $widget->defTamanho((int) $propriedades->{'width'});
        $widget->defEditavel((string) $propriedades->{'editable'});
        
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
        
        if (isset($propriedades->{'required'}) AND $propriedades->{'required'} == '1') // added later (not in the first version)
        {
            $widget->adicValidacao((string) $propriedades->{'name'}, new ValidadorObrigatorio);
        }
        
        $this->campos[] = $widget;
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaCor($propriedades)
    {
        $widget = new Cor((string) $propriedades->{'name'});
        $widget->defValor((string) $propriedades->{'value'});
        $widget->defTamanho((int) $propriedades->{'width'});
        $widget->defEditavel((string) $propriedades->{'editable'});
        
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
        
        if (isset($propriedades->{'required'}) AND $propriedades->{'required'} == '1') // added later (not in the first version)
        {
            $widget->adicValidacao((string) $propriedades->{'name'}, new ValidadorObrigatorio);
        }
        
        $this->campos[] = $widget;
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaBotaoBusca($propriedades)
    {
        $widget = new BotaoBusca((string) $propriedades->{'name'});
        $widget->defTamanho((int) $propriedades->{'width'});
        
        if ( ($propriedades->{'database'}) AND ($propriedades->{'model'}) )
        {
            $obj = new BuscaPadrao;
            $action = new Acao(array($obj, 'onSetup'));
            $action->defParametro('database',      (string) $propriedades->{'database'});
            if (isset($this->form))
            {
                if ($this->form instanceof Form)
                {
                    $action->defParametro('parent', $this->form->obtNome());
                }
            }
            
            $database      = (string) $propriedades->{'database'};
            $model         = (string) $propriedades->{'model'};
            $display_field = (string) $propriedades->{'display'};
            
            $ini  = ConfigAplicativo::obt();
            $seed = NOME_APLICATIVO . ( !empty($ini['general']['seed']) ? $ini['general']['seed'] : 's8dkld83kf73kf094' );
            
            $action->defParametro('hash',          md5("{$seed}{$database}{$model}{$display_field}"));
            $action->defParametro('model',         (string) $propriedades->{'model'});
            $action->defParametro('display_field', (string) $propriedades->{'display'});
            $action->defParametro('receive_key',   (string) $propriedades->{'name'});
            $action->defParametro('receive_field', (string) $propriedades->{'receiver'});
            $widget->defAcao($action);
        }

        $this->campos[] = $widget;
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaImagem($propriedades)
    {
        if (file_exists((string) $propriedades->{'image'}))
        {
            $widget = new Imagem((string) $propriedades->{'image'});
        }
        else
        {
            $widget = new Rotulo((string) 'Imagem não encontrada: ' . $propriedades->{'image'});
        }
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaTexto($propriedades)
    {
        $widget = new Texto((string) $propriedades->{'name'});
        $widget->defValor((string) $propriedades->{'value'});
        $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
        
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
        
        if (isset($propriedades->{'required'}) AND $propriedades->{'required'} == '1') // added later (not in the first version)
        {
            $widget->adicValidacao((string) $propriedades->{'name'}, new ValidadorObrigatorio);
        }
        
        $this->campos[] = $widget;
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaVerificaGrupo($propriedades)
    {
        $widget = new GrupoVerifica((string) $propriedades->{'name'});
        $widget->defEsboco('vertical');
	    $partes = explode("\n", (string) $propriedades->{'items'});
	    $items = array();
	    if ($partes)
	    {
	        foreach ($partes as $linha)
	        {
    	        $parte = explode(':', $linha);
    	        $items[$parte[0]] = $parte[1];
	        }
	    }
	    $widget->adicItens($items);
	    if (isset($propriedades->{'value'}))
	    {
	        $widget->defValor(explode(',', (string) $propriedades->{'value'}));
	    }
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function makeTDBCheckGroup($propriedades)
    {
        $widget = new GrupoVerificacaoBD((string) $propriedades->{'name'},
                                    (string) $propriedades->{'database'},
                                    (string) $propriedades->{'model'},
                                    (string) $propriedades->{'key'},
                                    (string) $propriedades->{'display'} );
        $widget->defEsboco('vertical');
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function makeTRadioGroup($propriedades)
    {
        $widget = new GrupoRadio((string) $propriedades->{'name'});
        $widget->defEsboco('vertical');
	    $partes = explode("\n", (string) $propriedades->{'items'});
	    $items = array();
	    if ($partes)
	    {
	        foreach ($partes as $linha)
	        {
    	        $parte = explode(':', $linha);
    	        $items[$parte[0]] = $parte[1];
	        }
	    }
	    $widget->adicItens($items);
	    $widget->defValor((string) $propriedades->{'value'});
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function makeTDBRadioGroup($propriedades)
    {
        $widget = new GrupoRadioBD((string) $propriedades->{'name'},
                                    (string) $propriedades->{'database'},
                                    (string) $propriedades->{'model'},
                                    (string) $propriedades->{'key'},
                                    (string) $propriedades->{'display'} );
        $widget->defEsboco('vertical');
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaCombo($propriedades)
    {
        $widget = new Combo((string) $propriedades->{'name'});
	    $partes = explode("\n", (string) $propriedades->{'items'});
	    $items = array();
	    if ($partes)
	    {
	        foreach ($partes as $linha)
	        {
    	        $parte = explode(':', $linha);
    	        $items[$parte[0]] = $parte[1];
	        }
	    }
	    $widget->adicItens($items);
	    if (isset($propriedades->{'value'}))
	    {
	        $widget->defValor((string) $propriedades->{'value'});
	    }
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $widget->defTamanho((int) $propriedades->{'width'});
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaComboBD($propriedades)
    {
        $widget = new ComboBD((string) $propriedades->{'name'},
                               (string) $propriedades->{'database'},
                               (string) $propriedades->{'model'},
                               (string) $propriedades->{'key'},
                               (string) $propriedades->{'display'} );
	    $widget->defTamanho((int) $propriedades->{'width'});
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaSeleciona($propriedades)
    {
        $widget = new Seleciona((string) $propriedades->{'name'});
	    $partes = explode("\n", (string) $propriedades->{'items'});
	    $items = array();
	    if ($partes)
	    {
	        foreach ($partes as $linha)
	        {
    	        $parte = explode(':', $linha);
    	        $items[$parte[0]] = $parte[1];
	        }
	    }
	    $widget->adicItens($items);
	    if (isset($propriedades->{'value'}))
	    {
	        $widget->defValor((string) $propriedades->{'value'});
	    }
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaSelecionaBD($propriedades)
    {
        $widget = new SelecionaBD((string) $propriedades->{'name'},
                               (string) $propriedades->{'database'},
                               (string) $propriedades->{'model'},
                               (string) $propriedades->{'key'},
                               (string) $propriedades->{'display'} );
	    $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaListaClassificacao($propriedades)
    {
        $widget = new ListaClassificacao((string) $propriedades->{'name'});
	    $partes = explode("\n", (string) $propriedades->{'items'});
	    $items = array();
	    if ($partes)
	    {
	        foreach ($partes as $linha)
	        {
    	        $parte = explode(':', $linha);
    	        $items[$parte[0]] = $parte[1];
	        }
	    }
	    $widget->adicItens($items);
	    if (isset($propriedades->{'value'}))
	    {
	        $widget->defValor((string) $propriedades->{'value'});
	    }
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
	    $widget->defPropriedade('style', 'box-sizing: border-box !important', FALSE);
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaListaClassificacaoBD($propriedades)
    {
        $widget = new ListaClassificacaoBD((string) $propriedades->{'name'},
                               (string) $propriedades->{'database'},
                               (string) $propriedades->{'model'},
                               (string) $propriedades->{'key'},
                               (string) $propriedades->{'display'} );
	    $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
	    $widget->defPropriedade('style', 'box-sizing: border-box !important', FALSE);
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaMultiBusca($propriedades)
    {
        $widget = new MultiBusca((string) $propriedades->{'name'});
	    $partes = explode("\n", (string) $propriedades->{'items'});
	    $items = array();
	    if ($partes)
	    {
	        foreach ($partes as $linha)
	        {
    	        $parte = explode(':', $linha);
    	        $items[$parte[0]] = $parte[1];
	        }
	    }
	    
	    $widget->adicItens($items);
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
	    $widget->defPropriedade('style', 'box-sizing: border-box !important', FALSE);
	    $widget->defComprimentoMin( (int) $propriedades->{'minlen'} );
	    $widget->defComprimentoMax( (int) $propriedades->{'maxsize'} );
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaMultiBuscaBD($propriedades)
    {
        $widget = new MultiBuscaBD((string) $propriedades->{'name'},
                               (string) $propriedades->{'database'},
                               (string) $propriedades->{'model'},
                               (string) $propriedades->{'key'},
                               (string) $propriedades->{'display'} );
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
	    $widget->defPropriedade('style', 'box-sizing: border-box !important', FALSE);
	    $widget->defComprimentoMin( (int) $propriedades->{'minlen'} );
	    $widget->defTamanhoMax( (int) $propriedades->{'maxsize'} ); # ***
	    
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function criaBlocoDeNotas($propriedades)
    {
        $largura  = (int) $propriedades->{'width'};
        $altura = (int) $propriedades->{'height'} - 30; // correction for sheet tabs
        $widget = new BlocoDeNotas($largura, $altura);
        if ($propriedades->{'pages'})
        {
            foreach ($propriedades->{'pages'} as $pagina)
            {
                $atributos = $pagina->atributos();
                $nome  = $atributos['tab'];
                $classe = get_class($this); // for inheritance
                $painel = new $classe((int) $propriedades->{'width'} -2, (int) $propriedades->{'height'} -23);
                
                // pass the controller and form ahead.
                $painel->defControlador($this->controlador);
                $painel->defForm($this->form);
                // parse element
                $painel->analisaElemento($pagina);
                // integrate the notebook' fields
                $this->camposPorNome = array_merge( (array) $this->camposPorNome, (array) $painel->getWidgets());
                $this->campos       = array_merge( (array) $this->campos,       (array) $painel->getFields());
                
                $widget->anexaPagina((string) $nome, $painel);
            }
        }
        
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaMoldura($propriedades)
    {
        $largura  = PHP_SAPI == 'cli' ? (int) $propriedades->{'width'} -2  : (int) $propriedades->{'width'} -12;
        $altura = PHP_SAPI == 'cli' ? (int) $propriedades->{'height'} -2 : (int) $propriedades->{'height'} -12;
        $widget = new Moldura($largura, $altura);
        $classe = get_class($this); // for inheritance
        $painel = new $classe($largura, $altura);
        // pass the controller and form ahead.
        $painel->defControlador($this->controlador);
        $painel->defForm($this->form);
        
        if ($propriedades->{'child'})
        {
            foreach ($propriedades->{'child'} as $child)
            {
                $painel->analisaElemento($child);
                
                // integrate the frame' fields
                $this->camposPorNome = array_merge( (array) $this->camposPorNome, (array) $painel->getWidgets());
                $this->campos       = array_merge( (array) $this->campos,       (array) $painel->getFields());
            }
        }
        $widget->defLegenda((string) $propriedades->{'title'});
        $widget->adic($painel);
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function criaGradeDados($propriedades)
    {
        $table  = new Tabela;
        $widget = new Gradedados;
        $widget->defAltura((string) $propriedades->{'height'});
        
        if ($propriedades->{'columns'})
        {
            foreach ($propriedades->{'columns'} as $Column)
            {
                $dgcolumn = new ColunaGradedados((string) $Column->{'name'},
                                                (string) $Column->{'label'},
                                                (string) $Column->{'align'},
                                                (string) $Column->{'width'} );
                $widget->adicColuna($dgcolumn);
                $this->camposPorNome[(string)$Column->{'name'}] = $dgcolumn;
            }
        }
        
        if ($propriedades->{'actions'})
        {
            foreach ($propriedades->{'actions'} as $Acao)
            {
                //if (is_callable(array($this->controlador, (string) $Acao->{'method'})))
                {
                    $dgaction = new GradeDadosAcao(array($this->controlador, (string) $Acao->{'method'}));
                    $dgaction->defRotulo((string) $Acao->{'label'});
                    $dgaction->defImagem((string) $Acao->{'image'});
                    $dgaction->defCampo((string) $Acao->{'field'});
                
                    $widget->adicAcao($dgaction);
                }
                //$this->camposPorNome[(string)$propriedades->Name] = $column;
            }
        }
        
        if ((string)$propriedades->{'pagenavigator'} == 'yes')
        {
            $loader = (string) $propriedades->{'loader'} ? (string) $propriedades->{'loader'} : 'onReload';
            $pageNavigation = new NavegacaoPagina;
            $pageNavigation->defAcao(new Acao(array($this->controlador, $loader)));
            $pageNavigation->defLargura($widget->obtLargura());
        }
        
        $widget->criaModelo();
        
        $row = $table->adicLinha();
        $row->adicCelula($widget);
        if (isset($pageNavigation))
        {
            $row = $table->adicLinha();
            $row->adicCelula($pageNavigation);
            $widget->defNavegacaoPagina($pageNavigation);
        }
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        $widget = $table;
        
        return $widget;
    }
    
    /**
     * parse a xml element 
     * @param $xml SimpleXMLElement object
     * @ignore-autocomplete on
     */
    private function analisaElemento($xml)
    {
        $erros = array();
        $widgets = array();
        
        foreach ($xml as $objeto)
        {
            try
            {
                $classe = (string)$objeto->{'class'};
                $propriedades = (array)$objeto;
                if (in_array(ini_get('php-gtk.codepage'), array('ISO8859-1', 'ISO-8859-1') ) )
                {
                    array_walk_recursive($propriedades, array($this, 'arrayToIso8859'));
                }
                $propriedades = (object)$propriedades;
                
                $widget = NULL;
                switch ($classe)
                {
                    case 'T'.'Label':
                        $widget = $this->criarRotulo($propriedades);
                        break;
                    case 'T'.'Button':
                        $widget = $this->criarBotao($propriedades);
                        break;
                    case 'T'.'Entry':
                        $widget = $this->criarEntrada($propriedades);
                        break;
                    case 'T'.'Password':
                        $widget = $this->criaSenha($propriedades);
                        break;
                    case 'T'.'Date':
                        $widget = $this->criaData($propriedades);
                        break;
                    case 'T'.'File':
                        $widget = $this->criaArquivo($propriedades);
                        break;
                    case 'T'.'Color':
                        $widget = $this->criaCor($propriedades);
                        break;
                    case 'T'.'SeekButton':
                        $widget = $this->criaBotaoBusca($propriedades);
                        break;
                    case 'T'.'Image':
                        $widget = $this->criaImagem($propriedades);
                        break;
                    case 'T'.'Text':
                        $widget = $this->criaTexto($propriedades);
                        break;
                    case 'T'.'CheckGroup':
                        $widget = $this->criaVerificaGrupo($propriedades);
                        break;
                    case 'T'.'DBCheckGroup':
                        $widget = $this->makeTDBCheckGroup($propriedades);
                        break;
                    case 'T'.'RadioGroup':
                        $widget = $this->makeTRadioGroup($propriedades);
                        break;
                    case 'T'.'DBRadioGroup':
                        $widget = $this->makeTDBRadioGroup($propriedades);
                        break;
                    case 'T'.'Combo':
                        $widget = $this->criaCombo($propriedades);
                        break;
                    case 'T'.'DBCombo':
                        $widget = $this->criaComboBD($propriedades);
                        break;
                    case 'T'.'Notebook':
                        $widget = $this->criaBlocoDeNotas($propriedades);
                        break;
                    case 'T'.'Frame':
                        $widget = $this->criaMoldura($propriedades);
                        break;
                    case 'T'.'DataGrid':
                        $widget = $this->criaGradeDados($propriedades);
                        break;
                    case 'T'.'Spinner':
                        $widget = $this->criarGiratorio($propriedades);
                        break;
                    case 'T'.'Slider':
                        $widget = $this->criarControleDeslizante($propriedades);
                        break;
                    case 'T'.'Select':
                        $widget = $this->criaSeleciona($propriedades);
                        break;
                    case 'T'.'DBSelect':
                        $widget = $this->criaSelecionaBD($propriedades);
                        break;
                    case 'T'.'SortList':
                        $widget = $this->criaListaClassificacao($propriedades);
                        break;
                    case 'T'.'DBSortList':
                        $widget = $this->criaListaClassificacaoBD($propriedades);
                        break;
                    case 'T'.'MultiSearch':
                        $widget = $this->criaMultiBusca($propriedades);
                        break;
                    case 'T'.'DBMultiSearch':
                        $widget = $this->criaMultiBuscaBD($propriedades);
                        break;
                }
                
                if ($widget)
                {
                    parent::colocar($widget, (int) $propriedades->{'x'}, (int) $propriedades->{'y'});
                    $widgets[] = $widget;
                }
            }
            catch(Exception $e)
            {
                $erros[] = $e->getMessage();
            }
        
        }
        
        if ($erros)
        {
            new Mensagem('erro', implode('<br>', $erros));
        }
        return $widgets;
    }
    
    /**
     * Converts an array to iso8859
     * @param $value current value
     * @param $key current key
     * @ignore-autocomplete on
     */
    private function arrayToIso8859(&$value, $key)
    {
        if (is_scalar($value))
        {
            $value = utf8_decode($value);
        }
    }
    
    /**
     * Defines the UI controller
     * @param $objeto Controller Object
     */
    public function defControlador($objeto)
    {
        $this->controlador = $objeto;
    }
    
    /**
     * Defines the Parent Form
     * @param $objeto Form
     */
    public function defForm($form)
    {
        $this->form = $form;
    }
    
    /**
     * Return the UI widgets (form fields)
     */
    public function obtCampos()
    {
        return $this->campos;
    }
    
    /**
     * Return the parsed widgets
     */
    public function obtWidgets()
    {
        return $this->camposPorNome;
    }
    
    /**
     * Return the widget by name
     * @param $nome Widget name
     */
    public function obtWidget($nome)
    {
        if (isset($this->camposPorNome[$nome]))
        {
            return $this->camposPorNome[$nome];
        }
        else
        {
            throw new Exception("Widget {$nome} não encontrado");
        } 
    }
}
