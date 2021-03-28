<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 14/03/2021
 ************************************************************************************/

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Repositorio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Dialogo\Pergunta;
use Estrutura\Bugigangas\Embrulho\EmbrulhoGradedados;
use Estrutura\Bugigangas\Gradedados\ColunaGradedados;
use Estrutura\Bugigangas\Gradedados\Gradedados;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;

/**
 * Class ListaFuncionario
 */
class ListaFuncionario extends Pagina
{
    private $gradedados;
    private $carregado;

    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        # instancia o objeto de grade de dados
        $this->gradedados = new EmbrulhoGradedados(new Gradedados);
        
        # instancia as colunas da grade de dados
        $codigo   = new ColunaGradedados('id',       'Código',   'center', '10%');
        $nome     = new ColunaGradedados('nome',     'Nome',     'left',   '30%');
        $endereco = new ColunaGradedados('endereco', 'Endereço', 'left',   '30%');
        $email    = new ColunaGradedados('email',    'Email',    'left',   '30%');

        # adiciona as colunas à grade de dados
        $this->gradedados->adicColuna($codigo);
        $this->gradedados->adicColuna($nome);
        $this->gradedados->adicColuna($endereco);
        $this->gradedados->adicColuna($email);

        $nome->defTransformador(function($valor) {
            return strtoupper($valor);
        });

        $this->gradedados->adicAcao('Editar', new Acao(array(new FormFuncionario, 'aoEditar')), 'id');
        $this->gradedados->adicAcao('Excluir', new Acao(array($this, 'Exclui')), 'id');

        # adiciona a grade de dados à página
        parent::adic($this->gradedados);
    }

    /**
     * Método aoRecarregar
     */
    public function aoRecarregar()
    {
        Transacao::abre('exemplo');
        $repositorio = new Repositorio('Funcionario');

        # cria um critério de seleção de dados
        $criterio = new Criterio;
        $criterio->defPropriedade('ORDER', 'id');

        # carrega os produtos que satisfazem o critério
        $funcionarios = $repositorio->carrega($criterio);
        $this->gradedados->limpa();
        if ($funcionarios) {
            foreach ($funcionarios as $funcionario) {
                # adiciona grade de dados
                $this->gradedados->adicItem($funcionario);
            }
        }

        Transacao::fecha();
        $this->carregado = true;
    }

    /**
     * Método aoExcluir
     */
    public function aoExcluir($param) {
        $id = $param['id'];
        $acao1 = new Acao(array($this, 'Exclui'));
        $acao1->defParametro('id', $id);

        new Pergunta('Deseja realmente excluir o registro?', $acao1);
    }

    /**
     * Método Exclui
     */
    public function Exclui($param)
    {
        try {
            $id = $param['id'];
            Transacao::abre('exemplo');

            $funcionario = Funcionario::localiza($id);
            if ($funcionario) {
                $funcionario->apaga();
            }

            Transacao::fecha();
            $this->aoRecarregar();
            new Mensagem('info', "Registro excluído com sucesso");
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }

    /**
     * Método exibe
     */
    public function exibe()
    {
        # se a listagem ainda não foi carregada
        if (!$this->carregado) {
            $this->aoRecarregar();
        }
        parent::exibe();
    }
 }