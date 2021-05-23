<?php

use Ageunet\Validacao\ValidadorObrigatorio;
use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Embalagem\ComboBD;
use Estrutura\Bugigangas\Form\Combo;
use Estrutura\Bugigangas\Form\Data;
use Estrutura\Bugigangas\Form\Entrada;
use Estrutura\Bugigangas\Form\Form;
use Estrutura\Bugigangas\Form\MultiBusca;
use Estrutura\Bugigangas\Form\Rotulo;
use Estrutura\Bugigangas\Recipiente\CaixaV;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Pagina;
use Estrutura\Embrulho\BootstrapConstrutorForm;

/**
 * FuncionarioAbasForm Form
 * @author  <your name here>
 */
class FormularioComAbas extends Pagina
{
    protected $form; // form
    private $camposForm = [];
    private static $bancodados = 'exemplos';
    private static $registroAtivo = 'Funcionario';
    private static $chavePrimaria = 'id';
    private static $nomeForm = 'list_Funcionario';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        // creates the form
        $this->form = new BootstrapConstrutorForm(self::$nomeForm);
        // define the form title
        $this->form->defTituloForm('Formulário com abas');

        $id = new Entrada('id');
        $dt_nascimento = new Data('dt_nascimento');
        $nome = new Entrada('nome');
        $sobrenome = new Entrada('sobrenome');
        $cidade_estado_pais_id = new ComboBD('cidade_estado_pais_id', 'exemplos', 'Pais', 'id', '{nome}','nome asc'  );
        $cidade_estado_id = new Combo('cidade_estado_id');
        $cidade_id = new Combo('cidade_id');
        $cep = new Entrada('cep');
        $endereco = new Entrada('endereco');
        $funcao_id = new ComboBD('funcao_id', 'exemplos', 'Funcao', 'id', '{nome}','nome asc'  );
        $habilidades = new MultiBusca('habilidades', 'exemplos', 'Habilidades', 'id', 'nome','nome asc'  );

        $cidade_estado_pais_id->defMudaAcao(new Acao([$this,'aoMudarcidade_estado_pais_id']));
        $cidade_estado_id->defMudaAcao(new Acao([$this,'onChangecidade_estado_id']));

        $dt_nascimento->adicValidacao('Data de nascimento', new ValidadorObrigatorio()); 
        $nome->adicValidacao('Nome', new ValidadorObrigatorio()); 
        $sobrenome->adicValidacao('Sobrenome', new ValidadorObrigatorio()); 
        $cidade_id->adicValidacao('Cidade id', new ValidadorObrigatorio()); 
        $funcao_id->adicValidacao('Funcao id', new ValidadorObrigatorio()); 

        $id->defEditavel(false);
        $dt_nascimento->setDatabaseMask('yyyy-mm-dd');
        $habilidades->setMinLength(2);

        $habilidades->defMascara('{nome}');
        $dt_nascimento->defMascara('dd/mm/yyyy');

        $id->defTamanho(100);
        $cep->defTamanho('100%');
        $nome->defTamanho('100%');
        $endereco->defTamanho('100%');
        $sobrenome->defTamanho('100%');
        $cidade_id->defTamanho('100%');
        $funcao_id->defTamanho('100%');
        $dt_nascimento->defTamanho(110);
        $habilidades->defTamanho('100%', 68);
        $cidade_estado_id->defTamanho('100%');
        $cidade_estado_pais_id->defTamanho('100%');

        $this->form->appendPage('Dados pessoais');
        $this->form->adicCampos([new Rotulo('Id:')],[$id],[new Rotulo('Data de nascimento:', '#ff0000')],[$dt_nascimento]);
        $this->form->adicCampos([new Rotulo('Nome:', '#ff0000')],[$nome],[new Rotulo('Sobrenome:', '#ff0000')],[$sobrenome]);

        $this->form->appendPage('Endereço');
        $this->form->adicCampos([new Rotulo('País:', '#ff0000')],[$cidade_estado_pais_id],[new Rotulo('Estado:', '#ff0000')],[$cidade_estado_id]);
        $this->form->adicCampos([new Rotulo('Cidade:', '#ff0000')],[$cidade_id],[new Rotulo('CEP:', '#ff0000')],[$cep]);
        $this->form->adicCampos([new Rotulo('Endereco:', '#ff0000')],[$endereco]);

        $this->form->appendPage('Capacitação');
        $this->form->adicCampos([new Rotulo('Funcao id:', '#ff0000')],[$funcao_id]);
        $this->form->adicCampos([new Rotulo('Habilidades')],[$habilidades]);

        // create the form actions
        $btn_onsave = $this->form->adicAcao('Salvar', new Acao([$this, 'onSave']), 'fa:floppy-o #ffffff');
        $btn_onsave->adicStyleClass('btn-primary'); 

        $btn_onclear = $this->form->adicAcao('Limpar formulário', new Acao([$this, 'aoLimpar']), 'fa:eraser #dd5a43');

