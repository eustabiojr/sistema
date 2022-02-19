<?php
/********************************************************************************************
 * Sistema
 * 
 * Data: 07/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\BancoDados;

use IteratorAggregate;
use ArrayIterator;
use Estrutura\Nucleo\NucleoTradutor;
use PDO;
use Exception;
use Matematica\Analisador;
use Traversable;

/**
 * Classe base para Registro Ativo (Active Records)
 * 
 * @version 1.0
 * @package bancodados
 */
abstract class Gravacao implements IteratorAggregate {
    # 
    protected $dados;
    protected $pdo;
    protected $dados_virtual;
    protected $atributos;

    /**
     * Construtor da classe
     * Instancia o Registro Ativo
     * @param [$id] ID opcional do objeto, se passado, carrega seu objeto
     */
    public function __construct($id = null, $chamaObjetoCarregado = TRUE) {

        $this->atributos = [];

        # Caso o usuário tenha informado o $id
        if ($id) {
            $objeto = $this->carrega($id);

            if ($chamaObjetoCarregado) {
                $objeto = $this->carrega($id);
            } else {
                $objeto = self::carrega($id);
            }

            if ($objeto) {
                $this->doArray($objeto->paraArray() );
            } else {
                throw new Exception(NucleoTradutor::traduz('Objeto & não encontrado em &2', $id, constant(get_class($this) . '::NOMETABELA')));
            }
        }
    }

