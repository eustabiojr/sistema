<?php
/***************************************************************************************
 * sistema Novo
 * 
 * Data: 21/01/2022
 ***************************************************************************************/

# Espaço de nomes
namespace Estrutura\BancoDados;

use Estrutura\Nucleo\NucleoTradutor;
use PDO;
use Exception;
use ReflectionMethod;

/**
 * Implementa o padrão repositório para lidar com coleções de Registro Ativo
 * 
 * @version 1.0
 * @package bancodados
 */
class Repositorio {
    protected $classe; # Classe de Registro Ativo a ser manipulada
    protected $criterio; # Critério amortecido para ser usado com interfaces fluente
    protected $grupoValores; 
    protected $colunas; 

    /**
     * Contrutor da classe
     * @param $classe = Nome da classe de Registro Ativo
     */
    public function __construct($classe)
    {
        if (class_exists($classe)) {
            if (is_subclass_of($classe, 'Gravacao')) {
                $this->classe = $classe;
                $this->criterio = new Criterio;
            } else {
                throw new Exception(NucleoTradutor::traduz('A classe &1 não aceita como argumento. A classe informada deve ser subclasse de &2.', $classe, 'Gravacao'));
            }
        } else {
            throw new Exception(NucleoTradutor::traduz('A classe &1 não encontrada. Verifique o nome da classe ou do arquivo. Eles devem coincidir','"' . $classe . '"'));
        }
    }

    /**
    * Define um criterio de seleção
    * @param $criterio Um objeto Criterio, especificando os filtros 
    */
    public function defCriterio(Criterio $criterio)
    {
        $this->criterio = $criterio;
    }

    /**
     * Retorna o nome da entidade de banco de dados
     * @return Uma string contendo o nome da entidade
     */
    protected function obtEntidade()
    {
        return constant($this->classe.'::NOMETABELA');
    }

    /**
     * Obtém a lista de atributos da entidade
     */
    protected function obtListaAtributos()
    {
        if (!empty($this->colunas)) {
            return implode(', ', $this->colunas);
        } else {
            $objeto = new $this->classe;
            return $objeto->obtListaAtributos();
        }
    }

    /**
     * Define lista de colunas
     */
    public function seleciona($colunas)
    {
        $this->colunas = $colunas;
        return $this;
    }

    /**
     * Adiciona critério em tempo de execução usando interfaces fluente
     * 
     * @param $variavel = variavel
     * @param $operador = operador de comparação (>, <, =)
     * @param $valor = valor a ser comparado
     * @param $operadorLogico = operador lógico (Expressao::OPERADOR_E, Expressao::OPERADOR_OU)
     * @return Um objeto Repositorio
     */
    public function onde($variavel, $operador, $valor, $operadorLogico = Expressao::OPERADOR_OU)
    {
        $this->criterio->adic(new Filtro($variavel, $operador, $valor), $operadorLogico);

        return $this;
    }

    /**
     * Adiciona critério em tempo de execução usando interfaces fluente
     * 
     * Atribui valores para as colunas do banco de dados
     * @param $coluna O nome da coluna do banco de dados
     * @param $valor O valor para a coluna do banco de dados
     * @return Um objeto Repositorio
     */
    public function def($coluna, $valor) 
    {
        if (is_scalar($valor) OR is_null($valor)) {
            $this->defValores[$coluna] = $valor;
        }

        return $this;
    }

    /**
     * Adiciona em tempo de execução critério OU usando interfaces fluente
     * 
     * @param $variavel = variavel
     * @param $operador = operador de comparação (>, <, =)
     * @param $valor = valor a ser comparado
     * @return Um objeto Repositorio
     */
    public function ouOnde($variavel, $operador, $valor)
    {
        $this->criterio->adic(new Filtro($variavel, $operador, $valor), Expressao::OPERADOR_OU);

        return $this;
    }

    /**
     * Define a ordenação para critério usando interfaces fluente
     * 
     * @param $ordem - Coluna ordem
     * @param $direcao = Direção ordem (asc, desc)
     * @return Um objeto Repositorio
     */
    public function ordenaPor($ordem, $direcao = 'asc')
    {
        $this->criterio->defPropriedade('order', $ordem);
        $this->criterio->defPropriedade('direction', $direcao);

        return $this;
    }
    
