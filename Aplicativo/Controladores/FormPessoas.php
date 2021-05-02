<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 02/04/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\Recipiente\AbasConteudo;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Base\Recipiente\NavItens;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbalaForms;
use Estrutura\Bugigangas\Embrulho\EmbalaGrupoForm;
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\ItensForm;
use Estrutura\Bugigangas\Form\Texto;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Classe FormPessoas
 */
class FormPessoas extends Pagina
{
    private $form;
    private $conexao;
    private $registroAtivo;

    private $validacao_campos = array();

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

        # cria os campos do formulário (Básico)
        $cpf             = new Entrada('cpf');
        $pesquisar       = new Botao('pesquisar');
        $id              = new Entrada('id');
        $id->readonly = NULL;
        $nome            = new Entrada('nome');
        $apelido         = new Entrada('apelido');
        $nascimento      = new Entrada('data_nascimento');
        $identidade      = new Entrada('identidade');
        $orgao_expedidor = new Entrada('orgao_expedidor');
        $uf_expedidor    = new Combo('uf_expedidor');
        $data_expedicao  = new Entrada('data_expedicao');
        $nacionalidade   = new Entrada('nacionalidade');
        $naturalidade    = new Entrada('naturalidade');
        $sexo            = new Combo('sexo');
        $ddd             = new Entrada('ddd');
        $celular         = new Entrada('celular');
        $pai             = new Entrada('pai');
        $mae             = new Entrada('mae');
        $estado_civil    = new Combo('estado_civil');
        $email           = new Entrada('email');

        # cria os campos do formulário (Endereço)
        $cep              = new Entrada('cep');
        $endereco         = new Entrada('endereco');
        $numero           = new Entrada('numero');
        $complemento      = new Entrada('complemento');
        $bairro           = new Entrada('bairro');
        $uf               = new Combo('uf');
        $cidade           = new Combo('id_cidade');
        $ponto_referencia = new Entrada('ponto_referencia');
        $ddd_end          = new Entrada('ddd_end');
        $telefone         = new Entrada('telefone');
        $tempo_residencia = new Entrada('tempo_residencia');
        $tipo_imovel      = new Combo('tipo_imovel');

        # cria os campos do formulário (Emprego)
        $tipo_atividade        = new Combo('tipo_atividade');
        $tipo_organizacao      = new Combo('tipo_organizacao');
        $cargo                 = new Entrada('cargo');
        $empresa               = new Entrada('empresa');
        $salario               = new Entrada('salario');
        $outras_rendas         = new Entrada('outras_rendas');
        $numero_matricula      = new Entrada('numero_matricula');
        $documento_apresentado = new Entrada('documento_apresentado');
        $data_admissao         = new Entrada('data_admissao');

        # cria os campos do formulário (Referências)
        $nome_referencia1      = new Entrada('nome_referencia1');
        $ddd_referencia1       = new Entrada('ddd_referencia1');
        $telefone_referencia1  = new Entrada('telefone_referencia1');
        $nome_referencia2      = new Entrada('nome_referencia2');
        $ddd_referencia2       = new Entrada('ddd_referencia2');
        $telefone_referencia2  = new Entrada('telefone_referencia2');
        $nome_referencia3      = new Entrada('nome_referencia3');
        $ddd_referencia3       = new Entrada('ddd_referencia3');
        $telefone_referencia3  = new Entrada('telefone_referencia3');

        # cria os campos do formulário (Observações)
        $observacoes      = new Texto('observacoes');

        # carrega os fabricantes do banco de dados
        Transacao::abre($this->conexao);

        # define alguns atributos
        $ufs_expedidor = Estado::todos();
        $itens = array();
        foreach ($ufs_expedidor as $obj_estado) {
            $itens[$obj_estado->id] = $obj_estado->nome;
        }
        $uf_expedidor->adicItens($itens);

        $estado_civil->adicItens(array( '1' => 'Solteiro(a)',
                                        '2' => 'Casado(a)',
                                        '3' => 'Divorciado(a)',
                                        '4' => 'Outro',
                                        '5' => 'Solteiro(a)'));   
                                        
        $sexo->adicItens(array('1' => 'Masculino',
                               '2' => 'Feminino'));

        $ufs = Estado::todos();
        $itens = array();
        foreach ($ufs as $obj_uf) {
            $itens[$obj_uf->id] = $obj_uf->nome;
        }
        $uf->adicItens($itens);

        $cidades = Cidade::todos();
        $itens = array();
        foreach ($cidades as $obj_cidade) {
            $itens[$obj_cidade->id] = $obj_cidade->nome;
        }
        $cidade->adicItens($itens);

        $tipo_imovel->adicItens(array('1' => 'Próprio',
                                      '2' => 'Alugado',
                                      '3' => 'Família'));

