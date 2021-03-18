<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 17/03/2021
 ************************************************************************************/

use Dompdf\Dompdf;
use Dompdf\Options;
use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\Recipiente\Painel;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbrulhoForm;
use Estrutura\Bugigangas\Form\Data;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class RelatorioContas
 */
class RelatorioContas extends Pagina
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->conexao = 'exemplo';
        $this->registroAtivo = 'Conta';

        # Instância um formulário
        $this->form = new EmbrulhoForm(new Form('form_relat_contas'));
        $this->form->defTitulo('Relatório de Contas');

        # cria os campos do formulário
        $data_ini = new Data('data_ini');
        $data_fim = new Data('data_fim');

        $this->form->adicCampo('Vencimento Inicial', $data_ini, '50%');
        $this->form->adicCampo('Vencimento Final', $data_fim, '50%');

        $this->form->adicAcao('Gerar', new Acao(array($this, 'aoGerar')));
        $this->form->adicAcao('PDF', new Acao(array($this, 'aoGerarPDF')));

        # adiciona o formulário à página
        parent::adic($this->form);
    }

    public function aoGerar() 
    {
        $carregador = new FilesystemLoader('Aplicativo/Recursos');
        $twig = new Environment($carregador);
        $template = $twig->loadTemplate('relatorio_contas.html');

        # obtém os dados do formulário
        $dados = $this->form->obtDados();

        # joga os campos de volta ao formulário 
        $this->form->defDados($dados);

        $data_ini = $dados->data_ini;
        $data_fim = $dados->data_fim;

        # vetor de parâmetros para o template
        $substituicoes = array();
        $substituicoes['data_ini'] = $dados->data_ini;
        $substituicoes['data_fim'] = $dados->data_fim;

        try {
            # inicia transação com o banco de dados
            Transacao::abre($this->conexao);

            # instancia um repositório de classe Conta
            $repositorio = new Repositorio($this->registroAtivo);

            # cria um critério de seleção por intervalo de datas
            $criterio = new Criterio;
            $criterio->defPropriedade('ORDER', 'data_vencimento');

            if ($dados->data_ini) {
                $criterio->adic('data_vencimento', '>=', $data_ini);
            }

            if ($dados->data_fim) {
                $criterio->adic('data_vencimento', '>=', $data_fim);
            }

            # lê todos as contas que satisfazem ao critério de busca
            $contas = $repositorio->carrega($criterio);

            if ($contas) {
                foreach ($contas as $conta) {
                    $array_conta = $conta->paraArray();
                    $array_conta['nome_cliente'] = $conta->cliente->nome;
                    $substituicoes['contas'][] = $array_conta;
                }
            }
            # finaliza a transação
            Transacao::fecha();

        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }

        $conteudo = $template->render($substituicoes);

        $titulo  = 'Contas';
        $titulo .= (!empty($dados->data_ini)) ? ' de ' . $dados->data_ini : '';
        $titulo .= (!empty($dados->data_fim)) ? ' até ' . $dados->data_fim : '';
        
        # cria um painel para conter o formulário
        $painel = new Painel($titulo);
        $painel->adic($conteudo);
        parent::adic($painel);
        return $conteudo;
    }

    public function aoGerarPDF($param)
    {
        $html = $this->aoGerar($param);

        $opcoes = new Options();
        $opcoes->set('dpi', '128');

        # DomPDF converte o HTML para PDF
        $dompdf = new Dompdf($opcoes);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        # escreve o arquivo e abre em tela
        $nomearquivo = 'tmp/contas.pdf';
        if(is_writable('tmp')) {
            file_put_contents($nomearquivo, $dompdf->output());
            echo "<script>window.open('{$nomearquivo}');</script>";
        } else {
            new Mensagem('erro', 'Permissão negada em: ' . $nomearquivo);
        }
    }
}