    /**
     * Define o grupo para o critério usando interfaces fluente
     * 
     * @param $grupo Coluna grupo
     * @return Um objeto Repositorio
     */
    public function agrupaPor($grupo)
    {
        $this->criterio->defPropriedade('grupo', $grupo);

        return $this;
    }

    /**
     * Define o critério LIMITE usando interfaces fluente
     * 
     * @param $limite = Limite
     * @return Um objeto Repositorio
     */
    public function levar($limite)
    {
        $this->criterio->defPropriedade('limit', $limite);

        return $this;
    }

    /**
     * Define o critério DESLOCAMENTO usando interfaces fluente
     * 
     * @param $limite = Limite
     * @return Um objeto Repositorio
     */
    public function pula($deslocamento)
    {
        $this->criterio->defPropriedade('offset', $deslocamento);

        return $this;
    }

    /**
     * Carrega a coleção    de objetos do banco de dados usando um criterio
     * 
     * @param $criterio     Um objeto criterio, especificando filtros
     * @param $chamaObjetoCarregado Se método carrega() do Registro Ativo deve ser chamado para carregar partes de objeto
     * @return              Um array contendo o Registro Ativo
     */
    public function carrega(Criterio $criterio = NULL, $chamaObjetoCarregado = TRUE)
    {
        if(!$criterio) {
            $criterio = isset($this->criterio) ? $this->criterio : new Criterio;
        }

        # cria uma declaração SELECT
        $sql = new SqlSelect;
        $sql->adicColuna($this->obtListaAtributos());
        $sql->defEntidade($this->obtEntidade());
        # atribui o critério para a declaração SELECT
        $sql->defCriterio($criterio);

        # obtém a conexão da transação ativa
        if($cnx = Transacao::obt()) {
            # registra a operação no arquivo de histórico
            Transacao::hist($sql->obtInstrucao());
            $bdinfo = Transacao::obtInfoBancodados();
            if (isset($bdinfo['prep']) AND $bdinfo['prep'] == '1') {
                $resulado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resulado->execute($criterio->obtVarsPreparadas());
            } else {
                # executa a consulta
                $resulado = $cnx->query($sql->obtInstrucao());
            }
            $resultados = array();

            $classe = $this->classe;
            $chamadevolta = array($classe, 'carrega'); 

            # Descobre se carrega() está sobrecarregado
            $rm = new ReflectionMethod($classe,$chamadevolta[1]);

            if ($resulado) {
                # itera os resultados como objetos
                while ($cru = $resulado->fetchObject()) {
                    $objeto = new $this->classe;
                    if(method_exists($objeto, 'noPosCarregarColecao')) {
                        $objeto->noPosCarregarColecao($cru);
                    }
                    $objeto->doArray((array) $cru);

                    if ($chamaObjetoCarregado) {
                        # recarrega o objeto devido seu método carrega() pode está sobrecarregado
                        if ($rm->getDeclaringClass()->getName() !== 'Novo\Bancodados\Gravacao') {
                            $objeto->recarrega(); ###
                        }
                    }

                    if (($cache = $objeto->obtControleCache()) && empty($this->colunas)) {
                        $cp = $objeto->obtChavePrimaria();
                        $chave_registro = $classe . '[' . $objeto->$cp . ']';
                        if ($cache::defValor($chave_registro, $objeto->paraArray())) {
                            Transacao::hist($chave_registro . ' gravado no cache');
                        }
                    }
                    # armazena o objeto no array $resultados
                    $resultados[] = $resulado;
                }
            }
            return $resultados;
        } else {
            # caso não exista nenhuma transação ativa aberta
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Carrega sem agrepados
     */
    public function carregaEstatico()
    {
        return $this->carrega(null, false);
    }

    public function obtArrayIndexado($colunaIndice, $valorColuna = NULL, $criterio = NULL)
    {
        if(is_null($valorColuna)) {
            $valorColuna = $colunaIndice;
        }

        $criterio = (empty($criterio)) ? $this->criterio : $criterio;
        $objetos = $this->carrega($criterio, false);

        $arrayIndexado = array();
        if ($objetos) {
            foreach($objetos as $objeto) {
                $chave = (isset($objeto->$colunaIndice)) ? $objeto->$colunaIndice : $objeto->renderiza($colunaIndice);
                $valor = (isset($objeto->$valorColuna)) ? $objeto->$valorColuna : $objeto->renderiza($valorColuna);

                $arrayIndexado[$chave] = $valor;
            }
        }

        if (empty($criterio) or ($criterio instanceof Criterio and empty($criterio->obtPropriedade('order')))){
            asort($arrayIndexado);
        }
        return $arrayIndexado;
    }

    /**
     * Atualiza a coleção de Registro Ativo do banco de dados
     * @param $criterio O objeto Criterio, especificando filtros
     * @return As linhas afetadas
     */
    public function atualiza($defValores = null, Criterio $criterio = null)
    {
        if (!$criterio) {
            $criterio = isset($this->criterio) ? $this->criterio : new Criterio;
        }
        $defValores = isset($defValores) ? $defValores : $this->defValores;

        $classe = $this->classe;

        # obtém a conexão da transação ativa
        if ($cnx = Transacao::obt()) {
            $infobd = Transacao::obtInfoBancodados(); 

            # cria uma declaração UPDATE
            $sql = new SqlUpdate;
            if ($defValores) {
                foreach($defValores as $coluna => $valor) {
                    $sql->defDadosLinha($coluna, $valor);
                }
            }
            $sql->defEntidade($this->obtEntidade());
            
            # atribui o critério à declaração UPDATE
            $sql->defCriterio($criterio);

            if(isset($infobd['prep']) AND $infobd['prep'] == '1') {
                $declaracao = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resultado = $declaracao->execute($sql->obtVarsPreparadas());
            } else {
                # executa a declaração UPDATE
                $resultado = $cnx->exec($sql->obtInstrucao());
            }

            # registra a operação no arquivo de HISTÓRICO
            Transacao::hist($sql->obtInstrucao());

            # atualiza cache
            $gravacao = new $classe;
            if ($cache = $gravacao->obtControleCache()) {
                $cp = $gravacao->obtChavePrimaria();

                # cria uma declaração SELECT
                $sql = new SqlSelect;
                $sql->adicColuna($this->obtListaAtributos());
                $sql->defEntidade($this->obtEntidade());

                # atribui critério à declaração SELECT
                $sql->defCriterio($criterio);

                if (isset($infobd['prep']) AND $infobd['prep'] == '1') {
                    $subresultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                    $subresultado->execute($criterio->obtVarsPreparadas());  
                } else {
                    $subresultado = $cnx->query($sql->obtInstrucao());
                }

                if($subresultado) {
                    # itera os resultados do objeto
                    while ($cru = $subresultado->fetchObject()) {
                        $objeto = new $this->classe;
                        $objeto->doArray((array) $cru);

                        $chave_gravacao = $classe . '[' . $cru->$cp . ']';
                        if ($cache::defValor($chave_gravacao, $objeto->paraArray())) {
                            Transacao::hist($chave_gravacao . ' gravado no cache');
                        }
                    }
                }
            }

            return $resultado;
        } else {
            # caso não exista nenhuma transação ativa aberta
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Apaga a coleção de Registro Ativo do banco de dados
     * @param $criterio O objeto Criterio, especificando filtros
     * @return As linhas afetadas
     */
    public function apaga(Criterio $criterio = null, $chamaObjetoCarregado = false) 
    {
        if (!$criterio) {
            $criterio = isset($this->criterio) ? $this->criterio : new Criterio;
        }
        $classe = $this->classe;

        # obtém a conexão da transação ativa
        if ($cnx = Transacao::obt()) {
            $infobd = Transacao::obtInfoBancodados();
                    
            # primeiro limpamos o cache
            $gravacao = new $classe;
            if (($cache = $gravacao->obtControleCache()) OR $chamaObjetoCarregado) {
                $cp = $gravacao->obtChavePrimaria();

                # cria uma declaração SELECT
                $sql = new SqlSelect;
                $sql->adicColuna($cp);
                $sql->defEntidade($this->obtEntidade());

                # atribui o critério à declaração SELECT
                $sql->defCriterio($criterio);

                if(isset($infobd['prep']) AND $infobd['prep'] == '1') {
                    $resultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                    $resultado->execute($criterio->obtVarsPreparadas());
                } else {
                    $resultado = $cnx->query($sql->obtInstrucao());
                }

                if($resultado) {
                    # itera os resultados como objetos
                    while ($linha = $resultado->fetchObject()) {
                        if($cache) {
                            $chave_gravacao = $classe . '[' . $linha->$cp . ']';
                            if ($cache::defValor($chave_gravacao)) {
                                Transacao::hist($chave_gravacao . ' apagado no cache');
                            }
                        }

                        if ($chamaObjetoCarregado) {
                            $objeto = new $this->classe;
                            $objeto->doArray((array) $linha);
                            $objeto->delete();
                        }
                    }
                }
            }

            # cria uma declração DELETE
            $sql = new SqlDelete;
            $sql->defEntidade($this->obtEntidade());

            # atribui o critério à declaração DELETE
            $sql->defCriterio($criterio);

            if(isset($infobd['prep']) AND $infobd['prep'] == '1') {
                $resultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resultado->execute($criterio->obtVarsPreparadas());
            } else {
                $resultado = $cnx->exec($sql->obtInstrucao());
            }

            # registra a operação no arquivo de HISTÓRICO
            Transacao::hist(($sql->obtInstrucao()));

            return $resultado;
        } else {
            # caso não exista nenhuma transação ativa aberta
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Retorna o montante de objetos que satisfazem o critério fornecido
     * @param $criterio O objeto Criterio, especificando filtros
     * @return As linhas afetadas
     */
    public function conta(Criterio $criterio = NULL) {
        if(!$criterio) {
            $criterio = isset($this->criterio) ? $this->criterio : new Criterio;
        }

        # cria um declaração SELECT
        $sql = new SqlSelect;
        $sql->adicColuna('count(*)');
        $sql->defEntidade($this->obtEntidade());

        # atribui o critério para a declaração SELECT
        $sql->defCriterio($criterio);

        if ($cnx = Transacao::obt()) {
            $infobd = Transacao::obtInfoBancodados(); 

            $infobd = Transacao::obtInfoBancodados();
            if(isset($infobd['prep']) AND $infobd['prep'] == '1') {
                $resultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resultado->execute($criterio->obtVarsPreparadas());
            } else {
                # executa a declaração UPDATE
                $resultado = $cnx->query($sql->obtInstrucao());
            }

            if ($resultado) {
                $linha = $resultado->fetch();
                return $linha[0];
            }
        } else {
            # caso não exista nenhuma transação ativa aberta
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Conta agregado distintamente
     * @param $coluna Coluna a ser agregada
     * @return Um array de objetos ou o valor total (se não tiver 'group by')
     */
    public function contaDistintoPor($coluna, $apelido = null) 
    {
        $apelido = is_null($apelido) ? $coluna : $apelido;
        return $this->agregar('count', 'distinct ' . $coluna, $apelido);
    }

    /**
     * Apelido para contaDistintoPor
     */
    public function countDistinctBy($coluna, $apelido = null) 
    {
        $this->contaDistintoPor($coluna, $apelido);
    }

    /**
     * Conta agregado
     * @param $coluna Coluna a ser agregada
     * @return Um array de objetos ou o valor total (se não tiver 'group by')
     */
    public function contaPor($coluna, $apelido = null) 
    {
        return $this->agregar('count', $coluna, $apelido);
    }

    /**
     * Soma agregado
     * @param $coluna Coluna a ser agregada
     * @return Um array de objetos ou o valor total (se não tiver 'group by')
     */
    public function somaPor($coluna, $apelido = null) 
    {
        return $this->agregar('sum', $coluna, $apelido);
    }

    /**
     * Agregado médio
     * @param $coluna Coluna a ser agregada
     * @return Um array de objetos ou o valor total (se não tiver 'group by')
     */
    public function mediaPor($coluna, $apelido = null) 
    {
        return $this->agregar('avg', $coluna, $apelido);
    }

    /**
     * Agregado mínimo
     * @param $coluna Coluna a ser agregada
     * @return Um array de objetos ou o valor total (se não tiver 'group by')
     */
    public function minPor($coluna, $apelido = null) 
    {
        return $this->agregar('min', $coluna, $apelido);
    }

    /**
     * Agregado máximo
     * @param $coluna Coluna a ser agregada
     * @return Um array de objetos ou o valor total (se não tiver 'group by')
     */
    public function maxPor($coluna, $apelido = null) 
    {
        return $this->agregar('max', $coluna, $apelido);
    }

    /**
     * Coluna agregada
     * @param $function função agregada (count, sum, min, max, avg)
     * @return Um array de objetos ou o valor total (se não tiver 'group by')
     */
    protected function agregar($funcao, $coluna, $apelido = null)
    {
        $criterio = isset($this->criterio) ? $this->criterio : new Criterio;
        $apelido = $apelido ? $apelido : $coluna;

        # cria uma declaração SELECT
        $sql = new SqlSelect;
        if (!empty($this->criterio->obtPropriedade('group'))) {
            $sql->adicColuna($this->criterio->obtPropriedade('group'));
        }
        $sql->adicColuna("$funcao($coluna) as \"{$apelido}\"");
        $sql->defEntidade($this->obtEntidade());

        # atribui o critério à declaração SELECT 
        $sql->defCriterio($criterio);

        # obtém a conexão da transação ativa
        if ($cnx = Transacao::obt()) {
            # registra a operação no arquivo de histórico (LOG)
            Transacao::hist($sql->obtInstrucao());

            $infobd = Transacao::obtInfoBancodados();
            if(isset($infobd['prep']) AND $infobd['prep'] == '1') {
                $resultado = $cnx->prepare($sql->obtInstrucao(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $resultado->execute($criterio->obtVarsPreparadas());
            } else {
                # executa a declaração UPDATE
                $resultado = $cnx->query($sql->obtInstrucao());
            }

            $resultados = [];

            if ($resultado) {
                # itera os resultados como objetos
                while ($cru = $resultado->fetchObject()) {
                    $resultados[] = $cru;
                }
            }

            if ($resultados) {
                if ((count($resultados) > 1) || !empty($this->criterio->obtPropriedade('group'))) {
                    return $resultados;
                } else {
                    return $resultados[0]->$apelido;
                }
            }

            return 0;
        } else {
            # caso não exista nenhuma transação ativa aberta
            throw new Exception(NucleoTradutor::traduz('Não há transações ativas com o banco de dados') . ': ' . __METHOD__ . ' ' . $this->obtEntidade());
        }
    }

    /**
     * Apelido para carrega()
     */
    public function obt(Criterio $criterio = null, $chamaCarregaObjeto = TRUE)
    {
        return $this->carrega($criterio,$chamaCarregaObjeto);
    }

    /**
     * Apelido para carrega()
     */
    public function get(Criterio $criterio = null, $chamaCarregaObjeto = TRUE)
    {
        return $this->carrega($criterio,$chamaCarregaObjeto);
    }

    /**
     * Retorna o primero item da coleção
     */
    public function primeiro($chamaCarregaObjeto = TRUE) 
    {
        $colecao = $this->levar(1)->carrega(null, $chamaCarregaObjeto);
        if (isset($colecao[0])) {
            return $colecao[0];
        }
    }

    /**
     * Retorna a coleção transformada
     */
    public function transforma(Callable $chamadevolta, $chamaCarregaObjeto = TRUE)
    {
        $colecao = $this->carrega(null, $chamaCarregaObjeto);

        if ($colecao) {
            foreach($colecao as $objeto) {
                call_user_func($chamadevolta, $objeto);
            }
        }

        return $colecao;
    }

    /**
     * Retorna a coleção filtrada
     */
    public function filtro(Callable $chamadevolta, $chamaCarregaObjeto = TRUE)
    {
        $colecao = $this->carrega(null, $chamaCarregaObjeto);

        if ($colecao) {
            foreach($colecao as $objeto) {
                if(call_user_func($chamadevolta, $objeto)) {
                    $nova_colecao[] = $objeto;
                }
            }
        }

        return $nova_colecao;
    }
}