        $tipo_atividade->adicItens(array('1' => 'Autônomo (Pedreiro, Carpinteiro, Pintor, Etc...)',
                                         '2' => 'Assalariado(a)',
                                         '3' => 'Aposentado(a)',
                                         '4' => 'Empresário(a)'));

        $tipo_organizacao->adicItens(array('1' => 'Vendedora de Confecções',
                                           '2' => 'Construção Civil',
                                           '3' => 'Comércio Varejista',
                                           '4' => 'Família'));    
        //----------------------------------------------------------------------------------------------------------------------------------
        $this->validacao_campos["nome"] = ['class' => 'form-control is-invalid', 'id' => 'inputNome1', 'required' => NULL];
        if ($_POST) {
            if (empty($_POST['nome'])) {    
                $campo_nome =  $this->validacao_campos["nome"]; # ?? ['class' => 'form-control', 'id' => 'inputNome1', 'required' => NULL];
            } else {
                $campo_nome =  ['class' => 'form-control is-valid', 'id' => 'inputNome1', 'required' => NULL];
            }
        } else {
            #$campo_nome = $this->validacao_campos["nome"] ?? ['class' => 'form-control is-valid', 'id' => 'inputNome1', 'required' => NULL];
            $campo_nome =  ['class' => 'form-control', 'id' => 'inputNome1', 'required' => NULL];
        }

        # Mensagens de feedback
        $validacao = array('feedback_ok' => 'Parece bom', 'feedback_erro' => 'Campo em branco ou inválido');     
               
        # Itens da aba 1 (Básico)
        $if_1 = new ItensForm('aba1');

        // , array('rotulo' => $prop_rotulo), array('entrada' => $prop_entrada)
        # O segundo parâmetro aceita array e string. Caso seja um array vazio, o rótulo não será criado.
        $if_1->adicGrupoForm('CPF',             $cpf,             'col-md-4', [], ['id' => 'inputCPF1', 'required' => NULL], $validacao);
        #$if_1->adicGrupoForm('<br/>',           $pesquisar,       'col-md-4', [], ['id' => 'inputCPF1'], $validacao);
        $if_1->adicGrupoForm('Código ',         $id,              'col-md-2', [], ['id' => 'inputCodigo4', 'readonly' => NULL], $validacao);
        $if_1->adicGrupoForm('Nome',            $nome,            'col-md-8', [], $campo_nome, $validacao);
        $if_1->adicGrupoForm('Apelido',         $apelido,         'col-md-2', [], ['id' => 'inputApelido4'], $validacao);
        $if_1->adicGrupoForm('Nascimento',      $nascimento,      'col-md-2', [], ['id' => 'inputNascimento4'], $validacao);
        $if_1->adicGrupoForm('Número RG',       $identidade,      'col-md-3', [], ['id' => 'inputRG4'], $validacao);
        $if_1->adicGrupoForm('Orgão Expedidor', $orgao_expedidor, 'col-md-2', [], ['id' => 'inputOrgExp4'], $validacao);
        $if_1->adicGrupoForm('UF',              $uf_expedidor,    'col-md-3', [], ['class' => 'form-select', 'id' => 'inputUFExpedidor'], $validacao);
        $if_1->adicGrupoForm('Data Expedição',  $data_expedicao,  'col-md-2', [], ['id' => 'inputDataExpedicao4'], $validacao);
        $if_1->adicGrupoForm('Nacionalidade',   $nacionalidade,   'col-md-3', [], ['id' => 'inputNacionalidade'], $validacao);
        $if_1->adicGrupoForm('Naturalidade',    $naturalidade,    'col-md-3', [], ['id' => 'inputNaturalidade4'], $validacao);
        $if_1->adicGrupoForm('Sexo',            $sexo,            'col-md-2', [], ['class' => 'form-select', 'id' => 'inputSexo'], $validacao);
        $if_1->adicGrupoForm('DDD',             $ddd,             'col-md-1', [], ['id' => 'inputDDD4'], $validacao);
        $if_1->adicGrupoForm('Celular',         $celular,         'col-md-3', [], ['id' => 'inputCelular4'], $validacao);
        $if_1->adicGrupoForm('Nome do pai',     $pai,             'col-md-6', [], ['id' => 'inputPai4'], $validacao);
        $if_1->adicGrupoForm('Nome da mãe',     $mae,             'col-md-6', [], ['id' => 'inputMae4'], $validacao);
        $if_1->adicGrupoForm('Estado Civil',    $estado_civil,    'col-md-4', [], ['class' => 'form-select', 'id' => 'inputEstadoCivil'], $validacao);
        $if_1->adicGrupoForm('Email',           $email,           'col-md-6', [], ['id' => 'inputEmail4'], $validacao);

