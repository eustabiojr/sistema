<?php
/***************************************************************************************
 * Sistema Novo
 * 
 * Data: 28/11/2021
 ***************************************************************************************/

# Espaço de nomes
namespace Estrutura\Nucleo;

class NucleoTradutor {
    private static $instancia;
    private $idioma;

    /**
     * Construtor da classe
     */
    private function __construct()
    {
        # array de tradução de idioma para o portuguẽs
        $this->mensagens['pt'][] = 'Carregando';
        $this->mensagens['pt'][] = 'Arquivo não encontrado';
        $this->mensagens['pt'][] = 'Buscar';
        $this->mensagens['pt'][] = 'Registrar';
        $this->mensagens['pt'][] = 'Registro Salvo';
        $this->mensagens['pt'][] = 'Deseja realmente apagar?';
        $this->mensagens['pt'][] = 'Registro excluído';
        $this->mensagens['pt'][] = 'Registros excluídos';
        $this->mensagens['pt'][] = 'Função';
        $this->mensagens['pt'][] = 'Tabela';
        $this->mensagens['pt'][] = 'Ferramenta';
        $this->mensagens['pt'][] = 'Dados';
        $this->mensagens['pt'][] = 'Abrir';
        $this->mensagens['pt'][] = 'Salvar';
        $this->mensagens['pt'][] = 'Listar';
        $this->mensagens['pt'][] = 'Excluir';
        $this->mensagens['pt'][] = 'Excluir selecionados';
        $this->mensagens['pt'][] = 'Editar';
        $this->mensagens['pt'][] = 'Cancelar';
        $this->mensagens['pt'][] = 'Sim';
        $this->mensagens['pt'][] = 'Não';
        $this->mensagens['pt'][] = 'Janeiro';
        $this->mensagens['pt'][] = 'Fevereiro';
        $this->mensagens['pt'][] = 'Março';
        $this->mensagens['pt'][] = 'Abril';
        $this->mensagens['pt'][] = 'Maio';
        $this->mensagens['pt'][] = 'Junho';
        $this->mensagens['pt'][] = 'Julho';
        $this->mensagens['pt'][] = 'Agosto';
        $this->mensagens['pt'][] = 'Setembro';
        $this->mensagens['pt'][] = 'Outubro';
        $this->mensagens['pt'][] = 'Novembro';
        $this->mensagens['pt'][] = 'Dezembro';
        $this->mensagens['pt'][] = 'Hoje';
        $this->mensagens['pt'][] = 'Fechar';
        $this->mensagens['pt'][] = 'Campo para ação &1 não definido';
        $this->mensagens['pt'][] = 'Campo &1 não existe ou contém valor NULO';
        $this->mensagens['pt'][] = 'Use o método &1';
        $this->mensagens['pt'][] = 'Formulário sem campos';
        $this->mensagens['pt'][] = 'E-mail não enviado';
        $this->mensagens['pt'][] = 'O campo &1 não pode ser menor que &2 caracteres';
        $this->mensagens['pt'][] = 'O campo &1 não pode ser maior que &2 caracteres';
        $this->mensagens['pt'][] = 'O campo &1 não pode ser menor que &2';
        $this->mensagens['pt'][] = 'O campo &1 não pode ser maior que &2';
        $this->mensagens['pt'][] = 'O campo &1 é necessário';
        $this->mensagens['pt'][] = 'O campo &1 não possui um CNPJ válido';
        $this->mensagens['pt'][] = 'O campo &1 não possui um CPF válido';
        $this->mensagens['pt'][] = 'O campo &1 contém um e-mail inválido';
        $this->mensagens['pt'][] = 'O campo &1 deve ser numérico';
        $this->mensagens['pt'][] = 'Não há transações ativas com o banco de dados';
        $this->mensagens['pt'][] = 'Objeto não encontrado';
        $this->mensagens['pt'][] = 'Objeto &1 não encontrado em &2';
        $this->mensagens['pt'][] = 'Método &1 não aceita valores nulos';
        $this->mensagens['pt'][] = 'Método &1 deve receber um parâmetro do tipo &2';
        $this->mensagens['pt'][] = 'Estilo &1 não encontrado em &2';
        $this->mensagens['pt'][] = 'Você deve chamar o construtor &1';
        $this->mensagens['pt'][] = 'Você deve chamar &1 antes de &2';
        $this->mensagens['pt'][] = 'Você deve passar o &1 (&2) como parâmetro para &3';
        $this->mensagens['pt'][] = 'O parâmetro (&1) de &2 é necessário';
        $this->mensagens['pt'][] = 'O parâmetro (&1) de &2 construtor é necessário';
        $this->mensagens['pt'][] = 'Você já adicionou um campo chamado "&1" ao formulário';
        $this->mensagens['pt'][] = 'Fechar do aplicativo?';
        $this->mensagens['pt'][] = 'Use adicCampo() ou defCampos para definir os campos do formulário';
        $this->mensagens['pt'][] = 'Verifique se a ação (&1) existe';
        $this->mensagens['pt'][] = 'Informação';
        $this->mensagens['pt'][] = 'Erro';
        $this->mensagens['pt'][] = 'Exceção';
        $this->mensagens['pt'][] = 'Pergunta';
        $this->mensagens['pt'][] = 'A classe &1 não aceita como argumento. A classe informada deve ser subclasse de &2.';
        $this->mensagens['pt'][] = 'A classe &1 não aceita como argumento. A classe informada deve implementar &2.';
        $this->mensagens['pt'][] = 'A classe &1 não encontrada. Verifique o nome da classe ou do arquivo. Eles devem coincidir';
        $this->mensagens['pt'][] = 'Nome de propriedade reservada (&1) na classe &2';
        $this->mensagens['pt'][] = 'A ação (&1) deve ser estática para ser usado em &2';
        $this->mensagens['pt'][] = 'Tentando acessar uma propriedade não existente (&1)'; 
        $this->mensagens['pt'][] = 'Formulário não encontrado. Verifique se você passou o campo (&1) para defCampos()';
        $this->mensagens['pt'][] = 'Classe &1 não encontrada em &2';
        $this->mensagens['pt'][] = 'Você deve chamar &1 antes de adicionar este componente';
        $this->mensagens['pt'][] = 'Driver não encontrado';
        $this->mensagens['pt'][] = 'Buscar registro';
        $this->mensagens['pt'][] = 'Campo';
        $this->mensagens['pt'][] = 'Registro atualizado';
        $this->mensagens['pt'][] = 'Registros atualizados';
        $this->mensagens['pt'][] = 'Entrada';
        $this->mensagens['pt'][] = 'Classe &1 não encontrada';
        $this->mensagens['pt'][] = 'Método &1 não encontrado';
        $this->mensagens['pt'][] = 'Verifique o nome da classe ou o nome do arquivo';
        $this->mensagens['pt'][] = 'Limpar';
        $this->mensagens['pt'][] = 'Selecionar';
        $this->mensagens['pt'][] = 'Você deve definir o campo para a ação (&1)';
        $this->mensagens['pt'][] = 'A seção (&1) não foi fechada de forma apropriada';
        $this->mensagens['pt'][] = 'O método (&1) aceita apenas valores do tipo &2 entre &3 e &4';
        $this->mensagens['pt'][] = 'A classe interna &1 não pode ser executada';
        $this->mensagens['pt'][] = 'A versão mínima exigida para PHP é &1';
        $this->mensagens['pt'][] = '&1 não definido. Você deve chamar &2 em &3';
        $this->mensagens['pt'][] = 'Banco de dados';
        $this->mensagens['pt'][] = 'Construtor';
        $this->mensagens['pt'][] = 'Registros';
        $this->mensagens['pt'][] = 'Descrição';
        $this->mensagens['pt'][] = 'Falha ao copiar arquivo para &1';
        $this->mensagens['pt'][] = 'Permissão negada';
        $this->mensagens['pt'][] = 'Extensão não permitida';
        $this->mensagens['pt'][] = 'Erro de hash';
        $this->mensagens['pt'][] = 'Parâmetro inválido (&1) em &2';
        $this->mensagens['pt'][] = 'Aviso';
        $this->mensagens['pt'][] = 'Nenhum registro localizado';
        $this->mensagens['pt'][] = '&1 para &2 de &3 registros';
        $this->mensagens['pt'][] = 'Módulo PHP não encontrado';
        $this->mensagens['pt'][] = 'O parâmetro (&1) de &2 não deve ser vazio';
        $this->mensagens['pt'][] = 'Retorno em JSON não válido. Verifique a URL';
        $this->mensagens['pt'][] = 'Campos obrigatórios';
        $this->mensagens['pt'][] = 'Erro CSRF';

        # array de tradução de idioma para o inglês
        $this->mensagens['en'][] = 'Loading';
        $this->mensagens['en'][] = 'File not found';
        $this->mensagens['en'][] = 'Search';
        $this->mensagens['en'][] = 'Register';
        $this->mensagens['en'][] = 'Record saved';
        $this->mensagens['en'][] = 'Do you really want to delete?';
        $this->mensagens['en'][] = 'Record deleted';
        $this->mensagens['en'][] = 'Records deleted';
        $this->mensagens['en'][] = 'Fucntion';
        $this->mensagens['en'][] = 'Table';
        $this->mensagens['en'][] = 'Tool';
        $this->mensagens['en'][] = 'Data';
        $this->mensagens['en'][] = 'Open';
        $this->mensagens['en'][] = 'Save';
        $this->mensagens['en'][] = 'List';
        $this->mensagens['en'][] = 'Delete';
        $this->mensagens['en'][] = 'Delete selected';
        $this->mensagens['en'][] = 'Edit';
        $this->mensagens['en'][] = 'Cancel';
        $this->mensagens['en'][] = 'Yes';
        $this->mensagens['en'][] = 'No';
        $this->mensagens['en'][] = 'January';
        $this->mensagens['en'][] = 'February';
        $this->mensagens['en'][] = 'March';
        $this->mensagens['en'][] = 'April';
        $this->mensagens['en'][] = 'May';
        $this->mensagens['en'][] = 'June';
        $this->mensagens['en'][] = 'July';
        $this->mensagens['en'][] = 'August';
        $this->mensagens['en'][] = 'September';
        $this->mensagens['en'][] = 'Octuber';
        $this->mensagens['en'][] = 'November';
        $this->mensagens['en'][] = 'December';
        $this->mensagens['en'][] = 'Today';
        $this->mensagens['en'][] = 'Close';
        $this->mensagens['en'][] = 'Field for action &1 not defined';
        $this->mensagens['en'][] = 'Field &1 not exists or contains NULL value';
        $this->mensagens['en'][] = 'Use the &1 method';
        $this->mensagens['en'][] = 'Form with no fields';
        $this->mensagens['en'][] = 'E-mail not sent';
        $this->mensagens['en'][] = 'The field &1 can not be less than &2 characters';
        $this->mensagens['en'][] = 'The field &1 can not be greater than &2 characters';
        $this->mensagens['en'][] = 'The field &1 can not be less than &2';
        $this->mensagens['en'][] = 'The field &1 can not be greater than &2';
        $this->mensagens['en'][] = 'The field &1 is required';
        $this->mensagens['en'][] = 'The field &1 has not a valid CNPJ';
        $this->mensagens['en'][] = 'The field &1 has not a valid CPF';
        $this->mensagens['en'][] = 'The field &1 contains an invalid e-mail';
        $this->mensagens['en'][] = 'The field &1 must be numeric';
        $this->mensagens['en'][] = 'No active transactions';
        $this->mensagens['en'][] = 'Object not found';
        $this->mensagens['en'][] = 'Object &1 not found in &2';
        $this->mensagens['en'][] = 'Method &1 does not accept null values';
        $this->mensagens['en'][] = 'Method &1 must receive a parameter of type &2';
        $this->mensagens['en'][] = 'Style &1 not found in &2';
        $this->mensagens['en'][] = 'You must call &1 constructor';
        $this->mensagens['en'][] = 'You must call &1 antes &2';
        $this->mensagens['en'][] = 'You must pass the &1 (&2) as a parameter to &3';
        $this->mensagens['en'][] = 'The parameter (&1) of &2 is required';
        $this->mensagens['en'][] = 'The parameter (&1) of &2 contructor is required';
        $this->mensagens['en'][] = 'You have already added a field called "&1" inside the form';
        $this->mensagens['en'][] = 'Quit the application?';
        $this->mensagens['en'][] = 'Use the adicCampo() or defCampos() to define the form fields';
        $this->mensagens['en'][] = 'Check if the action (&1) exists';
        $this->mensagens['en'][] = 'Information';
        $this->mensagens['en'][] = 'Error';
        $this->mensagens['en'][] = 'Exception';
        $this->mensagens['en'][] = 'Question';
        $this->mensagens['en'][] = 'The class &1 was not accepted as argument. The class informed as parameter must be subclass of &2';
        $this->mensagens['en'][] = 'The class &1 was not accepted as argument. The class informed as parameter must implement &2';
        $this->mensagens['en'][] = 'The class &1 was not found. Check the class name or the file name. They must match';
        $this->mensagens['en'][] = 'Reserved property name (&1) in class &2';
        $this->mensagens['en'][] = 'Action (&1) must be static to be used in &2';
        $this->mensagens['en'][] = 'Trying to access a non-existent property (&1)';
        $this->mensagens['en'][] = 'Form not found. Check if you have passed the field (&1) to the defCampos()';
        $this->mensagens['en'][] = 'Class &1 not found in &2';
        $this->mensagens['en'][] = 'You must call &1 before add this component';
        $this->mensagens['en'][] = 'Driver not found';
        $this->mensagens['en'][] = 'Search record';
        $this->mensagens['en'][] = 'Field';
        $this->mensagens['en'][] = 'Record updated';
        $this->mensagens['en'][] = 'Records updated';
        $this->mensagens['en'][] = 'Input';
        $this->mensagens['en'][] = 'Class &1 not found';
        $this->mensagens['en'][] = 'Method &1 not found';
        $this->mensagens['en'][] = 'Check the class name or the file name';
        $this->mensagens['en'][] = 'Clear';
        $this->mensagens['en'][] = 'Select';
        $this->mensagens['en'][] = 'You must define the field for the action (&1)';
        $this->mensagens['en'][] = 'The section (&1) was not closed property';
        $this->mensagens['en'][] = 'The method (&1) just accept values of type &2 between &3 and &4';
        $this->mensagens['en'][] = 'The internal class &1 can not be executed';
        $this->mensagens['en'][] = 'The minimum version required for PHP is &1';
        $this->mensagens['en'][] = '&1 was not defined. You must call &2 in &3';
        $this->mensagens['en'][] = 'Database';
        $this->mensagens['en'][] = 'Constructor';
        $this->mensagens['en'][] = 'Records';
        $this->mensagens['en'][] = 'Description';
        $this->mensagens['en'][] = 'Error while copying file to &1';
        $this->mensagens['en'][] = 'Permission denied';
        $this->mensagens['en'][] = 'Extension not allowed';
        $this->mensagens['en'][] = 'Hash error';
        $this->mensagens['en'][] = 'Invalid parameter (&1) in &2';
        $this->mensagens['en'][] = 'Warning';
        $this->mensagens['en'][] = 'No records found';
        $this->mensagens['en'][] = '&1 to &2 form &3 records';
        $this->mensagens['en'][] = 'PHP Module not found';
        $this->mensagens['en'][] = 'The parameter (&1) of &2 must not be empty';
        $this->mensagens['en'][] = 'Return is not a valid JSON. Check the URL';
        $this->mensagens['en'][] = 'Required fields';
        $this->mensagens['en'][] = 'CSRF Error';

        # array de tradução de idioma para o espanhol
        # A SER IMPLEMENTADO
    }

