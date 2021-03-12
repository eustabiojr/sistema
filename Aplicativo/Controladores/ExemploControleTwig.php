<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 11/03/2021
 ************************************************************************************/

use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
  * Class ExemploControleTwig
  */
class ExemploControleTwig extends Pagina
{
    public function __construct()
    {
        parent::__construct();

        $carrega = new FilesystemLoader('Aplicativo/Recursos');
        $twig = new Environment($carrega);
        $template = $twig->loadTemplate('form.html');

        $substituicoes = array();

        $substituicoes['titulo']   = "Título";
        $substituicoes['acao']     = 'inicio.php?classe=ExemploControleTwig&metodo=aoGravar';
        $substituicoes['nome']     = "Maria";
        $substituicoes['endereco'] = "Rua das Flores";
        $substituicoes['telefone'] = "(51) 1234-5678";

        $conteudo = $template->render($substituicoes);
        parent::adic($conteudo);
    }

    public function aoGravar($params)
    {
        echo '<pre>';
            var_dump($_POST);
        echo '</pre>';  
    }
}