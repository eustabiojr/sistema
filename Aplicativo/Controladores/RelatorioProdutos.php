<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 18/03/2021
 ************************************************************************************/

use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class RelatorioProdutos
 */
class RelatorioProdutos extends Pagina
{
    public function __construct()
    {
        parent::__construct();

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Conta';

        $carregador = new FilesystemLoader('Aplicativo/Recursos');
        $twig = new Environment($carregador);
        $template = $twig->loadTemplate('relatorio_produtos.html');
    }
}