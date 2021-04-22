<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 02/04/2021
 ************************************************************************************/

use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\Bugigangas\Base\Recipiente\AbasConteudo;
use Estrutura\Bugigangas\Base\Recipiente\Cartao;
use Estrutura\Bugigangas\Base\Recipiente\NavItens;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embrulho\EmbalaForms;
use Estrutura\Bugigangas\Embrulho\EmbalaGrupoForm2;
use Estrutura\Bugigangas\Embrulho\GrupoEntradaForm;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\ItensForm2;
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

        # cria os campos do formulário
        $cpf      = new Entrada('cpf');
        $nome     = new Entrada('nome');
        #$nome  = new GrupoEntradaForm(new Entrada('nome'));
        $cep      = new Entrada('cep');
        $endereco = new Entrada('endereco');
        
        #$div = new Elemento('div');
        #$div->class = 'row g-3';

        #$itens_grupo = array('1','2');

        # Itens da aba 1 (Básico)
        $itens_form_1 = new ItensForm2('aba1');
        $comuns_grupo = ['classe_rotulo' => 'form-label', 'classe_entrada' => 'form-control'];
        # O segundo parâmetro aceita array e string. Caso seja um array vazio, o rótulo não será criado.
        $itens_form_1->adicGrupoForm('CPF',  $cpf, array_merge(['classe_grupo' => 'col-md-4', 'id' => 'inputCPF1'], $comuns_grupo));
        $itens_form_1->adicGrupoForm('Nome', $nome, array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputNome1'], $comuns_grupo));

        //$itens_form_1->obtGrupoCampo();
        //--------------------------------------------------------- 
        #$itens_form_1->recuperaCampos1();

        # Itens da aba 2 (Endereço)
        $itens_form_2 = new ItensForm2('aba2');
        $itens_form_2->adicGrupoForm('CEP', $cep, array_merge(['classe_grupo' => 'col-md-3', 'id' => 'inputCEP4'], $comuns_grupo));
        $itens_form_2->adicGrupoForm('Endereço', $endereco, array_merge(['classe_grupo' => 'col-md-6', 'id' => 'inputEndereco'], $comuns_grupo)); # '1234 Main St'));
   
        #echo '<pre>';
           #print_r($itens_aba1);
        #echo '</pre>';

        //$itens_form_1->adicGrupoForm('col-md-4', array('CPF', 'form-label'), array('text', 'cpf', 'form-control', 'inputCPF1'));
        //$itens_form_1->adicGrupoForm('col-md-2', array('<br/>', 'form-label'), array('button', 'pesquisar', 'form-control btn btn-primary'), 'Pesquisar');

        # Itens da aba 5 (Observações)
        //$itens_form_5 = new ItensForm2;
        //$itens_form_5->adicGrupoForm('col-md-12', array('Anotações', 'form-label'), array('textarea', 'observacoes', 'form-control', 'inputObservacoes'));
        
        //------------------------------------------------------------------------------------------------------------------------- 
        /** O formulário com abas funciona assim. Os grupos de itens de formulário são inseridos no objeto abas conteúdo. 
         * E em seguida o objeto. AbasConteudo é inserido em um formulário.
         */
        $itens_aba1 = new EmbalaGrupoForm2($itens_form_1, array('id' => 'aba1', 'classe' => 'g-3'));
        $itens_aba2 = new EmbalaGrupoForm2($itens_form_2, array('id' => 'aba1', 'classe' => 'g-3'));
        #$itens_aba2 = new EmbalaGrupoForm($itens_form_5, array('id' => 'aba5', 'classe' => 'g-3'));
        #parent::adic($itens_aba1);

        $params_identificacao = array('titulo_cartao' => "Dados Pessoais", 'id' => 'idAbaIdent', 'role' => 'tablist');
        $cartao_basico = new Cartao($params_identificacao, 'div', []);
        $cartao_basico->adic($itens_aba1);

        $params_endereco = array('titulo_cartao' => "Endereço Atual", 'id' => 'idAbaEndereco', 'role' => 'tablist');
        $cartao_endereco = new Cartao($params_endereco, 'div', []);
        $cartao_endereco->adic($itens_aba2);

        #$params_observacoes = array('titulo_cartao' => "Observação", 'id' => 'idAbaObs', 'role' => 'tablist');
        #$cartao_observacoes = new Cartao($params_observacoes, 'div', []);
        #$cartao_observacoes->adic($itens_aba2);

        # O que estava bagunçando a layout das abas era a class 'row g-3'. Para arrumar, basta criar uma div 
        # com essa classe em cada aba, e deixar form sem classe.        
        $parametros_abas = array('id' => 'meuConteudoAba', 'ativo' => 'basico');
        $abas_conteudo = array('basico' => $cartao_basico, 'endereco' => $cartao_endereco, 'emprego' => '$cartao_emprego', 
                               'referencias' => '$cartao_refs', 'obs' => '$cartao_observacoes');
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