        # Itens da aba 2 (Endereço)
        $if_2 = new ItensForm('aba2');
        $if_2->adicGrupoForm('CEP',         $cep,         'col-md-3', [], ['id' => 'inputCEP4'], $validacao);
        $if_2->adicGrupoForm('Endereço',    $endereco,    'col-md-6', [], ['id' => 'inputEndereco'], $validacao);
        $if_2->adicGrupoForm('Número',      $numero,      'col-md-1', [], ['id' => 'inputNumero4'], $validacao);
        $if_2->adicGrupoForm('Complemento', $complemento, 'col-md-3', [], ['id' => 'inputComplemento4'], $validacao);
        $if_2->adicGrupoForm('Bairro',      $bairro,      'col-md-3', [], ['id' => 'inputBairro4'], $validacao);
        $if_2->adicGrupoForm('Estado',      $uf,          'col-md-4', [], ['class' => 'form-select', 'id' => 'inputEstado'], $validacao);
        $if_2->adicGrupoForm('Cidade',      $cidade,      'col-md-4', [], ['class' => 'form-select', 'id' => 'inputCidade'], $validacao);

        # Este campo precisa ser implementado corretamente (textarea) rows
        $if_2->adicGrupoForm('Ponto de referência', $ponto_referencia, 'col-md-8', [], ['id' => 'inputPontoReferencia'], $validacao);
        $if_2->adicGrupoForm('DDD',                 $ddd_end,          'col-md-1', [], ['id' => 'inputDDD4'], $validacao);
        $if_2->adicGrupoForm('Telefone',            $telefone,         'col-md-2', [], ['id' => 'inputTelefone4'], $validacao);
        $if_2->adicGrupoForm('Tempo de residência', $tempo_residencia, 'col-md-3', [], ['id' => 'inputTempoResidencia4'], $validacao);
        $if_2->adicGrupoForm('Tipo de imóvel',      $tipo_imovel,      'col-md-4', [], ['class' => 'form-select', 'id' => 'inputTipoImovel'], $validacao);

        # Itens da aba 3 (Ocupação)
        $if_3 = new ItensForm('aba3');
        $if_3->adicGrupoForm('Tipo Atividade',        $tipo_atividade,        'col-md-6', [], ['class' => 'form-select', 'id' => 'inputTipoAtividade'], $validacao);
        $if_3->adicGrupoForm('Tipo de Organização',   $tipo_organizacao,      'col-md-6', [], ['class' => 'form-select', 'id' => 'inputTipoOrganizacao'], $validacao);
        $if_3->adicGrupoForm('Cargo',                 $cargo,                 'col-md-6', [], ['id' => 'inputCargo4'], $validacao);
        $if_3->adicGrupoForm('Empresa',               $empresa,               'col-md-6', [], ['id' => 'inputEmpresa4'], $validacao);
        $if_3->adicGrupoForm('Salário',               $salario,               'col-md-3', [], ['id' => 'inputSalario4'], $validacao);
        $if_3->adicGrupoForm('Outras Rendas',         $outras_rendas,         'col-md-3', [], ['id' => 'inputDDD4'], $validacao);
        $if_3->adicGrupoForm('Número da Matrícula',   $numero_matricula,      'col-md-3', [], ['id' => 'inputNumeroMatricula4'], $validacao);
        $if_3->adicGrupoForm('Documento Apresentado', $documento_apresentado, 'col-md-3', [], ['id' => 'inputDocumentoApresentado'], $validacao);
        $if_3->adicGrupoForm('Data de Admissão',      $data_admissao,         'col-md-3', [], ['id' => 'inputDataAdmissao'], $validacao);

        # Itens da aba 4 (Referências)
        $if_4 = new ItensForm('aba4');
        $if_4->adicGrupoForm('Nome',     $nome_referencia1,     'col-md-5', [], ['id' => 'inputNomeReferencia1'], $validacao);
        $if_4->adicGrupoForm('DDD',      $ddd_referencia1,      'col-md-1', [], ['id' => 'inputDDDReferencia1'], $validacao);
        $if_4->adicGrupoForm('Telefone', $telefone_referencia1, 'col-md-3', [], ['id' => 'inputTelefoneReferencia1'], $validacao);
        $if_4->adicGrupoForm('Nome',     $nome_referencia2,     'col-md-5', [], ['id' => 'inputNomeReferencia2'], $validacao);
        $if_4->adicGrupoForm('DDD',      $ddd_referencia2,      'col-md-1', [], ['id' => 'inputDDDReferencia2'], $validacao);
        $if_4->adicGrupoForm('Telefone', $telefone_referencia2, 'col-md-3', [], ['id' => 'inputTelefoneReferencia2'], $validacao);
        $if_4->adicGrupoForm('Nome',     $nome_referencia3,     'col-md-5', [], ['id' => 'inputNomeReferencia3'], $validacao);
        $if_4->adicGrupoForm('DDD',      $ddd_referencia3,      'col-md-1', [], ['id' => 'inputDDDReferencia3'], $validacao);
        $if_4->adicGrupoForm('Telefone', $telefone_referencia3, 'col-md-3', [], ['id' => 'inputTelefoneReferencia3'], $validacao);
        
