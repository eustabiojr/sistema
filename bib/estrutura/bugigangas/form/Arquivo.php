<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
 namespace Estrutura\Bugigangas\Form;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Estrutura\Bugigangas\Form\Campo;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\ConfigAplicativo;
use Estrutura\Nucleo\NucleoTradutor;
use Exception;

/**
  * Class Rotulo
  */
class Arquivo extends Campo implements InterfaceBugiganga
{   
    protected $id;
    protected $altura;
    protected $completaAcao;
    protected $classeUploader;
    protected $placeHolder;
    protected $extensoes;
    protected $modoExibicao;
    protected $semente;
    protected $manipuladorArquivo;
    protected $galeriaImagens;
    protected $galleryWidth;
    protected $galleryHeight;
    protected $popover;
    protected $tituloPop;
    protected $conteudoPop;
    
    /**
     * Método Construtor 
     * @param $nome input nome
     */
    public function __construct($nome)
    {
        parent::__construct($nome);
        $this->id = $this->nome . '_' . mt_rand(1000000000, 1999999999);
        $this->classeUploader = 'AgeuServicoSubida';
        $this->manipuladorArquivo = FALSE;
        
        $ini = ConfigAplicativo::obt();
        $this->semente = NOME_APLICATIVO . ( !empty($ini['geral']['semente']) ? $ini['geral']['semente'] : 's8dkld83kf73kf094' );
        $this->galeriaImagens = false;
        $this->popover = false;
    }
    
    /**
     * Habilita visão da galeria de imagens
     */
    public function habilitaGaleriaImagens($largura = null, $altura = 100)
    {
        $this->galeriaImagens  = true;
        $this->larguraGaleria  = is_null($largura) ? 'unset' : $largura;
        $this->alturaGaleria   = is_null($altura)  ? 'unset' : $altura;
    }
    
    /**
     * Habilita popover
     * @param $titulo Title
     * @param $conteudo Content
     */
    public function habilitaPopover($titulo = null, $conteudo = '')
    {
        $this->popover     = TRUE;
        $this->tituloPop   = $titulo;
        $this->conteudoPop = $conteudo;
    }
    
    /**
     * Define o modo de exibição {arquivo}
     */
    public function defModoExibicao($modo) 
    {
        $this->modoExibicao = $modo;
    }
    
    /**
     * Define a classe de serviço para resposta
     */
    public function defServico($servico)
    {
        $this->classeUploader = $servico;
    }
    
    /**
     * Define as extensoes permitidas
     */
    public function defExtensoesPermitidas($extensoes)
    {
        $this->extensoes = $extensoes;
        $this->tag->{'accept'} = '.' . implode(',.', $extensoes);
    }
    
    /**
     * Define o manipulador arquivo
     */
    public function habilitaManipuladorArquivo()
    {
        $this->manipuladorArquivo = TRUE;
    }
    
    /**
     * Define o place holder
     */
    public function defPlaceHolder(Elemento $bugiganga)
    {
        $this->placeHolder = $bugiganga;
    }
    
    /**
     * Define o tamanho do campo
     */
    public function defTamanho($largura, $altura = NULL)
    {
        $this->tamanho   = $largura;
    }
    
    /**
     * Define a altura do campo
     */
    public function defAltura($altura)
    {
        $this->altura = $altura;
    }
    
    /**
     * Retorna os dados postados
     */
    public function obtDadosPost() : string
    {
        $nome = str_replace(['[',']'], ['',''], $this->nome);
        
        if (isset($_POST[$nome]))
        {
            return $_POST[$nome];
        }
    }
    
    /**
     * Define o valor do campo
     */
    public function defValor($valor)
    {
        if ($this->manipuladorArquivo)
        {
            if (strpos($valor, '%7B') === false)
            {
                if (!empty($valor))
                {
                    $this->valor = urlencode(json_encode(['fileName'=>$valor]));
                }
            }
            else
            {
                $valor_object = json_decode(urldecode($valor));
                
                if (!empty($valor_object->{'apagArquivo'}) AND $valor_object->{'apagArquivo'} == $valor_object->{'nomeArquivo'})
                {
                    $valor = '';
                }
                
                parent::defValor($valor);
            }
        }
        else
        {
            parent::defValor($valor);
        }
    }
    
