<?php
/**
 * TradutorAplicativo
 *
 * @version    7.0
 * @package    util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TradutorAplicativo
{
    private static $instancia; // singleton instance
    private $mensagens;
    private $paravrasEmPortugues;
    private $idioma;            // target language
    
    /**
     * Class Constructor
     */
    private function __construct()
    {
        $this->mensagens['en'][] = 'File not found';
        $this->mensagens['en'][] = 'Search';
        $this->mensagens['en'][] = 'Register';
        $this->mensagens['en'][] = 'Record saved';
        $this->mensagens['en'][] = 'Do you really want to delete ?';
        $this->mensagens['en'][] = 'Record deleted';
        $this->mensagens['en'][] = 'Function';
        $this->mensagens['en'][] = 'Table';
        $this->mensagens['en'][] = 'Tool';
        $this->mensagens['en'][] = 'Data';
        $this->mensagens['en'][] = 'Open';
        $this->mensagens['en'][] = 'New';
        $this->mensagens['en'][] = 'Save';
        $this->mensagens['en'][] = 'Find';
        $this->mensagens['en'][] = 'Delete';
        $this->mensagens['en'][] = 'Edit';
        $this->mensagens['en'][] = 'Cancel';
        $this->mensagens['en'][] = 'Yes';
        $this->mensagens['en'][] = 'No';
        $this->mensagens['en'][] = 'January';
        $this->mensagens['en'][] = 'February';
        $this->mensagens['en'][] = 'March';
        $this->mensagens['en'][] = 'April';
        $this->mensagens['en'][] = 'May';
        $this->mensagens['en'][] = 'June';
        $this->mensagens['en'][] = 'July';
        $this->mensagens['en'][] = 'August';
        $this->mensagens['en'][] = 'September';
        $this->mensagens['en'][] = 'October';
        $this->mensagens['en'][] = 'November';
        $this->mensagens['en'][] = 'December';
        $this->mensagens['en'][] = 'Today';
        $this->mensagens['en'][] = 'Close';
        $this->mensagens['en'][] = 'The field ^1 can not be less than ^2 characters';
        $this->mensagens['en'][] = 'The field ^1 can not be greater than ^2 characters';
        $this->mensagens['en'][] = 'The field ^1 can not be less than ^2';
        $this->mensagens['en'][] = 'The field ^1 can not be greater than ^2';
        $this->mensagens['en'][] = 'The field ^1 is required';
        $this->mensagens['en'][] = 'The field ^1 has not a valid CNPJ';
        $this->mensagens['en'][] = 'The field ^1 has not a valid CPF';
        $this->mensagens['en'][] = 'The field ^1 contains an invalid e-mail';
        $this->mensagens['en'][] = 'Permission denied';
        $this->mensagens['en'][] = 'Generate';
        $this->mensagens['en'][] = 'List';
        $this->mensagens['en'][] = 'Detail';
        $this->mensagens['en'][] = 'Back';
        $this->mensagens['en'][] = 'Clear';
        $this->mensagens['en'][] = 'Program';
        $this->mensagens['en'][] = 'Path';
        $this->mensagens['en'][] = 'Results';
        
        $this->mensagens['pt'][] = 'Arquivo não encontrado';
        $this->mensagens['pt'][] = 'Buscar';
        $this->mensagens['pt'][] = 'Cadastrar';
        $this->mensagens['pt'][] = 'Registro salvo';
        $this->mensagens['pt'][] = 'Deseja realmente excluir ?';
        $this->mensagens['pt'][] = 'Registro excluído';
        $this->mensagens['pt'][] = 'Função';
        $this->mensagens['pt'][] = 'Tabela';
        $this->mensagens['pt'][] = 'Ferramenta';
        $this->mensagens['pt'][] = 'Dados';
        $this->mensagens['pt'][] = 'Abrir';
        $this->mensagens['pt'][] = 'Novo';
        $this->mensagens['pt'][] = 'Salvar';
        $this->mensagens['pt'][] = 'Buscar';
        $this->mensagens['pt'][] = 'Excluir';
        $this->mensagens['pt'][] = 'Editar';
        $this->mensagens['pt'][] = 'Cancelar';
        $this->mensagens['pt'][] = 'Sim';
        $this->mensagens['pt'][] = 'Não';
        $this->mensagens['pt'][] = 'Janeiro';
        $this->mensagens['pt'][] = 'Fevereiro';
        $this->mensagens['pt'][] = 'Março';
        $this->mensagens['pt'][] = 'Abril';
        $this->mensagens['pt'][] = 'Maio';
        $this->mensagens['pt'][] = 'Junho';
        $this->mensagens['pt'][] = 'Julho';
        $this->mensagens['pt'][] = 'Agosto';
        $this->mensagens['pt'][] = 'Setembro';
        $this->mensagens['pt'][] = 'Outubro';
        $this->mensagens['pt'][] = 'Novembro';
        $this->mensagens['pt'][] = 'Dezembro';
        $this->mensagens['pt'][] = 'Hoje';
        $this->mensagens['pt'][] = 'Fechar';
        $this->mensagens['pt'][] = 'O campo ^1 não pode ter menos de ^2 caracteres';
        $this->mensagens['pt'][] = 'O campo ^1 não pode ter mais de ^2 caracteres';
        $this->mensagens['pt'][] = 'O campo ^1 não pode ser menor que ^2';
        $this->mensagens['pt'][] = 'O campo ^1 não pode ser maior que ^2';
        $this->mensagens['pt'][] = 'O campo ^1 é obrigatório';
        $this->mensagens['pt'][] = 'O campo ^1 não contém um CNPJ válido';
        $this->mensagens['pt'][] = 'O campo ^1 não contém um CPF válido';
        $this->mensagens['pt'][] = 'O campo ^1 contém um e-mail inválido';
        $this->mensagens['pt'][] = 'Permissão negada';
        $this->mensagens['pt'][] = 'Gerar';
        $this->mensagens['pt'][] = 'Listar';
        $this->mensagens['pt'][] = 'Detalhe';
        $this->mensagens['pt'][] = 'Voltar';
        $this->mensagens['pt'][] = 'Limpar';
        $this->mensagens['pt'][] = 'Programa';
        $this->mensagens['pt'][] = 'Caminho';
        $this->mensagens['pt'][] = 'Resultados';

        
        $this->paravrasEmPortugues = [];
        foreach ($this->mensagens['pt'] as $chave => $valor)
        {
            $this->paravrasEmPortugues[$valor] = $chave;
        }
    }
    
    /**
     * Returns the singleton instance
     * @return  Instance of self
     */
    public static function obtInstancia()
    {
        // if there's no instance
        if (empty(self::$instancia))
        {
            // creates a new object
            self::$instancia = new self;
        }
        // returns the created instance
        return self::$instancia;
    }
    
    /**
     * Define the target language
     * @param $idioma     Target language index
     */
    public static function defIdioma($idioma)
    {
        $instancia = self::obtInstancia();
        $instancia->idioma = $idioma;
    }
    
    /**
     * Retorna o idioma alvo
     * @return Target indice idioma
     */
    public static function obtIdioma()
    {
        $instancia = self::obtInstancia();
        return $instancia->idioma;
    }
    
    /**
     * Traduz a palavra para o idioma alvo
     * @param $palavra     Palavra a ser traduzida
     * @return          Traduzido palavra
     */
    public static function traduz($palavra, $param1 = NULL, $param2 = NULL, $param3 = NULL)
    {
        // get the self unique instance
        $instancia = self::obtInstancia();
        // search by the numeric index of the word
        
        if (isset($instancia->paravrasEmPortugues[$palavra]) and !is_null($instancia->paravrasEmPortugues[$palavra]))
        {
            $chave = $instancia->paravrasEmPortugues[$palavra]; //$chave = array_search($palavra, $instancia->mensagens['en']);
            
            // get the target language
            $idioma = self::obtIdioma();
            // returns the translated word
            $mensagem = $instancia->mensagens[$idioma][$chave];
            
            if (isset($param1))
            {
                $mensagem = str_replace('^1', $param1, $mensagem);
            }
            if (isset($param2))
            {
                $mensagem = str_replace('^2', $param2, $mensagem);
            }
            if (isset($param3))
            {
                $mensagem = str_replace('^3', $param3, $mensagem);
            }
            return $mensagem;
        }
        else
        {
            return 'Mensagem não encontrada: '. $palavra;
        }
    }
    
    /**
     * Translate a template file
     */
    public static function traduzTemplate($template)
    {
        // get the self unique instance
        $instancia = self::obtInstancia();
        // search by translated words
        if(preg_match_all( '!_t\{(.*?)\}!i', $template, $coincidencia ) > 0)
        {
            foreach($coincidencia[1] as $palavra)
            {
                $traduzido = _t($palavra);
                $template = str_replace('_t{'.$palavra.'}', $traduzido, $template);
            }
        }
        return $template;
    }
}

/**
 * Facade to translate words
 * @param $palavra  Word to be translated
 * @param $param1 optional ^1
 * @param $param2 optional ^2
 * @param $param3 optional ^3
 * @return Translated word
 */
function _t($msg, $param1 = null, $param2 = null, $param3 = null)
{
        return TradutorAplicativo::traduz($msg, $param1, $param2, $param3);
}
