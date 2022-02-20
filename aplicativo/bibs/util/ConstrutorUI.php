<?php

use Ageunet\Validacao\ValidadorObrigatorio;
use Estrutura\Base\BuscaPadrao;
use Estrutura\Bugigangas\Form\Arquivo;
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\BotaoBusca;
use Estrutura\Bugigangas\Form\Cor;
use Estrutura\Bugigangas\Form\Data;
use Estrutura\Bugigangas\Form\Deslizante;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\Giratorio;
use Estrutura\Bugigangas\Form\GrupoVerifica;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Bugigangas\Form\Senha;
use Estrutura\Bugigangas\Form\Texto;
use Estrutura\Bugigangas\Recipiente\BlocoDeNotas;
use Estrutura\Bugigangas\Recipiente\Moldura;
use Estrutura\Bugigangas\Recipiente\Painel;
use Estrutura\Bugigangas\Util\Imagem;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\ConfigAplicativo;

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
        $widgets = $this->parseElement($xml);
        
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
        //if (is_callable(array($this->controller, (string) $propriedades->{'action'})))
        {
            $widget->setAction(new Acao(array($this->controller, (string) $propriedades->{'action'})), (string) $propriedades->{'value'});
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
                    $action->defParametro('parent', $this->form->getName());
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
            $widget->setAction($action);
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
    public function makeTCheckGroup($propriedades)
    {
        $widget = new GrupoVerifica((string) $propriedades->{'name'});
        $widget->setLayout('vertical');
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
        $widget = new TDBCheckGroup((string) $propriedades->{'name'},
                                    (string) $propriedades->{'database'},
                                    (string) $propriedades->{'model'},
                                    (string) $propriedades->{'key'},
                                    (string) $propriedades->{'display'} );
        $widget->setLayout('vertical');
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
        $widget = new TRadioGroup((string) $propriedades->{'name'});
        $widget->setLayout('vertical');
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
        $widget = new TDBRadioGroup((string) $propriedades->{'name'},
                                    (string) $propriedades->{'database'},
                                    (string) $propriedades->{'model'},
                                    (string) $propriedades->{'key'},
                                    (string) $propriedades->{'display'} );
        $widget->setLayout('vertical');
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
    public function makeTCombo($propriedades)
    {
        $widget = new TCombo((string) $propriedades->{'name'});
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
    public function makeTDBCombo($propriedades)
    {
        $widget = new TDBCombo((string) $propriedades->{'name'},
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
    public function makeTSelect($propriedades)
    {
        $widget = new TSelect((string) $propriedades->{'name'});
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
    public function makeTDBSelect($propriedades)
    {
        $widget = new TDBSelect((string) $propriedades->{'name'},
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
    public function makeTSortList($propriedades)
    {
        $widget = new TSortList((string) $propriedades->{'name'});
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
	    $widget->setProperty('style', 'box-sizing: border-box !important', FALSE);
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function makeTDBSortList($propriedades)
    {
        $widget = new TDBSortList((string) $propriedades->{'name'},
                               (string) $propriedades->{'database'},
                               (string) $propriedades->{'model'},
                               (string) $propriedades->{'key'},
                               (string) $propriedades->{'display'} );
	    $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
	    $widget->setProperty('style', 'box-sizing: border-box !important', FALSE);
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
    public function makeTMultiSearch($propriedades)
    {
        $widget = new TMultiSearch((string) $propriedades->{'name'});
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
	    $widget->setProperty('style', 'box-sizing: border-box !important', FALSE);
	    $widget->setMinLength( (int) $propriedades->{'minlen'} );
	    $widget->setMaxSize( (int) $propriedades->{'maxsize'} );
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function makeTDBMultiSearch($propriedades)
    {
        $widget = new TDBMultiSearch((string) $propriedades->{'name'},
                               (string) $propriedades->{'database'},
                               (string) $propriedades->{'model'},
                               (string) $propriedades->{'key'},
                               (string) $propriedades->{'display'} );
        if (isset($propriedades->{'tip'})) // added later (not in the first version)
        {
            $widget->defDica((string) $propriedades->{'tip'});
        }
	    $widget->defTamanho((int) $propriedades->{'width'}, (int) $propriedades->{'height'});
	    $widget->setProperty('style', 'box-sizing: border-box !important', FALSE);
	    $widget->setMinLength( (int) $propriedades->{'minlen'} );
	    $widget->setMaxSize( (int) $propriedades->{'maxsize'} );
	    
	    $this->campos[] = $widget;
	    $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
	    
        return $widget;
    }
    
    /**
     * 
     */
    public function makeTNotebook($propriedades)
    {
        $largura  = (int) $propriedades->{'width'};
        $altura = (int) $propriedades->{'height'} - 30; // correction for sheet tabs
        $widget = new TNotebook($largura, $altura);
        if ($propriedades->{'pages'})
        {
            foreach ($propriedades->{'pages'} as $page)
            {
                $attributes = $page->attributes();
                $name  = $attributes['tab'];
                $class = get_class($this); // for inheritance
                $panel = new $class((int) $propriedades->{'width'} -2, (int) $propriedades->{'height'} -23);
                
                // pass the controller and form ahead.
                $panel->setController($this->controller);
                $panel->setForm($this->form);
                // parse element
                $panel->parseElement($page);
                // integrate the notebook' fields
                $this->camposPorNome = array_merge( (array) $this->camposPorNome, (array) $panel->getWidgets());
                $this->campos       = array_merge( (array) $this->campos,       (array) $panel->getFields());
                
                $widget->appendPage((string) $name, $panel);
            }
        }
        
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function makeTFrame($propriedades)
    {
        $largura  = PHP_SAPI == 'cli' ? (int) $propriedades->{'width'} -2  : (int) $propriedades->{'width'} -12;
        $altura = PHP_SAPI == 'cli' ? (int) $propriedades->{'height'} -2 : (int) $propriedades->{'height'} -12;
        $widget = new TFrame($largura, $altura);
        $class = get_class($this); // for inheritance
        $panel = new $class($largura, $altura);
        // pass the controller and form ahead.
        $panel->setController($this->controller);
        $panel->setForm($this->form);
        
        if ($propriedades->{'child'})
        {
            foreach ($propriedades->{'child'} as $child)
            {
                $panel->parseElement($child);
                
                // integrate the frame' fields
                $this->camposPorNome = array_merge( (array) $this->camposPorNome, (array) $panel->getWidgets());
                $this->campos       = array_merge( (array) $this->campos,       (array) $panel->getFields());
            }
        }
        $widget->setLegend((string) $propriedades->{'title'});
        $widget->add($panel);
        $this->camposPorNome[(string) $propriedades->{'name'}] = $widget;
        
        return $widget;
    }
    
    /**
     * 
     */
    public function makeTDataGrid($propriedades)
    {
        $table  = new TTable;
        $widget = new TDataGrid;
        $widget->setHeight((string) $propriedades->{'height'});
        
        if ($propriedades->{'columns'})
        {
            foreach ($propriedades->{'columns'} as $Column)
            {
                $dgcolumn = new TDataGridColumn((string) $Column->{'name'},
                                                (string) $Column->{'label'},
                                                (string) $Column->{'align'},
                                                (string) $Column->{'width'} );
                $widget->addColumn($dgcolumn);
                $this->camposPorNome[(string)$Column->{'name'}] = $dgcolumn;
            }
        }
        
        if ($propriedades->{'actions'})
        {
            foreach ($propriedades->{'actions'} as $Action)
            {
                //if (is_callable(array($this->controller, (string) $Action->{'method'})))
                {
                    $dgaction = new TDataGridAction(array($this->controller, (string) $Action->{'method'}));
                    $dgaction->setLabel((string) $Action->{'label'});
                    $dgaction->setImage((string) $Action->{'image'});
                    $dgaction->setField((string) $Action->{'field'});
                
                    $widget->addAction($dgaction);
                }
                //$this->camposPorNome[(string)$propriedades->Name] = $column;
            }
        }
        
        if ((string)$propriedades->{'pagenavigator'} == 'yes')
        {
            $loader = (string) $propriedades->{'loader'} ? (string) $propriedades->{'loader'} : 'onReload';
            $pageNavigation = new TPageNavigation;
            $pageNavigation->setAction(new Acao(array($this->controller, $loader)));
            $pageNavigation->setWidth($widget->getWidth());
        }
        
        $widget->createModel();
        
        $row = $table->addRow();
        $row->addCell($widget);
        if (isset($pageNavigation))
        {
            $row = $table->addRow();
            $row->addCell($pageNavigation);
            $widget->setPageNavigation($pageNavigation);
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
    private function parseElement($xml)
    {
        $errors = array();
        $widgets = array();
        
        foreach ($xml as $object)
        {
            try
            {
                $class = (string)$object->{'class'};
                $propriedades = (array)$object;
                if (in_array(ini_get('php-gtk.codepage'), array('ISO8859-1', 'ISO-8859-1') ) )
                {
                    array_walk_recursive($propriedades, array($this, 'arrayToIso8859'));
                }
                $propriedades = (object)$propriedades;
                
                $widget = NULL;
                switch ($class)
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
                        $widget = $this->makeTPassword($propriedades);
                        break;
                    case 'T'.'Date':
                        $widget = $this->makeTDate($propriedades);
                        break;
                    case 'T'.'File':
                        $widget = $this->makeTFile($propriedades);
                        break;
                    case 'T'.'Color':
                        $widget = $this->makeTColor($propriedades);
                        break;
                    case 'T'.'SeekButton':
                        $widget = $this->criaBotaoBusca($propriedades);
                        break;
                    case 'T'.'Image':
                        $widget = $this->criaImagem($propriedades);
                        break;
                    case 'T'.'Text':
                        $widget = $this->makeTText($propriedades);
                        break;
                    case 'T'.'CheckGroup':
                        $widget = $this->makeTCheckGroup($propriedades);
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
                        $widget = $this->makeTCombo($propriedades);
                        break;
                    case 'T'.'DBCombo':
                        $widget = $this->makeTDBCombo($propriedades);
                        break;
                    case 'T'.'Notebook':
                        $widget = $this->makeTNotebook($propriedades);
                        break;
                    case 'T'.'Frame':
                        $widget = $this->makeTFrame($propriedades);
                        break;
                    case 'T'.'DataGrid':
                        $widget = $this->makeTDataGrid($propriedades);
                        break;
                    case 'T'.'Spinner':
                        $widget = $this->makeTSpinner($propriedades);
                        break;
                    case 'T'.'Slider':
                        $widget = $this->makeTSlider($propriedades);
                        break;
                    case 'T'.'Select':
                        $widget = $this->makeTSelect($propriedades);
                        break;
                    case 'T'.'DBSelect':
                        $widget = $this->makeTDBSelect($propriedades);
                        break;
                    case 'T'.'SortList':
                        $widget = $this->makeTSortList($propriedades);
                        break;
                    case 'T'.'DBSortList':
                        $widget = $this->makeTDBSortList($propriedades);
                        break;
                    case 'T'.'MultiSearch':
                        $widget = $this->makeTMultiSearch($propriedades);
                        break;
                    case 'T'.'DBMultiSearch':
                        $widget = $this->makeTDBMultiSearch($propriedades);
                        break;
                }
                
                if ($widget)
                {
                    parent::put($widget, (int) $propriedades->{'x'}, (int) $propriedades->{'y'});
                    $widgets[] = $widget;
                }
            }
            catch(Exception $e)
            {
                $errors[] = $e->getMessage();
            }
        
        }
        
        if ($errors)
        {
            new TMessage('error', implode('<br>', $errors));
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
     * @param $object Controller Object
     */
    public function setController($object)
    {
        $this->controller = $object;
    }
    
    /**
     * Defines the Parent Form
     * @param $object Form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }
    
    /**
     * Return the UI widgets (form fields)
     */
    public function getFields()
    {
        return $this->campos;
    }
    
    /**
     * Return the parsed widgets
     */
    public function getWidgets()
    {
        return $this->camposPorNome;
    }
    
    /**
     * Return the widget by name
     * @param $name Widget name
     */
    public function getWidget($name)
    {
        if (isset($this->camposPorNome[$name]))
        {
            return $this->camposPorNome[$name];
        }
        else
        {
            throw new Exception("Widget {$name} not found");
        } 
    }
}
