<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 17/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Classe RelatorioPessoas
 */
class RelatorioPessoas extends Pagina
{
    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Conta';

        $carregador = new FilesystemLoader('Aplicativo/Recursos');
        $twig = new Environment($carregador);
        $template = $twig->loadTemplate('relatorio_pessoas.html');

        # vetor de parâmetros para o template
        $substituicoes = array();

        try {
            # inicia transação com o banco de dados
            Transacao::abre($this->conexao);
            $substituicoes['pessoas'] = VisaoSaldoPessoa::todos();
            Transacao::fecha();
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }

        $conteudo = $template->render($substituicoes);

        # cria um cartao para conter o formulário
        $cartao = new Cartao('Pessoas');
        $cartao->adic($conteudo);

        parent::adic($cartao);
    }
}