    /**
     * Exibe a bugiganga na tela
     */
    public function exibe()
    {
        // define as propriedades da tag
        $this->tag->{'id'}       = $this->id;
        $this->tag->{'name'}     = 'arquivo_' . $this->nome;  // tag nome
        $this->tag->{'receiver'} = $this->nome;  // tag nome
        $this->tag->{'value'}    = $this->valor; // tag value
        $this->tag->{'type'}     = 'file';       // input type
        
        if (!empty($this->tamanho))
        {
            if (strstr($this->tamanho, '%') !== FALSE)
            {
                $this->defPropriedade('style', "width:{$this->tamanho};", false); //aggregate style info
            }
            else
            {
                $this->defPropriedade('style', "width:{$this->tamanho}px;", false); //aggregate style info
            }
        }
        
        if (!empty($this->altura))
        {
            $this->defPropriedade('style', "height:{$this->altura}px;", false); //aggregate style info
        }
        
        $nomeArquivoHd = new Oculto($this->nome);
        $nomeArquivoHd->defValor( $this->valor );
        
        $completa_acao = "'undefined'";
        
        # verifica se o campo é editável
        if (parent::obtEditavel())
        {
            if (isset($acao))
            {
                if (!Form::obtFormPeloNome($this->formName) instanceof Form)
                {
                    throw new Exception(NucleoTradutor::traduz('Você deve passar o &1 (&2) como parâmetro para &3', __CLASS__, $this->nome,
                         'Form::defCampos()'));
                }
                
                $string_acao  = $acao->serializa(FALSE);
                $completa_acao = "function() { __adianti_post_lookup('{$this->formName}', '{$string_acao }', '{$this->id}', 'callback'); tfile_update_download_link('{$this->nome}') }";
            }
        }
        else
        {
            // torna o campo somente leitura
            $this->tag->{'readonly'} = "1";
            $this->tag->{'type'} = 'text';
            $this->tag->{'class'} = 'tfield_disabled'; // CSS
        }
        
        $div = new Elemento('div');
        $div->{'style'} = "display:inline;width:100%;";
        $div->{'id'} = 'div_file_'.mt_rand(1000000000, 1999999999);
        $div->{'class'} = 'div_file';
        
        $div->adic( $nomeArquivoHd );
        if ($this->placeHolder)
        {
            $div->adic( $this->tag );
            $div->adic( $this->placeHolder );
            $this->tag->{'style'} = 'display:none';
        }
        else
        {
            $div->adic( $this->tag );
        }
        
        if ($this->modoExibicao == 'file' AND file_exists($this->valor))
        {
            $icone = Elemento::tag('i', null, ['class' => 'fa fa-download']);
            $link = new Elemento('a');
            $link->{'id'}     = 'view_'.$this->nome;
            $link->{'href'}   = 'baixar.php?arquivo='.$this->valor;
            $link->{'target'} = 'baixar';
            $link->{'style'}  = 'padding: 4px; display: block';
            $link->adic($icone);
            $link->adic($this->valor);
            $div->adic( $link );
        }
        
        $div->exibe();
        
        if (empty($this->extensoes))
        {
            $acao = "motor.php?classe={$this->classeUploader}";
        } else {
            $hash = md5("{$this->semente}{$this->nome}".base64_encode(serialize($this->extensoes)));
            $acao = "motor.php?classe={$this->classeUploader}&nome={$this->nome}&hash={$hash}&extensoes=".base64_encode(serialize($this->extensoes));
        }
        
        $manipuladorArquivo = $this->manipuladorArquivo ? '1' : '0';
        $galeriaImagens = json_encode(['habilitado'=> $this->galeriaImagens ? '1' : '0', 'width' => $this->galleryWidth, 'height' => $this->galleryHeight]);
        $popover = json_encode(['habilitado' => $this->popover ? '1' : '0', 'title' => $this->tituloPop, 'conteudo' => base64_encode($this->conteudoPop)]);
        
        Script::cria(" tfile_start( '{$this->tag-> id}', '{$div-> id}', '{$acao}', {$completa_acao}, $manipuladorArquivo, '$galeriaImagens', '$popover');");
    }
    
    /**
     * Define ação a ser executada quando o usuário deixa o campo do formulário
     * @param $acao objeto Acao 
     */
    function defcompletaAcao(Acao $acao) 
    {
        if ($acao->ehEstatico())
        {
            $this->completaAcao = $acao;
        }
        else
        {
            $string_acao = $acao->paraString();
            throw new Exception(NucleoTradutor::traduz('A ação (&1) deve ser estática para ser usado em &2', $string_acao, __METHOD__)); 
        }
    }
    
    /**
     * Habilita o campo
     * @param $nome_form nome do Formulário
     * @param $campo Nome campo
     */
    public static function habilitaCampo($nome_form, $campo)
    {
        Script::cria( " tfile_enable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Desabilita o campo
     * @param $nome_form nome do Formulário
     * @param $campo Nome campo
     */
    public static function desabilitaCampo($nome_form, $campo)
    {
        Script::cria( " tfile_disable_field('{$nome_form}', '{$campo}'); " );
    }
    
    /**
     * Limpa o campo
     * @param $nome_form nome do Formulário
     * @param $campo Nome campo
     */
    public static function limpaCampo($nome_form, $campo)
    {
        Script::cria( " tfile_clear_field('{$nome_form}', '{$campo}'); " );
    }
}