    /**
     * Retorna iterador
     */
    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->dados);
    }

    /**
     * Apelido para getIterator
     */
    public function obtIterador() {
        return $this->getIterator();
    }

    /**
     * Cria um new Gravacao e retorna a instância
     * @param $dados array indexado
     */
    public static function cria($dados)
    {
        $objeto = new static;
        $objeto->doArray($dados);
        $objeto->grava();
        return $objeto;
    }

    /**
     * Executado quadno o programador clona um Registro Ativo
     * Neste caso, temos que limpar o ID, para gerar um novo
     */
    public function __clone()
    {
        $cp = $this->obtChavePrimaria(); # não entendi, achava que deveria usar a variável sem o $this na próxima linha
        unset($this->$cp);
    }

    /**
     * Executado sempre que um método desconhecido é executado
     * @param $metodo Nome do método
     * @param $parametros Parametros do método
     */
    public static function __callStatic($metodo, $parametros)
    {
        $nome_classe = get_called_class();

        if(substr($metodo, -13) == 'EmTransacao') {
            $metodo = substr($metodo, 0, -11);
            if (method_exists($nome_classe, $metodo)) {
                $bancodados = array_shift($parametros);
                Transacao::abre($bancodados);
                $conteudo = forward_static_call_array(array($nome_classe, $metodo), $parametros);
                Transacao::fecha();
                return $conteudo;
            } else {
                throw new Exception(NucleoTradutor::traduz('Método &1 não encontrado', $nome_classe . '::' . $metodo . '()'));
            }
        } else if(method_exists('Repositorio', $metodo)) {
            $classe = get_called_class(); 
            $repositorio = new Repositorio($classe);
            return call_user_func_array(array($repositorio, $metodo), $parametros);
        } else {
            throw new Exception(NucleoTradutor::traduz('Método &1 não encontrado', $nome_classe . '::' . $metodo . '()'));
        }
    }
    
    /**
     * Executado sempre que uma propriedade é acessada
     * @param $propriedade O nome da propriedade do objeto
     * @return O valor da propriedade
     */
    public function __get($propriedade) {
        # verifica se existe um método chamado obt_<propriedade>
        if (method_exists($this, 'obt_'.$propriedade)) {
            // executa o método obt_<propriedade>
            return call_user_func(array($this, 'obt_'.$propriedade));
        } else {
            if (strpos($propriedade, '->') !== FALSE) {
                $partes = explode('->', $propriedade);
                $recipiente = $this;
                foreach($partes as $parte) {
                    if(is_object($recipiente)) {
                        $resultado = $recipiente->$parte;
                        $recipiente = $resultado;
                    } else {
                        throw new Exception(NucleoTradutor::traduz('Tentando acessar uma propriedade não existente (&1)', $propriedade));
                    }
                }

                return $resultado;
            } else {
                // retorna o valor da propriedade
                if (isset($this->dados[$propriedade])) {
                    return $this->dados[$propriedade];
                } else if(isset($this->dados_virtual[$propriedade])) {
                    return $this->dados_virtual[$propriedade];
                }
            }
        }
    }
    
    /**
     * Executado sempre que uma propriedade é acessada
     * @param $propriedade O nome da propriedade do objeto
     * @return O valor da propriedade
     */
    public function __set($propriedade, $valor) {
        if ($propriedade == 'dados') {
            throw new Exception(NucleoTradutor::traduz('Nome de propriedade reservada (&1) na classe &2', $propriedade, get_class($this)));
        }

        # verifica se existe um método chamado def_<propriedade>
        if (method_exists($this, 'def_'.$propriedade)) {
            // executa o método def_<propriedade>
            call_user_func(array($this, 'def_'.$propriedade), $valor);
        } else {
            if ($valor === NULL) {
                $this->dados[$propriedade] = NULL;
            } else if(is_scalar($valor)) {
                # atribui o valor da propriedade
                $this->dados[$propriedade] = $valor;
                unset($this->dados_virtual[$propriedade]);
            } else {
                # outras propriedades não-escalar que não serão persistidas
                $this->dados_virtual[$propriedade] = $valor;
                unset($this->dados[$propriedade]);
            }
        }
    }

    /**
     * Retorna se a propriedade está setada
     * @param $propriedade Nome da propriedade do objeto
     */
    public function __isset($propriedade) {
        return isset($this->dados[$propriedade]) OR 
                isset($this->dados_virtual[$propriedade]) OR
                method_exists($this, 'obt_'.$propriedade);
    }

    /**
     * Redefine a propriedade
     * @param $propriedade Nome da propriedade do objeto
     */
    public function __unset($propriedade)
    {
        unset($this->dados[$propriedade]);
        unset($this->dados_virtual[$propriedade]);
    }

    /**
     * Retorna o controle de cache
     */
    public function obtControleCache()
    {
        $nome_classe = get_class($this);
        $nome_cache = "{$nome_classe}::CONTROLECACHE";

        if (defined($nome_cache)) {
            $controle_cache = constant($nome_cache);
            $implementa = \class_implements($controle_cache);

            if (in_array('Guepardo\Registro\InterfaceRegistro', $implementa)) {
                if ($controle_cache::habilitado()) {
                    return $controle_cache;
                }
            }
        }
        return FALSE;
    }

    public function obtEntidade() {
        # $classe = get_called_class();
        $classe = get_class($this);

        # define('SISTEMA','AplicativoTeste'); // so um teste simples
        return constant("{$classe}::NOME_TABELA");
    }

    public function obtChavePrimaria()
    {
        # obtém o nome da classe do Registro Ativo
        $nome_classe = get_class($this);
        # retorna a constante CHAVE PRIMÁRIA da classe Registro Ativo
        return constant("{$nome_classe}::CHAVEPRIMARIA");
    }

    /**
     * Retorna o nome da coluna criada em
     * @return Uma string contendo a coluna criada em
     */
    public function obtColunaCriadaEm()
    {
        // obt o nome da classe de Registro Ativo
        $nome_classe = get_class($this);

        if (defined("{$nome_classe}::CRIADAEM")) {
            # retorna constante de classe CRIADAEM do Registro Ativo
            return constant("{$nome_classe}::CRIADAEM");
        }
    }

    /**
     * Retorna o nome da coluna atualizada em
     * @return Uma string contendo a coluna atualizada em
     */
    public function obtColunaAtualizadaEm()
    {
        // obt o nome da classe de Registro Ativo
        $nome_classe = get_class($this);

        if (defined("{$nome_classe}::ATUALIZADAEM")) {
            # retorna constante de classe ATUALIZADAEM do Registro Ativo
            return constant("{$nome_classe}::ATUALIZADAEM");
        }
    }

    /**
     * Retorna o nome da sequência da chave primária
     * @return Uma string contendo o nome da sequência
     */
    private function obtNomeSequencia()
    {
        // obt o nome da classe de Registro Ativo
        $nome_classe = get_class($this);

        if (defined("{$nome_classe}::SEQUENCIA")) {
            # retorna constante de classe SEQUENCIA do Registro Ativo
            return constant("{$nome_classe}::SEQUENCIA");
        } else {
            return $this->obtEntidade() . '_' . $this->obtChavePrimaria() . '_seq';
        }
    }

    /**
     * Preenche as propreidades do Registro Ativo a partir de outro Registro Ativo
     * @param $objeto Um Registro Ativo
     */
    public function mesclaObjeto(Gravacao $objeto) 
    {
        $dados = $objeto->paraArray();
        foreach($dados as $chave => $valor) {
            $this->dados[$chave] = $valor;
        }
    }

    /**
     * Preenche as propriedades do Registro Ativo a partir do array indexado
     * @param $dados Um array indexado contendo as propriedades do objeto
     */
    public function doArray($dados) { 
        # $this->dados = $dados; 
        if (count($this->atributos) > 0) {
            $cp = $this->obtChavePrimaria();
            foreach($dados as $chave => $valor) {
                # apenas define os atributos defindos por adicAtributos()
                if((in_array($chave, $this->atributos) AND is_string($chave)) OR ($chave === $cp)) {
                    $this->dados[$chave] = $dados[$chave];
                }
            }
        } else {
            foreach($dados as $chave => $valor) {
                $this->dados[$chave] = $dados[$chave];
            }
        }
    }

    /**
     * Retorna as propriedades do Registro Ativo como um array indexado
     * @param $atributos_filtro Array de atributos a ser retornado
     * @return Um array indexado contendo as propriedades do objeto
     */
    public function paraArray($atributos_filtro = null) {
        $atributos = $atributos_filtro ? $atributos_filtro : $this->atributos;

        $dados = array();
        if (count($atributos) > 0) {
            $cp = $this->obtChavePrimaria();
            if(!empty($this->dados)) {
                foreach($dados as $chave => $valor) {
                    # apenas define os atributos defindos por adicAtributos()
                    if((in_array($chave, $this->atributos) AND is_string($chave)) OR ($chave === $cp)) {
                        $this->dados[$chave] = $this->dados[$chave];
                    }
                }
            }
        } else {
            $dados = $this->dados;
        }
        return $dados;
    }

    /**
     * Retorna as propriedades do Registro Ativo como uma string json
     * @return Uma string JSON
     */
    public function paraJson()
    {
        return json_encode($this->paraArray());
    }

    /**
     * Renderiza variáveis em chaves
     */
    public function renderiza($padrao, $molda = null)
    {
        $conteudo = $padrao;
        if (preg_match_all('/\{(.*?)\}/', $padrao, $coincidencias)) {
            foreach($coincidencias[0] as $coincidencia) {
                $propriedade = substr($coincidencia, 1, -1);
                if (substr($propriedade, 0, 1) == '$') {
                    $propriedade = substr($propriedade, 1);
                }
                $valor = $this->$propriedade;
                if($molda) {
                    settype($valor, $molda);
                }
                $conteudo = str_replace($coincidencia, $valor, $conteudo);
            }
        }
        return $conteudo;
    }

    /**
     * Avalia variáveis dentro de colchetes
     */
    public function avalia($padrao)
    {
        $conteudo = $this->renderiza($padrao, 'float');
        $conteudo = str_replace('+', ' + ', $conteudo);
        $conteudo = str_replace('-', ' - ', $conteudo);
        $conteudo = str_replace('*', ' * ', $conteudo);
        $conteudo = str_replace('/', ' / ', $conteudo);
        $conteudo = str_replace('(', ' ( ', $conteudo);
        $conteudo = str_replace(')', ' ) ', $conteudo);
        $analise = new Analisador();
        $conteudo = $analise->avalia(substr($conteudo, 1));
        return $conteudo;
    }

    /**
     * Regisra um atributo persistente
     */
    public function adicAtributo($atributo) 
    {
        if ($atributo == 'dados') {
            throw new Exception(NucleoTradutor::traduz('Nome de propriedade reservada (&1) na classe &2', $atributo, get_class($this)));
        }
        $this->atributos[] = $atributo;
    }

    /**
     * Retorna os atributos persistentes
     */
    public function obtAtributos(){
        return $this->atributos;
    }

    /**
     * Obtém lisa de atributos
     */
    public function obtListaAtributos()
    {
        if(count($this->atributos) > 0) {
            $atributos = $this->atributos;
            array_unshift($atributos, $this->obtChavePrimaria());
            return implode(', ', array_unique($atributos));
        }
        return '*';
    }

    /**
     * Grava os objetos no banco de dados
     * @return O número de linhas afetadas
     * @exception Exceção se não há transação ativa aberta
     */
    public function grava() {
        # obtém o nome da classe de Registro Ativo
        $nome_classe = get_class($this);

        # verifica se o objeto possui um ID ou existe no banco de dados
        $cp = $this->obtChavePrimaria();

        if(method_exists($this, 'noAntesDeSalvar')) {
            $objeto_virtual = (object) $this->dados;
            $this->noAntesDeSalvar($objeto_virtual);
            $this->dados = (array) $objeto_virtual;
        }

        ##***********************************************************************
        if (empty($this->dados[$cp]) OR (!self::existe($this->$cp))) 
        {
            # incrementa o ID
            if (empty($this->dados[$cp])) {
                if ((defined("{$nome_classe}::IDPOLITICA")) AND (constant("{$nome_classe}::IDPOLITICA") == 'serial')) {
                    unset($this->cp);
                } else {
                    $this->$cp = $this->obtUltimoId();
                }
            }
            # cria um instrução INSERT
            $sql = new SqlInsert;
            $sql->defEntidade($this->obtEntidade());

            # itera o objeto dados
            foreach($this->dados as $chave => $valor) {
                # verifica se o campo é calculado
                if (!method_exists($this, 'obt_' . $chave) OR (count($this->atributos) > 0)) {
                    if (count($this->atributos) > 0) {
                        # define apenas atributos definidos por adicAtributo()
                        if ((in_array($chave, $this->atributos) AND is_string($chave))  OR ($chave === $cp)) {
                            # passa o objeto dados para o SQL
                            $sql->defDadosLinha($chave, $this->dados[$chave]);
                        }
                    } else {
                        # passa o objeto dados para o SQL
                        $sql->defDadosLinha($chave, $this->dados[$chave]);
                    }
                }
            }

            $criadoem = $this->obtColunaCriadaEm();
            if (!empty($criadoem)) {
                $sql->defDadosLinha($criadoem, date('Y-m-d H:i:s'));
            }
        ##***********************************************************************
        } else {
            # cria uma instrução UPDATE
            $sql = new SqlUpdate;
            $sql->defEntidade($this->obtEntidade());

            # cria um critério select com base no ID
            $criterio = new Criterio;
            $criterio->adic(new Filtro($cp, '=', $this->cp));
            $sql->defCriterio($criterio);

            # itera o objeto dados
            foreach($this->dados as $chave => $valor) {
                // não há necessidade de mudar o valor ID
                if ($chave !== $cp) {

                    # verifica se o campo é calculado
                    if(!method_exists($this, 'obt_' . $chave) OR (count($this->atributos) > 0)) {
                        if (count($this->atributos) > 0) {
                            # define apenas atributos definidos por adicAtributo()
                            if ((in_array($chave, $this->atributos) AND is_string($chave)) OR ($chave == $cp)) {
                                # passa o objeto dados para o SQL
                                $sql->defDadosLinha($chave, $this->dados[$chave]);
                            }
                        } else {
                            # passa o objeto dados para o SQL
                            $sql->defDadosLinha($chave, $this->dados[$chave]);
                        }
                    }
                }
            }

            $atuazadoem = $this->obtColunaAtualizadaEm();
            if (!empty($atuazadoem)) {
                $sql->defDadosLinha($atuazadoem, date('Y-m-d H:i:s'));
            }
        }
        // obt a conexão da transação ativa
        if ($cnx = Transacao::obt()) {
            # registra o operação no arquivo de HISTÓRICO
            Transacao::hist($sql->obtInstrucao());

            $infobd = Transacao::obtInfoBancodados();
            if (isset($infobd['prep']) AND $infobd['prep'] == '1') {
                $resultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resultado->execute($sql->obtVarsPreparadas());
            } else {
                # executa a consulta
                $resultado = $cnx->query($sql->obtInstrucao());
            }

            if ((defined("{$nome_classe}::IDPOLITICA")) AND (constant("{$nome_classe}::IDPOLITICA") == 'serial')) {
                if(($sql instanceof SqlInsert) AND empty($this->dados[$cp])) {
                    $this->$cp = $cnx->lastInsertId($this->obtNomeSequencia());
                }
            }

            if ($cache = $this->obtControleCache()) {
                $chave_registro = $nome_classe . '[' . $this->$cp . ']';
                if ($cache::defValor($chave_registro, $this->paraArray())) {
                    Transacao::hist($chave_registro, ' gravado em cache');
                }
            }

            if (method_exists($this, 'NoPosGravar')) {
                $this->noPosGravar((object) $this->paraArray());
            }

            # retorna o resulado do método exec()
            return $resultado;
        } else {
            # caso não exista transação ativa aberta
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Testa se o ID existe
     * @param $id O ID do objeto
     * @exception Exceção se não há transação ativa aberta
     */
    public function existe($id)
    {
        if (empty($id)) {
            return FALSE;
        }

        $nome_classe = get_class($this); # obtém o nome da classe de Registro Ativo
        $cp = $this->obtChavePrimaria(); # descobre o nome da chave primária

        # cria uma instrução SELECT
        $sql = new SqlSelect;
        $sql->defEntidade($this->obtEntidade());
        $sql->adicColuna($this->obtListaAtributos());

        # cria o critério de seleção baseado no ID
        $criterio = new Criterio;
        $criterio->adic(new Filtro($cp, '=', $id));
        $sql->defCriterio($criterio);

        # obtém a conexão da transação ativa
        if($cnx = Transacao::obt()) {
            $infobd = Transacao::obtInfoBancodados();
            if (isset($infobd['prep']) AND $infobd['prep'] == '1') // Preparada LIGADO
            {
                $resultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resultado->execute($criterio->obtVarsPreparadas());
            } else {
                $resultado = $cnx->query($sql->obtInstrucao());
            }

            # se há um resultado
            if ($resultado) {
                // retorna os dados como um objeto desta classe
                $objeto = $resultado->fetchObject(get_class($this));
            }

            return is_object($objeto);
        } else {
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Recarrega um objeto Registro Ativo do banco de dados
     */
    public function recarrega()
    {
        $cp = $this->obtChavePrimaria(); # descobre o nome da chave primária
        return $this->carrega($this->$cp);
    }

    /**
     * Carrega um objeto de Registro Ativo do banco de dados
     * @param $id  O ID do objeto
     * @return O objeto Registro Ativo
     * @exception Exceção se não há transação ativa aberta
     */
    public function carrega($id) 
    {   
        $nome_classe = get_class($this);
        $cp = $this->obtChavePrimaria();

        if (method_exists($this, 'noPreCarregamento')) {
            $this->noPreCarregamento($id);
        }

        if ($cache = $this->obtControleCache()) {
            $chave_registro = $nome_classe . '[' . $id . ']';
            if ($dados_trazidos = $cache::obtValor($chave_registro)) {
                $objeto_trazido = (object) $dados_trazidos;
                $objeto_carregado = clone $this;
                if (method_exists($this, 'noPosCarregamento')) {
                    $this->noPosCarregamento($objeto_trazido);
                    $objeto_carregado->doArray((array) $objeto_trazido);
                } else {
                    $objeto_carregado->doArray($dados_trazidos);
                }
                Transacao::hist($chave_registro . 'carregado do cache');
                return $objeto_carregado;
            }
        }

        # cria uma instrução SELECT
        $sql = new SqlSelect;
        $sql->defEntidade($this->obtEntidade());
        # usa *, uma vez que isto é chamado antes de adicTributo()
        $sql->adicColuna($this->obtListaAtributos());

        # cria um critério select com base no ID
        $criterio = new Criterio;
        $criterio->adic(new Filtro($cp, '=', $id));
        # define o critério de seleção
        $sql->defCriterio($criterio);

        # obtém a conexão da transação ativa
        if ($cnx = Transacao::obt()) {
            # registra a operação no arquivo de HISTÓRICO
            Transacao::hist($sql->obtInstrucao());

            $infobd = Transacao::obtInfoBancodados();
            if (isset($infobd['prep']) AND $infobd['prep'] == '1') // Preparada LIGADO
            {
                $resultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resultado->execute($criterio->obtVarsPreparadas());
            } else {
                # executa a consulta
                $resultado = $cnx->query($sql->obtInstrucao());
            }

            # se há um resultado
            if($resultado) {
                $classeAtiva = get_class($this);
                $objeto_trazido = $resultado->fetchObject();
                if ($objeto_trazido) {
                    if (method_exists($this, 'noPosCarregar')) {
                        $this->noPosCarregar($objeto_trazido);
                    }
                    $objeto = new $classeAtiva;
                    $objeto->doArray((array) $objeto_trazido);
                } else {
                    $objeto = NULL;
                }

                if($objeto) {
                    if ($cache = $this->obtControleCache()) {
                        $chave_registro = $nome_classe . '[' . $id . ']';
                        if ($cache::defValor($chave_registro . ' armazenado em cache'));
                    }
                }
            }
        }
    }

    /**
     * Exclui um objeto de Registro Ativo do banco de dados
     * @param $id  O ID do objeto
     * @exception Exceção se não há transação ativa aberta
     */
    public function exclui($id = NULL) {
        $nome_classe = get_class($this);

        if (method_exists($this, 'noPreCarregamento')) {
            $this->noPreExclusao((object) $this->paraArray());
        }

        # descobre o nome da chave primária
        $cp = $this->obtChavePrimaria();
        # caso o usuário não tenha passado um ID, pega o ID do objeto
        $id = $id ? $id : $this->$cp;
        # cria uma instrução DELETE
        $sql = new SqlDelete;
        $sql->defEntidade($this->obtEntidade());

        # cria um critério de seleção
        $criterio = new Criterio;
        $criterio->adic(new Filtro($cp, '=', $id));
        # atribui o critério à instrução de exclusão (DELETE)
        $sql->defCriterio($criterio);

        # obtém a conexão da transação ativa
        if ($cnx = Transacao::obt()) {
            # registra a operação no arquivo de HISTÓRICO
            Transacao::hist($sql->obtInstrucao());
        
            $infobd = Transacao::obtInfoBancodados();
            if (isset($infobd['prep']) AND $infobd['prep'] == '1') // Preparada LIGADO
            {
                $resultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resultado->execute($criterio->obtVarsPreparadas());
            } else {
                # executa a consulta
                $resultado = $cnx->query($sql->obtInstrucao());
            }

            if ($cache = $this->obtControleCache()) {
                $chave_registro = $nome_classe . '[' . $id . ']';
                if($cache::apagValor($chave_registro)) {
                    Transacao::hist($chave_registro . ' excluído do cache');
                }
            }

            if(method_exists($this, 'noPosExclui')) {
                $this->noPosExclui((object) $this->paraArray());
            }

            unset($this->dados);

            # retorna o resultado do método exec()
            return $resultado;
        } else {
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Retorna o PRIMEIRO ID do objeto do banco de dados
     * @return Um inteiro contendo o PRIMERIO ID do objeto no banco de dados
     * @exception Exceção se não há transação ativa aberta
     */
    public function obtPrimeiroId()
    {
        $cp = $this->obtChavePrimaria();

        # obtém a conexão da transação ativa
        if ($cnx = Transacao::obt()) {
            // instancia instrução SELECT
            $sql = new SqlSelect;
            $sql->adicColuna("min($cp) as {$cp}");
            $sql->defEntidade($this->obtEntidade());

            // registra a operação no arquivo de HISTÓRICO
            Transacao::hist($sql->obtInstrucao());
            $resultado = $cnx->query($sql->obtInstrucao());

            // retorna os dados do banco de dados
            $linha = $resultado->fetch();
            return $linha[0];
        } else {
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Retorna o PRIMEIRO ID do objeto do banco de dados
     * @return Um inteiro contendo o PRIMERIO ID do objeto no banco de dados
     * @exception Exceção se não há transação ativa aberta
     */
    private function obtUltimoId() 
    {
        $cp = $this->obtChavePrimaria();

        # obtém a conexão da transação ativa
        if ($cnx = Transacao::obt()) {
            // instancia instrução SELECT
            $sql = new SqlSelect;
            $sql->adicColuna("max($cp) as {$cp}");
            $sql->defEntidade($this->obtEntidade());

            // registra a operação no arquivo de HISTÓRICO
            Transacao::hist($sql->obtInstrucao());
            $resultado = $cnx->query($sql->obtInstrucao());

            // retorna os dados do banco de dados
            $linha = $resultado->fetch();
            return $linha[0];
        } else {
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Método obtObjetos
     * @param $criterio Critério opcional
     * @param $chamaObjetoCarregado Se o método carrega() do Registro Ativo deve ser chamado para carregar partes de objetos
     * @return Um array contendo os Registros Ativos
     */
    public static function obtObjetos($criterio = NULL, $chamaObjetoCarregado = TRUE)
    {
        # obtém o nome da classe de Registro Ativo
        $nome_classe = get_called_class();

        # cria o repositório
        $repositorio = new Repositorio($nome_classe);
        if(!$criterio) {
            $criterio = new Criterio;
        }

        return $repositorio->carrega($criterio, $chamaObjetoCarregado);
    }

    /**
     * Método contaObjetos
     * @param $criterio Critério opcional
     * @return Um array contendo os Registros Ativos
     */
    public static function contaObjetos($criterio = NULL)
    {
        # obtém o nome da classe de Registro Ativo
        $nome_classe = get_called_class();

        # cria o repositório
        $repositorio = new Repositorio($nome_classe);
        if(!$criterio) {
            $criterio = new Criterio;
        }

        return $repositorio->conta($criterio);
    }

    /**
     * Carrega objetos compostos (partes em relacionamento composto)
     * @param $classe_composta Classe de Registro Ativo para objetos compostos
     * @param $chave_estrangeira Chave estrangeira em objetos compostos
     * @param $id Chave primária do objeto pai
     * @return Array de Registro Ativo
     */
    public function carregaComposto($classe_composta, $chave_estrangeira, $id = NULL, $ordem = NULL) 
    {
        $cp = $this->obtChavePrimaria();
        $id = $id ? $id : $this->$cp;
        $criterio = Criterio::cria([$chave_estrangeira => $id], ['order' => $ordem]);
        $repositorio = new Repositorio($classe_composta);
        return $repositorio->carrega($criterio);
    }

    /**
     * Carrega objetos compostos. Atalho para carregaComposto
     * @param $classe_composta Classe de Registro Ativo para objetos compostos
     * @param $chave_estrangeira Chave estrangeira em objetos compostos
     * @param $id Chave primária do objeto pai
     * @return Array de Registro Ativo
     */
    public function hasMany($classe_composta, $chave_estrangeira, $id = NULL, $ordem = NULL)
    {
        $chave_estrangeira = $chave_estrangeira ?? $this->undercoreFromCamelCase(get_class($this)) . '_id';
        $chave_primaria = $chave_primaria ?? $this->obtChavePrimaria();
        return $this->carregaComposto($classe_composta, $chave_estrangeira, $this->$chave_primaria, $ordem);
    }

    /**
     * Cria um critério para carregar objetos compostos
     * @param $classe_composta Classe de Registro Ativo para objetos compostos
     * @param $chave_estrangeira Chave estrangeira em objetos compostos
     * @param $id Chave primária do objeto pai
     * @return Instância de Repositorio
     */
    public function filterMany($classe_composta, $chave_estrangeira, $id = NULL, $ordem = NULL) 
    {
        $chave_estrangeira = $chave_estrangeira ?? $this->undercoreFromCamelCase(get_class($this)) . '_id';
        $chave_primaria = $chave_primaria ?? $this->obtChavePrimaria();

        $criterio = Criterio::cria([$chave_estrangeira => $id], ['order' => $ordem]);
        $repositorio = new Repositorio($classe_composta);
        $repositorio->defCriterio($criterio);
        return $repositorio;
    }

    /**
     * Exclui objetos compostos (partes em relacionamento composto)
     * @param $classe_composta Classe de Registro Ativo para objetos compostos
     * @param $chave_estrangeira Chave estrangeira em objetos compostos
     * @param $id Chave primária do objeto pai
     */
    public function excluiComposto($classe_composta, $chave_estrangeira, $id = NULL, $chamaObjetoCarregado = NULL) 
    {
        $criterio = new Criterio;
        $criterio->adic(new Filtro($chave_estrangeira, '=', $id));

        $repositorio = new Repositorio($classe_composta);
        return $repositorio->apaga($criterio, $chamaObjetoCarregado);
    }

    /**
     * Salva objetos compostos (partes em relacionamento composto)
     * @param $classe_composta Classe de Registro Ativo para objetos compostos
     * @param $chave_estrangeira Chave estrangeira em objetos compostos
     * @param $id Chave primária do objeto pai
     * @param $objetos Array de Registros Ativos a serem salvos
     */
    public function salvaComposto($classe_composta, $chave_estrangeira, $id = NULL, $objetos, $chamaObjetoCarregado = NULL) 
    {
        $this->excluiComposto($classe_composta, $chave_estrangeira, $id, $chamaObjetoCarregado);

        if ($objetos) {
            foreach($objetos as $objeto) {
                $objeto->$chave_estrangeira = $id;
                $objeto->grava();
            }
        }
    }

    /**
     * Retorna o primeiro objeto
     */
    public static function primeiro(){
        $objeto = new static;
        $id = $objeto->obtPrimeiroId();

        return self::localiza($id);
    }

    /**
     * Retorna o último objeto
     */
    public static function ultimo(){
        $objeto = new static;
        $id = $objeto->obtUltimoId();

        return self::localiza($id);
    }

    /**
     * Localiza um Registro Ativo e o retorna
     * @return O próprio Registro Ativo ou NUL quando não encontrado
     */
    public function localiza($id) {
        $nomeclasse = get_called_class();
        $ra = new $nomeclasse;
        return $ra->carrega($id);
    }

    /**
     * Retorna todos os objetos
     */
    public static function todos($indexado = FALSE) {
        $objetos = self::obtObjetos(NULL, FALSE);

        if ($indexado) {
            $lista = [];
            foreach($objetos as $objeto) {
                $cp = $objeto->obtChavePrimaria();
                $lista[$objeto->$cp] = $objeto;
            }
            return $lista;
        } else {
            return $objetos;
        }
    }

    /**
     * Salva o objeto
     */
    public function salva(){
        $this->grava();
    }
}
