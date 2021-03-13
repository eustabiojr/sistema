<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 12/03/2021
 ************************************************************************************/

use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ExemploControleTwigLista extends Pagina 
{
    public function __construct()
    {
        parent::__construct();

        $carregador = new FilesystemLoader('Aplicativo/Recursos');
        $twig = new Environment($carregador);
        $template = $twig->loadTemplate('lista.html');

        $substituicoes = array();
        $substituicoes['titulo'] = 'Lista de Pessoas';
        $substituicoes['pessoas'] = array(
            array('codigo' => '1',
                  'nome' => 'Anita Garibaldi',
                  'endereco' => 'Rua dos Gaudérios 1'),
            array('codigo' => '2',
                  'nome' => 'Bento Gonçalves',
                  'endereco' => 'Rua dos Gaudérios 2'),
            array('codigo' => '3',
                  'nome' => 'Giuseppe Garibaldi',
                  'endereco' => 'Rua dos Gaudérios 3'));
        $conteudo = $template->render($substituicoes);
        echo $conteudo;
    }
}