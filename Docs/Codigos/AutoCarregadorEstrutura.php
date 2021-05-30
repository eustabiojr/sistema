<?php
/**
 * Auto-carregador de classes
 * 
 * Autor: Eustábio Júnior
 * Data: 27/02/2021
 */
//--------------------------------------------------------------------------------------------------- 
namespace Estrutura\Nucleo;

/**
 * Pretendo criar uma variável array para registrar os namespace que serão consultados 
 * durante o carregamento das classes
 */
class AutoCarregadorEstrutura {

    private $prefixos;

    /**
     * Construtor
     * 
     * Lembrete: a função spl_autoload_register só será chamada quando encontrar
     * a tentativa de instanciar uma classe.
     */
    public function registra()
    {
        # echo "<p> Inicializado </p>" . PHP_EOL;
        
        /**
         * Várias funções podem ser carregadas com essa função PHP
         */
        spl_autoload_register(array($this,'carregaClasse'));
    }

	/**
	* Método adicEspacoNome
	* 
	* Em resumo, como se é de esperar esse método apenas cria um array com os espaços de nomes e seu
	* respectivo diretório, ou diretórios. 
	*/
	public function adicEspacoNome($espaco_nome, $pasta) {

		# Aqui retiramos as barras invertidas em ambas as extremidades da string, e em seguida 
		# adicionamos uma barra invertida no final da string. Esse é o namespace
		$espaco_nome = trim($espaco_nome, '\\') . '\\';

		# Antes de definir o array '$this->prefixos[$espaco_nome]' com um array vazio, verificamos
		# se o índice $espaco_nome informado já existe.
		if (isset($this->prefixos[$espaco_nome]) === false) {
			$this->prefixos[$espaco_nome] = [];
		}

		//----------------------------------------------------------------------------------------------------
		# No caso, o separador de diretório deverá ser informado na configuração de acordo com 
		# sistema hospedeiro. No Windows o separador é \, no Linux é /
		$pasta = rtrim($pasta, DIRECTORY_SEPARATOR) . '/';

		# Aqui nós adicionamos um diretório base para o espaço de nome em questão.
		array_push($this->prefixos[$espaco_nome], $pasta);

		return $this->prefixos;
	}

	/**
	* Método carregaClasse
	*
	* Essa classe pega o nome da classe invocada juntamente com o seu espaço de nomes.
	*
	*/
	public function carregaClasse($item) {
		$pfx = $item;

		#echo "<hr/>" . PHP_EOL; 

		/**
		 * No caso, a variável $pfx é reduzida uma a uma da direita para esquerda com base na barra invertida.
		 * A função strrpos() informa a posição da última barra invertida na string informada. Quando não existe
		 * um barra invertda na extremidade direita da string, função substr() com a informação obtida por
		 * strrpos(), anteriormente corta a string até a próxima barra invertida. A cada laço de repetição a barra
		 * invertida da direita da string é removida com pela função rtrim().
		 */
		while (false !== $posicao = strrpos($pfx,'\\')) {
			#echo "<p>@@@@ A string é (esta seria a string da classe chamada): " . $item . "</p>" . PHP_EOL; 

			# Primeiro argumento: a string
			# Segundo argumento: se positivo quantos caracteres serão cortados (corte)
			# Terceiro argumento: quantos caracteres terá a partir do corte.
			$pfx = substr($item, 0, $posicao + 1);
			$classe_relativa  = substr($item, $posicao + 1);
			#echo "<p>A nova string que corresponde ao espaço de nome é: <b>" . $pfx . "</b></p>" . PHP_EOL; 
			#echo "<p>##### A nova string que corresponde a classe é: <b>" . $classe_relativa . "</b></p>" . PHP_EOL; 

			$arquivo_mapeado = $this->carregaArquivoMapeado($pfx, $classe_relativa);

			# Aquí é onde alteramos a variável chave deste laço de repetição. Enquanto houver barra invertda
			# na string, o laço se repetiŕá. 
			$pfx = rtrim($pfx, '\\');
			#echo "<p>A nova string 2 é: <b>" . $pfx . "</b></p><hr/>" . PHP_EOL;
		}
	}

	public function carregaArquivoMapeado($espaconome, $classe_relativa)
	{
		if (isset($this->prefixos[$espaconome]) === false) {
			return false;
		}

		foreach ($this->prefixos[$espaconome] as $pasta_base) {
			#echo "<p>**** Esse é o nosso include: " . $pasta_base . str_replace('\\', '/', $classe_relativa) . '.php' . "</p>" . PHP_EOL; 
			$arquivo = $pasta_base . str_replace('\\', '/', $classe_relativa) . '.php'; 
		}

		$this->solicitaArquivo($arquivo);
	}

	/**
	 * Método solicitaArquivo
	 */
	protected function solicitaArquivo($arquivo)
	{
		if(file_exists($arquivo)) {
			include $arquivo;
			return true;
		} else {
			return false;
		}
	}

    /**
     * Método listaNamespace
     */
    protected function listaNamespace() {
        return $this->espaco_nome;
    }
}