        // vertical box container
        $container = new CaixaV;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->adic(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->adic($this->form);

        parent::adic($container);

    }

    public static function aoMudarcidade_estado_pais_id($param)
    {
        try
        {
            if (isset($param['cidade_estado_pais_id']) && $param['cidade_estado_pais_id'])
            { 
                $criteria = Criterio::cria(['pais_id' => (int) $param['cidade_estado_pais_id']]); 
                ComboBD::recarregaDoModelo(self::$nomeForm, 'cidade_estado_id', 'exemplos', 'Estado', 'id', '{nome}', 'nome asc', $criteria, TRUE); 
            } 
            else 
            { 
                Combo::limpaCampo(self::$nomeForm, 'cidade_estado_id'); 
            }  

        }
        catch (Exception $e)
        {
            new Mensagem('erro', $e->getMessage());
        }
    } 

    public static function onChangecidade_estado_id($param)
    {
        try
        {
            if (isset($param['cidade_estado_id']) && $param['cidade_estado_id'])
            { 
                $criteria = Criterio::cria(['estado_id' => (int) $param['cidade_estado_id']]); 
                ComboBD::recarregaDoModelo(self::$nomeForm, 'cidade_id', 'exemplos', 'Cidade', 'id', '{nome}', 'nome asc', $criteria, TRUE); 
            } 
            else 
            { 
                Combo::limpaCampo(self::$nomeForm, 'cidade_id'); 
            }  

        }
        catch (Exception $e)
        {
            new Mensagem('erro', $e->getMessage());
        }
    } 

    public function onSave($param = null) 
    {
        try
        {
            Transacao::abre(self::$bancodados); // open a transaction

            /**
            // Enable Debug logger for SQL operations inside the transaction
            Transacao::setLogger(new TLoggerSTD); // standard output
            Transacao::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $objeto = new Funcionario(); // create an empty object 

            $data = $this->form->obtDados(); // get form data as array
            $objeto->doArray( (array) $data); // load the object with data

            $objeto->grava(); // save the object 

            $this->disparaEventos($objeto);

            $repository = HabilidadesFuncionario::where('funcionario_id', '=', $objeto->id);
            $repository->delete(); 

            if ($data->habilidades) 
            {
                foreach ($data->habilidades as $habilidades) 
                {
                    $habilidades_funcionario = new HabilidadesFuncionario;

                    $habilidades_funcionario->habilidades_id = $habilidades;
                    $habilidades_funcionario->funcionario_id = $objeto->id;
                    $habilidades_funcionario->grava();
                }
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $objeto->id; 

            $this->form->setData($data); // fill form data
            Transacao::fecha(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new Acao(['className', 'methodName']);
            **/

            new Mensagem('info', 'Record saved', $messageAction);

        }
        catch (Exception $e) // in case of exception
        {
            new Mensagem('erro', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->obtDados() ); // keep form data
            Transacao::desfaz(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function aoLimpar( $param )
    {
        $this->form->clear(true);

    }  

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                Transacao::abre(self::$bancodados); // open a transaction

                $objeto = new Funcionario($key); // instantiates the Active Record 

                $objeto->cidade_estado_pais_id = $objeto->cidade->estado->pais->id;
    $objeto->cidade_estado_id = $objeto->cidade->estado->id;

                $criteria = Criterio::cria(['funcionario_id'=>$objeto->id]);
                $objeto->habilidades = HabilidadesFuncionario::getIndexedArray('habilidades_id', 'habilidades_id', $criteria);

                $this->form->setData($objeto); // fill the form 

                $this->disparaEventos($objeto);

                Transacao::fecha(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new Mensagem('erro', $e->getMessage()); // shows the exception error message
            Transacao::desfaz(); // undo all pending operations
        }
    }

    public function aoExibir()
    {

    } 

    public function disparaEventos( $objeto )
    {
        $obj = new stdClass;
        if(get_class($objeto) == 'stdClass')
        {
            if(isset($objeto->cidade_estado_pais_id))
            {
                $obj->cidade_estado_pais_id = $objeto->cidade_estado_pais_id;
            }
            if(isset($objeto->cidade_estado_id))
            {
                $obj->cidade_estado_id = $objeto->cidade_estado_id;
            }
            if(isset($objeto->cidade_id))
            {
                $obj->cidade_id = $objeto->cidade_id;
            }
        }
        else
        {
            if(isset($objeto->cidade->estado->pais->id))
            {
                $obj->cidade_estado_pais_id = $objeto->cidade->estado->pais->id;
            }
            if(isset($objeto->cidade->estado->id))
            {
                $obj->cidade_estado_id = $objeto->cidade->estado->id;
            }
            if(isset($objeto->cidade_id))
            {
                $obj->cidade_id = $objeto->cidade_id;
            }
        }
        Form::enviaDados(self::$nomeForm, $obj);
    }  

}

