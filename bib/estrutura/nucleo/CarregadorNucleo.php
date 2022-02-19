<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Nucleo;

/**
 * Framework class autocarregaer
 *
 * @version    7.1
 * @package    core
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CarregadorNucleo
{
    static private $mapaClasse;
    
    /**
     * Load the class map
     */
    public static function carregaMapaClasse() 
    {
        self::$mapaClasse = MapaClasse::obtMapa();

        #echo '<pre>' . PHP_EOL;
            #print_r(self::$mapaClasse); # echo "<p>Classe: {$classe}</p>" . PHP_EOL;
        #echo '</pre>' . PHP_EOL;

        $apelidos = MapaClasse::obtApelidos();
        
        if ($apelidos)
        {
            foreach ($apelidos as $classe_antiga => $nova_classe)
            {
                if (class_exists($nova_classe))
                {
                    class_alias($nova_classe, $classe_antiga);
                }
            }
        }
    }
    
    /**
     * Define o caminho da classa
     * @param $classe Nome da classe
     * @param $caminho  Caminho da classe
     */
    public static function defCaminhoClasse($classe, $caminho)
    {
        self::$mapaClasse[$classe] = $caminho;
    }
    
    /**
     * Core autloader
     * @param $nomeClasse Nome da classe
     */
    public static function autocarrega($nomeClasse)
    {
        ///echo "<p> Carregando a classe: " . $nomeClasse . "</p>" . PHP_EOL;
        
        $nomeClasse = ltrim($nomeClasse, '\\');
        $nomeArquivo  = '';
        $namespace = '';
        if (strrpos($nomeClasse, '\\') !== FALSE)
        {
            $partes     = explode('\\', $nomeClasse);
            $nomeClasse = array_pop($partes);
            $namespace  = implode('\\', $partes);
        }

        ///echo "<p> O namespace e a classe são: {$namespace} | <b>" . $nomeClasse . "</b></p>" . PHP_EOL;
        $nomeArquivo = 'bib'.'\\'.strtolower($namespace).'\\'.$nomeClasse.'.php';
        #$nomeArquivo = 'Bib'.'\\'. $namespace .'\\'.$nomeClasse.'.php';
        $nomeArquivo = str_replace('\\', DIRECTORY_SEPARATOR, $nomeArquivo);

        /**********
         * Como a Classe Menu não possui namespace, o arquivo não é localizado.
         */
        /*if ($nomeClasse == 'Menu') {
            echo "<p>MENU1 MENU1 MENU1</p>" . PHP_EOL;
            echo "<p>Caminho: " . $nomeArquivo . "</p>" . PHP_EOL;
        }*/
        
        ///echo "<p> O caminho completo e o arquivo é: " . $nomeArquivo . "</p>" . PHP_EOL;
        if (file_exists($nomeArquivo))
        {
            #echo "PSR: $nomeClasse <br>";
            require_once $nomeArquivo;
            self::escopoGlobal($nomeClasse);
        }
        else
        {

            if (!self::AutocarregadorLegado($nomeClasse))
            {
                if (!CarregadorAplicativo::autocarrega($nomeClasse))
                {
                    if (file_exists('vendor/autocarrega_extras.php'))
                    {
                        require_once 'vendor/autocarrega_extras.php';
                    }
                }
            }
        }
    }
    
    /**
     * autocarregaer
     * @param $classe classname
     * 
     * Pelo visto este autocarregador é apenas para scripts PHP mais antigo.
     */
    public static function AutocarregadorLegado($classe)
    {
        # Caso esteja definido com base na classe informada.
        if (isset(self::$mapaClasse[$classe]))
        { 
            if (file_exists(self::$mapaClasse[$classe]))
            {
                ///echo '<p> >>>>> Mapa da classe (LEGADO)>>>>><b>: ' . self::$mapaClasse[$classe] . '</b></p><br>';
                require_once self::$mapaClasse[$classe];
                
                self::escopoGlobal($classe);
                return TRUE;
            } else {
                ///echo '<p>Arquivo: <b>' . self::$mapaClasse[$classe] . '</b> não existe.</p><br>';
            }
        }
    }
    
    /**
     * Torna a classe global
     */
    public static function escopoGlobal($classe)
    {
        if (isset(self::$mapaClasse[$classe]) AND self::$mapaClasse[$classe])
        {
            if (!class_exists($classe, FALSE))
            {
                $ns = self::$mapaClasse[$classe];
                $ns = str_replace('/', '\\', $ns);
                $ns = str_replace('bib\\estrutura', 'estrutura', $ns);
                $ns = str_replace('.class.php', '', $ns);
                $ns = str_replace('.php', '', $ns);
                
                ///echo "&nbsp;&nbsp;&nbsp;&nbsp;Mapeamento: $ns, $classe<br>";
                if (class_exists($ns) OR interface_exists($ns))
                {
                    class_alias($ns, $classe, FALSE);
                }
            }
        }
    }
}
