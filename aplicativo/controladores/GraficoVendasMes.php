<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 18/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Classe GraficoVendasMes 4910116 197203008
 */
class GraficoVendasMes extends Pagina
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
        $template = $twig->loadTemplate('vendas_mes.html');

        try {
            # inicia transação com o banco de dados
            Transacao::abre($this->conexao);
            $vendas = Venda::obtVendasMes();

            Transacao::fecha();
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }

        # vetor de parâmetros para o template
        $substituicoes = array();
        $substituicoes['titulo'] = 'Vendas por mês';
        $substituicoes['rotulos'] = json_encode(array_keys($vendas));
        $substituicoes['dados'] = json_encode(array_values($vendas));

        $conteudo = $template->render($substituicoes);

        # cria um painel para conter o formulário
        $painel = new Cartao('Vendas/Mês');
        $painel->adic($conteudo);

        parent::adic($painel);
    }
}