        # Itens da aba 5 (Observações)
        $if_5 = new ItensForm('aba5');
        $if_5->adicGrupoForm('Anotações', $observacoes, 'col-md-12', [], ['id' => 'inputObservacoes'], $validacao);
        
        //------------------------------------------------------------------------------------------------------------------------- 
        /** O formulário com abas funciona assim. Os grupos de itens de formulário são inseridos no objeto abas conteúdo. 
         * E em seguida o objeto. AbasConteudo é inserido em um formulário.
         */
        $itens_aba1 = new EmbalaGrupoForm($if_1, ['id' => 'aba1', 'classe' => 'g-3']);
        $itens_aba2 = new EmbalaGrupoForm($if_2, ['id' => 'aba1', 'classe' => 'g-3']);
        $itens_aba3 = new EmbalaGrupoForm($if_3, ['id' => 'aba1', 'classe' => 'g-3']);
        $itens_aba4 = new EmbalaGrupoForm($if_4, ['id' => 'aba1', 'classe' => 'g-3']);
        $itens_aba5 = new EmbalaGrupoForm($if_5, ['id' => 'aba5', 'classe' => 'g-3']);

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
        $abas_conteudo = array('basico' => $cartao_basico, 'endereco' => $cartao_endereco, 'emprego' => $cartao_emprego, 
                               'referencias' => $cartao_refs, 'obs' => $cartao_observacoes);
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
         * if_1
         */
        $parametros_cartao = array('titulo_cartao' => " ", 'id' => 'idAbaPessoa', 'role' => 'tablist');
        $parametros_form = array('id' => 'form_clientes_abas', 'classe_form' => 'exige-validacao', 'naovalida' => 1,
                                 'metodo' => 'post', 'links_abas' => $links_abas, 'params_cartao' => $parametros_cartao);

        $this->form_abas = new EmbalaForms(new Form('form_cliente'), NULL, NULL, $parametros_form, $abas_prontas); 
        $this->form_abas->defTitulo("Pessoas");

        $this->form_abas->adicItensGrupo($if_1);
        $this->form_abas->adicItensGrupo($if_2);
        $this->form_abas->adicItensGrupo($if_3);
        $this->form_abas->adicItensGrupo($if_4);
        $this->form_abas->adicItensGrupo($if_5);

        $this->form_abas->adicAcao('Salvar', new Acao(array($this, 'aoSalvar')));

        parent::adic($this->form_abas);
        parent::adic($conteudo); # Por enquanto, trás apenas o JS
    }

    //-------------------------------------------------------------------------------------------------------------------
    /**
     * Método aoSalvar
     */
    public function aoSalvar()
    {
        try {
            # inicia transação com o banco de dados
            Transacao::abre($this->conexao);

            //Transacao::defHistorico("/tmp/log");
            $dados = $this->form_abas->obtDados();

            // Validação
            if (empty($dados->nome)) {
                throw new Exception('O campo nome não foi informado!');
            }

            if (empty($dados->cpf)) {
                #new Mensagem('erro', 'O campo CPF não foi informado!');
                throw new Exception('O campo CPF não foi informado!');
            }

            // Monta mensagem
            $mensagem = "Nome: {$dados->nome} <br/>" . PHP_EOL;
            $mensagem .= "CPF: {$dados->cpf} <br/>" . PHP_EOL;
            $mensagem .= "Email: {$dados->email} <br/>" . PHP_EOL;

            new Mensagem('info', $mensagem);
            
            #$idsGrupos = $dados->ids_grupos;

            $this->form_abas->defDados($dados);

            $grupo_pessoa = new GrupoPessoa;
            $pessoa = new Pessoa;

            $pessoa->apagGrupos();
            /*if ($dados->ids_grupos) {
                foreach ($dados->ids_grupos as $id_grupo) {
                    $pessoa->adicGrupo(new Grupo($id_grupo));
                }
            }*/

            #unset($dados->ids_grupos);

            $pessoa->doArray((array) $dados);
            $pessoa->grava();

            Transacao::fecha();
            new Mensagem('info', 'Dados armazenados com sucesso');
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }
    }

    /**
     * Método aoEditar
     */
    public function aoEditar($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id'];

                # inicia transação com o banco de dados
                Transacao::abre($this->conexao);

                $pessoa = Pessoa::localiza($id);
                if ($pessoa) {
                    $pessoa->ids_grupos = $pessoa->obtIdsGrupos();
                    $this->form_abas->defDados($pessoa);
                }
                Transacao::fecha();
            }

        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }
    }
}
