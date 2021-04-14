<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 02/04/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Recipiente\AbasConteudo;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Base\Recipiente\Forms;
use Estrutura\Bugigangas\Base\Recipiente\ItensForm;
use Estrutura\Bugigangas\Base\Recipiente\ItensFormulario;
use Estrutura\Bugigangas\Base\Recipiente\NavItens;
use Estrutura\Bugigangas\Base\Recipiente\NavsAbas;
use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Classe FormPessoas
 */
class FormPessoas3 extends Pagina
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

        # Itens da aba 1
        $itens_form_1 = new ItensForm;
        # O segundo parâmetro aceita array e string. Caso seja um array vazio, o rótulo não será criado.
        $itens_form_1->adicLinhaForm('col-md-4', array('CPF', 'form-label'), array('text', 'cpf', 'form-control', 'inputCPF1'));
        $itens_form_1->adicLinhaForm('col-md-4', array('', 'form-label'), array('button', 'pesquisar', 'btn btn-primary'), 'Pesquisar');
        $itens_form_1->adicLinhaForm('col-md-4', array('Código', 'form-label'), array('text', 'codigo', 'form-control', 'inputCod1'));
        $itens_form_1->adicLinhaForm('col-md-8', array('Nome',   'form-label'), array('text', 'nome', 'form-control', 'inputNome4'));
        $itens_form_1->adicLinhaForm('col-md-2', array('Apelido',   'form-label'), array('text', 'apelido', 'form-control', 'inputApelido4'));
        $itens_form_1->adicLinhaForm('col-md-2', array('Nascimento',   'form-label'), array('text', 'nascimento', 'form-control', 'inputNascimento4'));
        $itens_form_1->adicLinhaForm('col-md-3', array('Número RG',   'form-label'), array('text', 'rg', 'form-control', 'inputRG4'));
        $itens_form_1->adicLinhaForm('col-md-2', array('Orgão Expedidor', 'form-label'), array('text', 'orgao_expedidor', 'form-control', 'inputOrgExp4'));
        $itens_form_1->adicLinhaForm('col-md-3', array('UF', 'form-label'), array('select', 'uf_expedidor', 'form-select', 'inputUFExpedidor'));
        $itens_form_1->adicLinhaForm('col-md-2', array('Data Expedição',   'form-label'), array('text', 'data_expedicao', 'form-control', 'inputDataExpedicao4'));
        $itens_form_1->adicLinhaForm('col-md-3', array('Nacionalidade', 'form-label'), array('select', 'nacionalidade', 'form-select', 'inputNacionalidade'));
        $itens_form_1->adicLinhaForm('col-md-3', array('Naturalidade',   'form-label'), array('text', 'naturalidade', 'form-control', 'inputNaturalidade4'));
        $itens_form_1->adicLinhaForm('col-md-2', array('Sexo', 'form-label'), array('select', 'sexo', 'form-select', 'inputSexo'));
        $itens_form_1->adicLinhaForm('col-md-1', array('DDD',   'form-label'), array('text', 'ddd', 'form-control', 'inputDDD4'));
        $itens_form_1->adicLinhaForm('col-md-3', array('Celular', 'form-label'), array('text', 'celular', 'form-control', 'inputCelular4'));
        $itens_form_1->adicLinhaForm('col-md-6', array('Nome do pai', 'form-label'), array('text', 'nome_pai', 'form-control', 'inputPai4'));
        $itens_form_1->adicLinhaForm('col-md-6', array('Nome da mãe', 'form-label'), array('text', 'nome_mae', 'form-control', 'inputMae4'));
        $itens_form_1->adicLinhaForm('col-md-4', array('Estado Civil', 'form-label'), array('select', 'estado_civil', 'form-select', 'inputEstadoCivil'));
        $itens_form_1->adicLinhaForm('col-md-6', array('Email',   'form-label'), array('email', 'email', 'form-control', 'inputEmail4'), 'nome@email.com');
        $itens_form_1->adicLinhaForm('col-12',   array(), array('button', 'enviar', 'btn btn-primary'), 'Enviar');
        #
        $itens_form_1->defOpcoesSeleciona('nacionalidade', array('Brasileiro', 'Argentino', 'Norte Americando', 'Chileno'));
        $itens_form_1->defOpcoesSeleciona('uf_expedidor', array('AL', 'BA', 'ES', 'MG', 'SP','RJ', 'SC','RS','TO','AM'));
        $itens_form_1->defOpcoesSeleciona('sexo', array('Masculino', 'Feminino'));
        $itens_form_1->defOpcoesSeleciona('estado_civil', array('Solteiro(a)', 'Casado(a)','Viúvo(a)', 'Divorciado(a)', 'Outro'));

        # Itens da aba 2
        $itens_form_2 = new ItensForm;
        $itens_form_2->adicLinhaForm('col-12',   array('Endereço', 'form-label'), array('text', 'endereco', 'form-control', 'inputEndereco', '1234 Main St'));
        $itens_form_2->adicLinhaForm('col-md-4', array('Estado', 'form-label'), array('select', 'uf', 'form-select', 'inputEstado'));
        $itens_form_2->adicLinhaForm('col-md-4', array('Cidade', 'form-label'), array('select', 'cidades', 'form-select', 'inputCidade'));

        $itens_form_2->defOpcoesSeleciona('uf', array('Alagoas', 'Bahia', 'Espírito Santo', 'Minas Gerais', 'São Paulo'));
        $itens_form_2->defOpcoesSeleciona('cidades', array('Prado', 'Alcobaça', 'Porto Seguro', 'Caravelas', 'Teixeira de Freitas'));

        //------------------------------------------------------------------------------------------------------------------------- 

        $itens_aba1 = new ItensFormulario($itens_form_1, NULL, array('id' => 'aba1', 'classe' => 'g-3'));
        $itens_aba2 = new ItensFormulario($itens_form_2, NULL, array('id' => 'aba2', 'classe' => 'g-3'));

        # O que estava bagunçando a layout das abas era a class 'row g-3'. Para arrumar, basta criar uma div 
        # com essa classe em cada aba, e deixar form sem classe.        
        $parametros = array('id' => 'meuConteudoAba', 'ativo' => 'basico');
        $abas = array('basico' => $itens_aba1, 'endereco' => $itens_aba2, 'emprego' => 'Três', 'referencias' => 'Quatro', 'obs' => 'xx');
        $aba = new AbasConteudo($abas, $parametros);

        /**
         * Aqui já sai o formulário completo. Mas precisamos apenas de parte dos campos do formulário de cada vez.
         */
        $form = new Forms($itens_form_1, NULL, array('id' => 'form_clientes_abas', 'metodo' => 'post'), $aba); # , $aba

        # Abas
        $nav_links = new NavItens;
        $nav_links->adicItem('links', array('basico'      => 'Básico'),     'nav-item active');
        $nav_links->adicItem('links', array('endereco'    => 'Endereço'),   'nav-item');
        $nav_links->adicItem('links', array('emprego'     => 'Emprego'),    'nav-item');
        $nav_links->adicItem('links', array('referencias' => 'Referências'),'nav-item');
        $nav_links->adicItem('links', array('observacoes' => 'Observações'),'nav-item');
      
        $nav_links->adicItem('param', 'sub_classe', 'nav-tabs');
        $nav_links->adicItem('param', 'id', 'minhaAbra');
        $nav_links->adicItem('param', 'role', 'tablist');
        $nav_links->adicItem('param', 'ativo', 0);
        $nav_links->adicItem('param', 'desabilitado', 2);
        $nav_links->adicItem('param', 'modo_link', 'button');

        $links_abas = $nav_links->obtItens();

        $la = $links_abas + array('ativo' => 0, 'desabilitado' => 4);

        #echo '<pre>';
            #print_r($la);
        #echo '</pre>';

        $parametros = array('titulo_cartao' => " ", 'id' => 'idAbaPessoa', 'role' => 'tablist');

        $cartao_form = new Cartao($parametros, 'div', [], $la);
        $cartao_form->adic($form); # form aba

        $cartao = new Cartao("Pessoas", 'h5', []);
        $cartao->adic($cartao_form);
      
        parent::adic($cartao);
        parent::adic($conteudo);
    }
}
