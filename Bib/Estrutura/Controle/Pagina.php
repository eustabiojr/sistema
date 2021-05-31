<?php
/** ***********************************************************************************
 * Classe Pagina
 * 
 * Data: 01/03/2021
 **************************************************************************************/

# Espaço de nomes
namespace Estrutura\Controle;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Script;
use Exception;

/**
 * Classe Pagina 
 * 
 * Esta classe futuramente deverá extender a classe Elemento.
 */
class Pagina extends Elemento {

    # propriedades da classe
    private $corpo;
    private $construido;
    private static $jscarregado;
    private static $csscarregado;
    private static $cssregistrado;

    /**
     * Método __construct
     */
    public function __construct()
    {
        parent::__construct('div');
        $this->construido = TRUE;
    }

    /**
     * Define o recipiente alvo para o conteúdo da pagina
     */
    public function defRecipienteAlvo($recipiente)
    {
        $this->{'ageunet_recipiente_alvo'} = $recipiente;
    }

    /**
     * Método executa
     */
    public function executa() {

        # Só entra no IF caso exista dados GET
        if ($_GET) {

            $classe = $_GET['classe'] ?? NULL;
            $metodo = $_GET['metodo'] ?? NULL;

            # caso a variável $classe esteja definida, entra no IF
            if ($classe) {

                /**
                 * testa se o nome da classe fornecido pelo URI é igual ao nome da classe
                 * do objeto atual. Se for igual retorna para na variável $objeto, o objeto 
                 * atual. Caso contrário retorna uma nova instancia da classe requisitada.
                 */
                $objeto = $classe == get_class($this) ? $this : new $classe; # 'Sim' : 'Não';
                
                if (is_callable(array($objeto, $metodo))) 
                {

                    /**
					 * Essa função é interessante. Pois com ela podemos chamar o método classe
					 * por meio de parametros enviados pela URL. Ou seja, chamamos aquela 
                     * classe que herda a classe 'Pagina', e seus métodos.
					 */
                    call_user_func(array($objeto, $metodo), $_REQUEST);
                }
            } else if (function_exists($metodo)) {
                call_user_func($metodo, $_REQUEST);
            }
        }
    }

    /**
     * Inclui uma função javascript específica a esta pagina
     * @param $js localização do javascript
     */
    public static function inclui_js($js)
    {
        self::$jscarregado[$js] = TRUE;
    }

    /**
     * Inclui uma folha de estilo específica a esta pagina
     * @param $css folha de estilo (Cascading Stylesheet)
     */
    public static function inclui_css($css)
    {
        self::$csscarregado[$css] = TRUE;
    }

    /**
     * Registra uma folha de estilo específica a esta pagina
     * @param $nomecss nome da folha de estilo (Cascading Stylesheet)
     * @param $codigocss código da folha de estilo (Cascading Stylesheet)
     */
    public static function registra_css($nomecss, $codigocss)
    {
        self::$cssregistrado[$nomecss] = $codigocss;
    }

    /**
     * Abre um diálogo de arquivo
     * @param $arquivo nome do arquivo
     */
    public static function abreArquivo($arquivo)
    {
        Script::cria("__ageunet_baixar_arquivo('{$arquivo}')");
    }

    /**
     * Obtém arquivos carregados de folhas de estilo
     * @ignore-autocompleta ligado
     */
    public static function obtCSSCarregado()
    {
        $css = self::$csscarregado;
        $csc = self::$cssregistrado;
        $texto_css = '';

        if($css) {
            foreach ($css as $arquivocss => $bool) {
                $texto_css .= "    <link rel='stylesheet' type='text/css' media='screen' href='$arquivocss'/>\n";
            }
        }

        if($csc) {
            $texto_css .= "    <link type='text/css' media='screen'/>\n";
            foreach ($csc as $nomecss => $codigocss) {
                $texto_css .= $codigocss;
            }
            $texto_css .= "    </style>\n";
        }
        return $texto_css;
    }

    /**
     * Obtém arquivos javascript carregados
     * @ignore-autocompleta ligado
     */
    public static function obtJSCarregado()
    {
        $js = self::$jscarregado;
        $texto_js = '';

        if($js) {
            foreach ($js as $arquivojs => $bool) {
                $texto_js .= "    <script language='Javascript' src='$arquivojs'></script>\n";
            }
        }
        return $texto_js;
    }

    public static function ehCelular()
    {
        $ehCelular = FALSE;

        if (PHP_SAPI == 'cli') {
            return FALSE;
        }

        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) OR isset($_SERVER['HTTP_PROFILE'])) {
            $ehCelular = TRUE;
        }

        $navegadoresCelular = array(
                            'android',   'audiovox', 'blackberry', 'epoc',
                            'ericsson', ' iemobile', 'ipaq',       'iphone', 'ipad', 
                            'ipod',      'j2me',     'midp',       'mmp',
                            'mobile',    'motorola', 'nitro',      'nokia',
                            'opera mini','palm',     'palmsource', 'panasonic',
                            'phone',     'pocketpc', 'samsung',    'sanyo',
                            'series60',  'sharp',    'siemens',    'smartphone',
                            'sony',      'symbian',  'toshiba',    'treo',
                            'up.browser','up.link',  'wap',        'wap',
                            'windows ce','htc'

        );

        foreach ($navegadoresCelular as $nc) {
            if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), $nc) !== FALSE) {
                $ehCelular = TRUE;
            }
        }
        return $ehCelular;
    }

    /**
     * Intecepta caso alguém atribua um novo valor de propriedade
     * @param $nome nome da propriedade
     * @param $valor valor da propriedade
     */
    public function __set($nome, $valor) 
    {
        parent::__set($nome, $valor);
        $this->nome = $valor;
    }

    /**
     * Decide que ação tomar e exibir a página
     */
    public function exibe()
    {
        if (!$this->obtEstaEmbalado()) {
            $this->executa();
        }
        parent::exibe();

        if (!$this->construido) {
            throw new Exception("Você deve executar o construtor de {__CLASS__}");
        }
    }
}

