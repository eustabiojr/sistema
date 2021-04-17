<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 02/04/2021
 ************************************************************************************/

use Estrutura\Bugigangas\Base\Recipiente\AbasConteudo;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Base\Recipiente\NavItens;
use Estrutura\Bugigangas\Embrulho\EmbalaForms;
use Estrutura\Bugigangas\Embrulho\EmbalaGrupoForm;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\ItensForm;
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

        # Itens da aba 1 (Básico)
        $itens_form_1 = new ItensForm;
        # O segundo parâmetro aceita array e string. Caso seja um array vazio, o rótulo não será criado.
        $itens_form_1->adicLinhaForm('col-md-4', array('CPF', 'form-label'), array('text', 'cpf', 'form-control', 'inputCPF1'));
        $itens_form_1->adicLinhaForm('col-md-2', array('<br/>', 'form-label'), array('button', 'pesquisar', 'form-control btn btn-primary'), 'Pesquisar');
        $itens_form_1->adicLinhaForm('col-md-4', array('Código', 'form-label'), array('text', 'codigo', 'form-control', 'inputCod1', 
                                     [array('readonly' => NULL/*, 'disabled' => NULL*/)]));
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
        $itens_form_1->adicLinhaForm('col-md-6', array('Email', 'form-label'), array('email', 'email', 'form-control', 'inputEmail4'), 'nome@email.com');
        $itens_form_1->adicLinhaForm('col-12',   [], array('button', 'enviar', 'btn btn-primary'), 'Enviar');
        #
        $itens_form_1->defOpcoesSeleciona('nacionalidade', array('Brasileiro', 'Argentino', 'Norte Americando', 'Chileno'));
        $itens_form_1->defOpcoesSeleciona('uf_expedidor', array('AL', 'BA', 'ES', 'MG', 'SP','RJ', 'SC','RS','TO','AM'));
        $itens_form_1->defOpcoesSeleciona('sexo', array('Masculino', 'Feminino'));
        $itens_form_1->defOpcoesSeleciona('estado_civil', array('Solteiro(a)', 'Casado(a)','Viúvo(a)', 'Divorciado(a)', 'Outro'));

        # Itens da aba 2 (Endereço)
        $itens_form_2 = new ItensForm;
        $itens_form_2->adicLinhaForm('col-md-3', array('CEP',   'form-label'), array('text', 'cep', 'form-control', 'inputCEP4'));
        $itens_form_2->adicLinhaForm('col-md-6', array('Endereço', 'form-label'), array('text', 'endereco', 'form-control', 'inputEndereco', '1234 Main St'));
        $itens_form_2->adicLinhaForm('col-md-1', array('Número',   'form-label'), array('text', 'numero', 'form-control', 'inputNumero4'));
        $itens_form_2->adicLinhaForm('col-md-3', array('Complemento', 'form-label'), array('text', 'complemento', 'form-control', 'inputComplemento4'));
        $itens_form_2->adicLinhaForm('col-md-3', array('Bairro',   'form-label'), array('text', 'bairro', 'form-control', 'inputBairro4'));
        $itens_form_2->adicLinhaForm('col-md-4', array('Estado', 'form-label'), array('select', 'uf', 'form-select', 'inputEstado'));
        $itens_form_2->adicLinhaForm('col-md-4', array('Cidade', 'form-label'), array('select', 'cidades', 'form-select', 'inputCidade'));
        # Este campo precisa ser implementado corretamente (textarea)
        $itens_form_2->adicLinhaForm('col-md-8', array('Ponto de referência', 'form-label'), array('textarea', 'ponto_referencia', 'form-control', 'inputPontoReferencia'));
        #
        $itens_form_2->adicLinhaForm('col-md-1', array('DDD',   'form-label'), array('text', 'ddd', 'form-control', 'inputDDD4'));
        $itens_form_2->adicLinhaForm('col-md-2', array('Telefone',   'form-label'), array('text', 'telefone', 'form-control', 'inputTelefone4'));
        $itens_form_2->adicLinhaForm('col-md-3', array('Tempo de residência',   'form-label'), array('text', 'tempo_residencia', 'form-control', 'inputTempoResidencia4'));
        $itens_form_2->adicLinhaForm('col-md-4', array('Tipo de imóvel', 'form-label'), array('select', 'tipo_imovel', 'form-select', 'inputTipoImovel'));

        $itens_form_2->defOpcoesSeleciona('uf', array('Alagoas', 'Bahia', 'Espírito Santo', 'Minas Gerais', 'São Paulo'));
        $itens_form_2->defOpcoesSeleciona('cidades', array('Prado', 'Alcobaça', 'Porto Seguro', 'Caravelas', 'Teixeira de Freitas'));
        $itens_form_2->defOpcoesSeleciona('tipo_imovel', array('Próprio', 'Alugado', 'Família'));

        # Itens da aba 3 (Emprego)
        $itens_form_3 = new ItensForm;
        $itens_form_3->adicLinhaForm('col-md-6', array('Tipo Atividade', 'form-label'), array('select', 'tipo_atividade', 'form-select', 'inputTipoAtividade'));
        $itens_form_3->adicLinhaForm('col-md-6', array('Tipo de Organização', 'form-label'), array('select', 'tipo_organizacao', 'form-select', 'inputTipoOrganizacao'));
        $itens_form_3->adicLinhaForm('col-md-6', array('Cargo', 'form-label'), array('text', 'cargo', 'form-control', 'inputCargo4'));
        $itens_form_3->adicLinhaForm('col-md-6', array('Empresa', 'form-label'), array('text', 'empresa', 'form-control', 'inputEmpresa4'));
        $itens_form_3->adicLinhaForm('col-md-3', array('Salário', 'form-label'), array('text', 'salario', 'form-control', 'inputSalario4'));
        $itens_form_3->adicLinhaForm('col-md-3', array('Outras Rendas', 'form-label'), array('text', 'outras_rendas', 'form-control', 'inputOutrasRendas'));
        $itens_form_3->adicLinhaForm('col-md-3', array('Número da Matrícula', 'form-label'), array('text', 'numero_matricula', 'form-control', 'inputNumeroMatricula4'));
        $itens_form_3->adicLinhaForm('col-md-3', array('Documento Apresentado', 'form-label'), array('text', 'documento_apresentado', 'form-control', 'inputDocumentoApresentado'));
        $itens_form_3->adicLinhaForm('col-md-3', array('Data de Admissão', 'form-label'), array('text', 'data_admissao', 'form-control', 'inputDataAdmissao'));

        $itens_form_3->defOpcoesSeleciona('tipo_atividade', array('Autônomo (Pedreiro, Carpinteiro, Pintor, Etc...)', 'Assalariado', 'Aposentado'));
        $itens_form_3->defOpcoesSeleciona('tipo_organizacao', array('Vendedora de Confecções', 'Construção Civil'));

        # Itens da aba 4 (Referências)
        $itens_form_4 = new ItensForm;
        $itens_form_4->adicLinhaForm('col-md-5', array('Nome',   'form-label'), array('text', 'nome_referencia1', 'form-control', 'inputNomeReferencia1'));
        $itens_form_4->adicLinhaForm('col-md-1', array('DDD',   'form-label'), array('text', 'ddd_referencia1', 'form-control', 'inputDDDReferencia1'));
        $itens_form_4->adicLinhaForm('col-md-3', array('Telefone',   'form-label'), array('text', 'telefone_referencia1', 'form-control', 'inputTelefoneReferencia1'));
        $itens_form_4->adicLinhaForm('col-md-5', array('Nome',   'form-label'), array('text', 'nome_referencia2', 'form-control', 'inputNomeReferencia2'));
        $itens_form_4->adicLinhaForm('col-md-1', array('DDD',   'form-label'), array('text', 'ddd_referencia2', 'form-control', 'inputDDDReferencia2'));
        $itens_form_4->adicLinhaForm('col-md-3', array('Telefone',   'form-label'), array('text', 'telefone_referencia2', 'form-control', 'inputTelefoneReferencia2'));
        $itens_form_4->adicLinhaForm('col-md-5', array('Nome',   'form-label'), array('text', 'nome_referencia3', 'form-control', 'inputNomeReferencia3'));
        $itens_form_4->adicLinhaForm('col-md-1', array('DDD',   'form-label'), array('text', 'ddd_referencia3', 'form-control', 'inputDDDReferencia3'));
        $itens_form_4->adicLinhaForm('col-md-3', array('Telefone',   'form-label'), array('text', 'telefone_referencia3', 'form-control', 'inputTelefoneReferencia3'));
        
        # Itens da aba 5 (Observações)
        $itens_form_5 = new ItensForm;
        $itens_form_5->adicLinhaForm('col-md-12', array('Anotações', 'form-label'), array('textarea', 'observacoes', 'form-control', 'inputObservacoes'));
        
        //------------------------------------------------------------------------------------------------------------------------- 
        /** O formulário com abas funciona assim. Os grupos de itens de formulário são inseridos no objeto abas conteúdo. 
         * E em seguida o objeto. AbasConteudo é inserido em um formulário.
         */
        $itens_aba1 = new EmbalaGrupoForm($itens_form_1, array('id' => 'aba1', 'classe' => 'g-3'));
        $itens_aba2 = new EmbalaGrupoForm($itens_form_2, array('id' => 'aba2', 'classe' => 'g-3'));
        $itens_aba3 = new EmbalaGrupoForm($itens_form_3, array('id' => 'aba3', 'classe' => 'g-3'));
        $itens_aba4 = new EmbalaGrupoForm($itens_form_4, array('id' => 'aba4', 'classe' => 'g-3'));
        $itens_aba5 = new EmbalaGrupoForm($itens_form_5, array('id' => 'aba5', 'classe' => 'g-3'));

        $params_identificacao = array('titulo_cartao' => "Dados Pessoais", 'id' => 'idAbaIdent', 'role' => 'tablist');
        $cartao_basico = new Cartao($params_identificacao, 'div', []);
        $cartao_basico->adic($itens_aba1);

        $params_endereco = array('titulo_cartao' => "Endereço Atual", 'id' => 'idAbaEndereco', 'role' => 'tablist');
        $cartao_endereco = new Cartao($params_endereco, 'div', []);
        $cartao_endereco->adic($itens_aba2);

        $params_emprego = array('titulo_cartao' => "Dados da Ocupação", 'id' => 'idAbaEmprego', 'role' => 'tablist');
        $cartao_emprego = new Cartao($params_emprego, 'div', []);
        $cartao_emprego->adic($itens_aba3);

        $params_refs = array('titulo_cartao' => "Referências Pessoais", 'id' => 'idAbaRefs', 'role' => 'tablist');
        $cartao_refs = new Cartao($params_refs, 'div', []);
        $cartao_refs->adic($itens_aba4);

        $params_observacoes = array('titulo_cartao' => "Observação", 'id' => 'idAbaObs', 'role' => 'tablist');
        $cartao_observacoes = new Cartao($params_observacoes, 'div', []);
        $cartao_observacoes->adic($itens_aba5);

        # O que estava bagunçando a layout das abas era a class 'row g-3'. Para arrumar, basta criar uma div 
        # com essa classe em cada aba, e deixar form sem classe.        
        $parametros_abas = array('id' => 'meuConteudoAba', 'ativo' => 'basico');
        $abas_conteudo = array('basico' => $cartao_basico, 'endereco' => $cartao_endereco, 'emprego' => $cartao_emprego, 'referencias' => $cartao_refs, 'obs' => $cartao_observacoes);
        $abas_prontas = new AbasConteudo($abas_conteudo, $parametros_abas);

        # Abas
        $nav_links = new NavItens;
        $nav_links->adicItem('links', array('basico'      => 'Básico'),     'nav-item active');
        $nav_links->adicItem('links', array('endereco'    => 'Endereço'),   'nav-item');
        $nav_links->adicItem('links', array('emprego'     => 'Emprego'),    'nav-item');
        $nav_links->adicItem('links', array('referencias' => 'Referências'),'nav-item');
        $nav_links->adicItem('links', array('obs'         => 'Observações'),'nav-item');
      
        $nav_links->adicItem('param', 'sub_classe', 'nav-tabs');
        $nav_links->adicItem('param', 'id', 'minhaAbra');
        $nav_links->adicItem('param', 'role', 'tablist');
        $nav_links->adicItem('param', 'ativo', 0);
        $nav_links->adicItem('param', 'desabilitado', NULL);
        $nav_links->adicItem('param', 'modo_link', 'button');

        $links_abas = $nav_links->obtItens();

        /**
         * Forms
         * 
         * Os itens são criados em uma classe externa (EmbalaGrupoForm)
         * itens_form_1
         */
        $parametros_form = array('id' => 'form_clientes_abas', 'metodo' => 'post', 'links_abas' => $links_abas);
        $form_abas = new EmbalaForms(new Form('form_cliente'), NULL, NULL, $parametros_form, $abas_prontas);

        #echo '<pre>';
            #print_r($la);
        #echo '</pre>';
      
        parent::adic($form_abas);
        parent::adic($conteudo); # Por enquanto, trás apenas o JS
    }
}
