<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 18/03/2021
 ************************************************************************************/

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class RelatorioProdutos
 */
class RelatorioProdutos extends Pagina
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
        $template = $twig->loadTemplate('relatorio_produtos.html');

        # vetor de parâmetros para o template
        $substituicoes = array();

        # gerador de código de barras em HTML
        $gerador = new \Picqer\Barcode\BarcodeGeneratorHTML();

        # gerador QRCode em SVG
        $renderizador = new \BaconQrCode\Renderer\ImageRenderer(
            new RendererStyle(256),
            new SvgImageBackEnd()
        );

        $escritor = new \BaconQrCode\Writer($renderizador);

        try {
            # inicia transação com o banco 
            Transacao::abre($this->conexao);

            $produtos = Produto::todos();
            foreach ($produtos as $produto) {
                $produto->codigobarras = $gerador->getBarcode($produto->id, $gerador::TYPE_CODE_128, 5, 100);
                $produto->qrcode = $escritor->writeString($produto->id . ' ' . $produto->descricao);
            }
            $substituicoes['produtos'] = $produtos;
            Transacao::fecha();
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }

        $conteudo = $template->render($substituicoes);

        // cria um cartao para conter o formulário
        $cartao = new Cartao('Produtos');
        $cartao->adic($conteudo);
        parent::adic($cartao);
    }
}