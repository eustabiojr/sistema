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
use Estrutura\Bugigangas\Embrulho\EmbalaGrupoForm2;
use Estrutura\Bugigangas\Form\Botao;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\ItensForm2;
use Estrutura\Bugigangas\Form\Texto;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Classe FormPessoas
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

        # cria os campos do formulário (Básico)
        $cpf             = new Entrada('cpf');
        $pesquisar       = new Botao('pesquisar');
        $id              = new Entrada('id');
        $nome            = new Entrada('nome');
        $apelido         = new Entrada('apelido');
        $nascimento      = new Entrada('data_nascimento');
        $identidade      = new Entrada('identidade');
        $orgao_expedidor = new Entrada('orgao_expedidor');
        $uf_expedidor    = new Entrada('uf_expedidor');
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
        $cidade           = new Combo('cidade');
        $ponto_referencia = new Entrada('ponto_referencia');
        $ddd_end          = new Entrada('ddd_end');
        $telefone         = new Entrada('telefone');
        $tempo_residencia = new Entrada('tempo_residencia');
        $tipo_imovel      = new Combo('tipo_imovel');

        # cria os campos do formulário (Emprego)
        $tipo_atividade        = new Entrada('tipo_atividade');
        $tipo_organizacao      = new Entrada('tipo_organizacao');
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
       
        # Itens da aba 1 (Básico)
        $itens_form_1 = new ItensForm2('aba1');
        $comuns_grupo = ['classe_rotulo' => 'form-label', 'classe_entrada' => 'form-control'];
        $comuns_grupo2 = ['classe_rotulo' => 'form-label', 'classe_entrada' => 'form-control btn btn-primary'];

        # O segundo parâmetro aceita array e string. Caso seja um array vazio, o rótulo não será criado.
        $itens_form_1->adicGrupoForm('CPF',             $nome,            array_merge(['classe_grupo' => 'col-md-4', 'id' => 'inputCPF1'], $comuns_grupo));
        #$itens_form_1->adicGrupoForm('<br/>',           $pesquisar,       array_merge(['classe_grupo' => 'col-md-2'], $comuns_grupo2));
        $itens_form_1->adicGrupoForm('Código',          $id,              array_merge(['classe_grupo' => 'col-md-4', 'id' => 'inputCod1'], $comuns_grupo)); #  [array('readonly' => NULL/*, 'disabled' => NULL*/)]
        $itens_form_1->adicGrupoForm('Nome',            $nome,            array_merge(['classe_grupo' => 'col-md-8', 'id' => 'inputNome1'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Apelido',         $apelido,         array_merge(['classe_grupo' => 'col-md-2', 'id' => 'inputApelido4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Nascimento',      $nascimento,      array_merge(['classe_grupo' => 'col-md-2', 'id' => 'inputNascimento4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Número RG',       $identidade,      array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputRG4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Orgão Expedidor', $orgao_expedidor, array_merge(['classe_grupo' => 'col-md-2', 'id' => 'inputOrgExp4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('UF',              $uf_expedidor,    array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputUFExpedidor'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Data Expedição',  $data_expedicao,  array_merge(['classe_grupo' => 'col-md-2', 'id' => 'inputDataExpedicao4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Nacionalidade',   $nacionalidade,   array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputNacionalidade'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Naturalidade',    $naturalidade,    array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputNaturalidade4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Sexo',            $sexo,            array_merge(['classe_grupo' => 'col-md-2', 'id' => 'inputSexo'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('DDD',             $ddd,             array_merge(['classe_grupo' => 'col-md-1', 'id' => 'inputDDD4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Celular',         $celular,         array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputCelular4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Nome do pai',     $pai,             array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputPai4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Nome da mãe',     $mae,             array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputMae4'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Estado Civil',    $estado_civil,    array_merge(['classe_grupo' => 'col-md-4', 'id' => 'inputEstadoCivil'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Email',           $email,           array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputEmail4'], $comuns_grupo));

        //$itens_form_1->obtGrupoCampo();

        # Itens da aba 2 (Endereço)
        $itens_form_2 = new ItensForm2('aba2');
        $itens_form_2->adicGrupoForm('CEP',                 $cep,              array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputCEP4'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Endereço',            $endereco,         array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputEndereco'], $comuns_grupo)); # '1234 Main St'));
        $itens_form_2->adicGrupoForm('Número',              $numero,           array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputNumero4'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Complemento',         $complemento,      array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputComplemento4'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Bairro',              $bairro,           array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputBairro4'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Estado',              $uf,               array_merge(['classe_grupo' => 'col-md-4', 'id' => 'inputEstado'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Cidade',              $cidade,           array_merge(['classe_grupo' => 'col-md-4', 'id' => 'inputCidade'], $comuns_grupo));
        # Este campo precisa ser implementado corretamente (textarea) rows
        $itens_form_2->adicGrupoForm('Ponto de referência', $ponto_referencia, array_merge(['classe_grupo' => 'col-md-8', 'id' => 'inputPontoReferencia'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('DDD',                 $ddd_end,          array_merge(['classe_grupo' => 'col-md-1', 'id' => 'inputDDD4'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Telefone',            $telefone,         array_merge(['classe_grupo' => 'col-md-2', 'id' => 'inputTelefone4'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Tempo de residência', $tempo_residencia, array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputTempoResidencia4'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Tipo de imóvel',      $tipo_imovel,      array_merge(['classe_grupo' => 'col-md-4', 'id' => 'inputTipoImovel'], $comuns_grupo));

        # Itens da aba 3 (Ocupação)
        $itens_form_3 = new ItensForm2('aba3');
        $itens_form_3->adicGrupoForm('Tipo Atividade',        $tipo_atividade,        array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputTipoAtividade'], $comuns_grupo));
        $itens_form_3->adicGrupoForm('Tipo de Organização',   $tipo_organizacao,      array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputTipoOrganizacao'], $comuns_grupo));
        $itens_form_3->adicGrupoForm('Cargo',                 $cargo,                 array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputCargo4'], $comuns_grupo));
        $itens_form_3->adicGrupoForm('Empresa',               $empresa,               array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputEmpresa4'], $comuns_grupo));
        $itens_form_3->adicGrupoForm('Salário',               $salario,               array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputSalario4'], $comuns_grupo));
        $itens_form_3->adicGrupoForm('Outras Rendas',         $outras_rendas,         array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputOutrasRendas'], $comuns_grupo));
        $itens_form_3->adicGrupoForm('Número da Matrícula',   $numero_matricula,      array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputNumeroMatricula4'], $comuns_grupo));
        $itens_form_3->adicGrupoForm('Documento Apresentado', $documento_apresentado, array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputDocumentoApresentado'], $comuns_grupo));
        $itens_form_3->adicGrupoForm('Data de Admissão',      $data_admissao,         array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputDataAdmissao'], $comuns_grupo));

        $itens_form_3->defOpcoesSeleciona('tipo_atividade', array('Autônomo (Pedreiro, Carpinteiro, Pintor, Etc...)', 'Assalariado', 'Aposentado'));
        $itens_form_3->defOpcoesSeleciona('tipo_organizacao', array('Vendedora de Confecções', 'Construção Civil'));

        # Itens da aba 4 (Referências)
        $itens_form_4 = new ItensForm2('aba4');
        $itens_form_4->adicGrupoForm('Nome',     $nome_referencia1,     array_merge(['classe_grupo' => 'col-md-5', 'id' => 'inputNomeReferencia1'], $comuns_grupo));
        $itens_form_4->adicGrupoForm('DDD',      $ddd_referencia1,      array_merge(['classe_grupo' => 'col-md-1', 'id' => 'inputDDDReferencia1'], $comuns_grupo));
        $itens_form_4->adicGrupoForm('Telefone', $telefone_referencia1, array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputTelefoneReferencia1'], $comuns_grupo));
        $itens_form_4->adicGrupoForm('Nome',     $nome_referencia2,     array_merge(['classe_grupo' => 'col-md-5', 'id' => 'inputNomeReferencia2'], $comuns_grupo));
        $itens_form_4->adicGrupoForm('DDD',      $ddd_referencia2,      array_merge(['classe_grupo' => 'col-md-1', 'id' => 'inputDDDReferencia2'], $comuns_grupo));
        $itens_form_4->adicGrupoForm('Telefone', $telefone_referencia2, array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputTelefoneReferencia2'], $comuns_grupo));
        $itens_form_4->adicGrupoForm('Nome',     $nome_referencia3,     array_merge(['classe_grupo' => 'col-md-5', 'id' => 'inputNomeReferencia3'], $comuns_grupo));
        $itens_form_4->adicGrupoForm('DDD',      $ddd_referencia3,      array_merge(['classe_grupo' => 'col-md-1', 'id' => 'inputDDDReferencia3'], $comuns_grupo));
        $itens_form_4->adicGrupoForm('Telefone', $telefone_referencia3, array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputTelefoneReferencia3'], $comuns_grupo));
        
        # Itens da aba 5 (Observações)
        $itens_form_5 = new ItensForm2('aba5');
        $itens_form_5->adicGrupoForm('Anotações', $observacoes, array_merge(['classe_grupo' => 'col-md-12', 'id' => 'inputObservacoes'], $comuns_grupo));
        
        //------------------------------------------------------------------------------------------------------------------------- 
        /** O formulário com abas funciona assim. Os grupos de itens de formulário são inseridos no objeto abas conteúdo. 
         * E em seguida o objeto. AbasConteudo é inserido em um formulário.
         */
        $itens_aba1 = new EmbalaGrupoForm2($itens_form_1, array('id' => 'aba1', 'classe' => 'g-3'));
        $itens_aba2 = new EmbalaGrupoForm2($itens_form_2, array('id' => 'aba1', 'classe' => 'g-3'));
        $itens_aba3 = new EmbalaGrupoForm2($itens_form_3, array('id' => 'aba1', 'classe' => 'g-3'));
        $itens_aba4 = new EmbalaGrupoForm2($itens_form_4, array('id' => 'aba1', 'classe' => 'g-3'));
        $itens_aba5 = new EmbalaGrupoForm2($itens_form_5, array('id' => 'aba5', 'classe' => 'g-3'));
        #parent::adic($itens_aba1);

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
         * itens_form_1
         */
        $parametros_cartao = array('titulo_cartao' => " ", 'id' => 'idAbaPessoa', 'role' => 'tablist');
        $parametros_form = array('id' => 'form_clientes_abas', 'metodo' => 'post', 'links_abas' => $links_abas,
                                 'params_cartao' => $parametros_cartao);
        $this->form_abas = new EmbalaForms(new Form('form_cliente'), NULL, NULL, $parametros_form, $abas_prontas);
        $this->form_abas->defTitulo("Pessoas");

        $this->form_abas->adicItensGrupo($itens_form_1);
        $this->form_abas->adicItensGrupo($itens_form_2);

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