    /**
     * Retorna a instancia singleton (considere mudar para monostate)
     */
    public static function obtInstancia()
    {
        # caso não exista instancia 
        if (empty(self::$instancia)) {
            # cria um novo objeto
            self::$instancia = new NucleoTradutor;
        }
        # retorna a instância criada
        return self::$instancia;
    }

    /**
     * Define o idioma alvo
     * @param $idioma Índice do idioma alvo
     */
    public static function defIdioma($idioma)
    {
        $instancia = self::obtInstancia();

        if (in_array($idioma, array_keys($instancia->mensagens)))
        {
            $instancia->idioma = $idioma;
        }
    }

    /**
     * Retorna o idioma alvo
     */
    public static function obtIdioma()
    {
        $instancia = self::obtInstancia();
        return $instancia->idioma;
    }

    /**
     * Traduz a palavra para o idioma alvo
     * @param $palavra A palavra a ser traduzida
     */
    public static function traduz($palavra, $param1 = NULL, $param2 = NULL, $param3 = NULL, $param4 = NULL)
    {
        $instancia = self::obtInstancia();
        $chave = array_search($palavra, $instancia->mensagens['pt']);
        if ($chave !== false) {
            # obtém o idioma alvo
            $idioma = self::obtIdioma();
            # retorna a palavra traduzida
            $mensagem = $instancia->mensagens[$idioma][$chave];
            if (isset($param1)) {
                $mensagem = str_replace('&1', $param1, $mensagem);
            }
            if (isset($param2)) {
                $mensagem = str_replace('&2', $param2, $mensagem);
            }
            if (isset($param3)) {
                $mensagem = str_replace('&3', $param3, $mensagem);
            }
            if (isset($param4)) {
                $mensagem = str_replace('&4', $param4, $mensagem);
            }
            return $mensagem;
        } else {
            return 'Mensagem não encontrada: ' . $palavra;
        } 
    }
}