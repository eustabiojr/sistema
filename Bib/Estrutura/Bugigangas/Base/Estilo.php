<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 04/05/2021
 ********************************************************************************************/
namespace Estrutura\Bugigangas\Base;

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Controle\Pagina;

/**
 * Gerenciador de folha de estilo (CSS)
 *
 * @version    0.1
 * @package    widget
 * @subpackage base
 * @author     Pablo Dall'Oglio (e alterado por Eustábio J. Silva Jr.)
 * @copyright  ??
 * @license    ??
 */
class Estilo
{
    private $nome;           // stylesheet name
    private $propriedades;     // propriedades
    static private $carregado; // array of loaded styles
    static private $estilos;
    
    /**
     * Class Constructor
     * @param $mame Name of the style
     */
    public function __construct($nome)
    {
        $this->nome = $nome;
        $this->propriedades = array();
    }
    
    /**
     * Importa estilo
     * @param $estilo nome do arquivo de estilo
     */
    public static function importaDoArquivo($nomearquivo)
    {
        $estilo = new Elemento('style');
        $estilo->adic( file_get_contents($nomearquivo));
        $estilo->exibe();
    }
    
    /**
     * Returns the style name
     */
    public function obtNome()
    {
        return $this->nome;
    }
    
    /**
     * Localiza um estilo por meio de sua propriedade
     * @objeto objeto de estlo
     */
    public static function localizaEstilo($objeto)
    {
        if (self::$estilos)
        {
            foreach (self::$estilos as $estiloname => $estilo)
            {
                if ((array)$estilo->propriedades === (array)$objeto->propriedades)
                {
                    return $estiloname;
                }
            }
        }
    }
    
    /**
     * Executado sempre que a propriedade é atribuida
     * @param  $nome    = nome da propriedade
     * @param  $valor   = valor da propriedade
     */
    public function __set($nome, $valor)
    {
        # Substitui "_" por "-" nos nomes das propriedades
        $nome = str_replace('_', '-', $nome);
        
        # Armazena a propriedade de tag atribuida
        $this->propriedades[$nome] = $valor;
    }
    
    /**
     * Executado sempre que a propriedade é lida
     * @param  $nome    = nome das propriedades
     */
    public function __get($nome)
    {
        # Substitui "_" por "-" nos nomes das propriedades
        $nome = str_replace('_', '-', $nome);
        
        return $this->propriedades[$nome];
    }
    
    /**
     * Retorna se o estilo possui alguma conteúdo
     */
    public function possuiConteudo()
    {
        return count($this->propriedades) > 0;
    }
    
    /**
     * Obtém o conteúdo do estilo
     */
    public function obtConteudo()
    {
        // abre o estilo 
        $estilo = '';
        $estilo.= "    .{$this->nome}\n";
        $estilo.= "    {\n";
        if ($this->propriedades)
        {
            # Itera as propriedades do estilo
            foreach ($this->propriedades as $nome=>$valor)
            {
                $estilo.= "        {$nome}: {$valor};\n";
            }
        }
        $estilo.= "    }\n";
        return $estilo;
    }
    
    /**
     * Obtém código de estilo em linha
     */ 
    public function obtEmLinha()
    {
        $estilo = '';
        if ($this->propriedades)
        {
            # Itera as propriedades do estilo
            foreach ($this->propriedades as $nome => $valor)
            {
                $nome = str_replace('_', '-', $nome);
                $estilo.= "{$nome}: {$valor};";
            }
        }
        
        return $estilo;
    }
    
    /**
     * Exibe o estilo
     */
    public function exibe( $emlinha = FALSE)
    {
        // check if the style is already loaded
        if (!isset(self::$carregado[$this->nome]))
        {
            if ($emlinha)
            {
                echo "    <style type='text/css' media='screen'>\n";
                echo $this->obtConteudo();
                echo "    </style>\n";
            }
            else
            {
                $estilo = $this->obtConteudo();
                Pagina::registra_css($this->nome, $estilo);
                # marca o estilo como carregado
                self::$carregado[$this->nome] = TRUE;
                self::$estilos[$this->nome] = $this;
            }
        }
    }
}
