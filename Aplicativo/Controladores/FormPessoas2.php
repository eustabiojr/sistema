<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 15/03/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Classe FormPessoas
 * 
 * Pretendo implementar API para recurso NAVs do Bootstrap. 
 * 
 * Já o formulário, devido a sua complexidade, pretendo usar um template.
 */
class FormPessoas2 extends Pagina
{
    private $form;
    private $conexao;
    private $registroAtivo;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Pessoa';

        $carregador = new FilesystemLoader('Aplicativo/Recursos');
        $twig = new Environment($carregador);
        $template = $twig->loadTemplate('cadastro_pessoa.html');

        # vetor de parâmetros para o template
        $substituicoes = array();

        $conteudo = $template->render($substituicoes);

        $titulo = array('titulo_cartao' => $conteudo, 'sub_classe' => 'text-center');

        # Aqui no título na verdade incluimos conteudo HTML
        $cartao_int_cab = new Cartao($titulo, 'div');
        $cartao_int_cab->adic('');
        $cartao_int_cab->adicTituloCorpo("Titulo de tratamento especial");
        $cartao_int_cab->adicTextoCorpo("Com o texto de apoio abaixo como uma introdução natural para conteúdo adicional.");
        $cartao_int_cab->adicLinkCorpo("Ir para algum lugar");

        # cria um cartao para conter o formulário
        $parametros['titulo_cartao'] = 'Pessoas Exemplo';
        $cartao = new Cartao($parametros);
        $cartao->adic($cartao_int_cab);

        parent::adic($cartao);
    }
}
