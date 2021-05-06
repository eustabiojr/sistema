<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 02/05/2021
 ********************************************************************************************/
# espaço de nomes
namespace Estrutura\Bugigangas\Base;

/**
 * Classe GElemento
 */
class Elemento
{
	private $nometag;
	private $propriedades;
	private $usaQuebraLinha;
	private $usaAspasSimples;
	private $elementoPosterior;
	protected $filhos;
	private $embalado;
	private static $elementos_vazios;
	private $oculto;


	/**
	* Método Construtor
	*/
	public function __construct($nometag)
	{
		$this->nometag 		   = $nometag;
		$this->usaQuebraLinha  = TRUE;
		$this->usaAspasSimples = FALSE;
		$this->embalado		   = FALSE;
		$this->propriedades	   = [];
		$this->oculto		   = FALSE;

		if (empty(self::$elementos_vazios))
        {
            self::$elementos_vazios = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr','img', 'input', 
            								'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr');
        }
	}

	/**
	* Método tag
	* 
	* Cria uma tag html com seus respectivos atributos a a retorna
	*/
	public static function tag($nome_tag, $valor, $atributos = NULL)
	{
		$objeto = new Elemento($nome_tag);

		if(is_array($valor)) {
			foreach ($valor as $elemento) {
				$objeto->adic($elemento);
			}
		} else {
			$objeto->adic($valor);
		}

		if($atributos) {
			foreach ($atributos as $atrib_nome => $atrib_valor) {
				$objeto->$atrib_nome = $atrib_valor;
			}
		}

		return $objeto;
	}

	/**
	* Oculta objeto
	*/
	public function oculta()
	{
		$this->oculto = true;
	}

	/**
	 * Insere elemento posterior
	 */
	public function posterior($elemento)
	{
		$this->elementoPosterior = $elemento;
	}

	/**
	 * Retorna o elemento posterior
	 */
	public function obtElementoPosterior()
	{
		return $this->elementoPosterior;
	}

	/**
	* Método defNome
	*/
	public function defNome($nome)
	{
		$this->nometag = $nome;
	}

	/**
	* Método obtNome
	*/
	public function obtNome()
	{
		return $this->nometag;
	}

	/**
	* Define se o objeto está embalado dentro de outro
	*
	* $param $bool é TRUE se estiver embalado
	*/
	public function defEstaEmbalado($bool)
	{
		$this->embalado = $bool;
	}

	/**
	* Retorna se o objeto está embalado dentro de outro
	*/
	public function obtEstaEmbalado()
	{
		return $this->embalado;
	}

	/**
	* Método defPropriedade
	* @param nome da propriedade
	* @param valor da propriedade
	*/
	public function defPropriedade($nome, $valor)
	{
		if(is_scalar($valor)) {
			$this->propriedades[$nome] = $valor;
		}
	}

	/**
	* Método obtPropriedade
	* @param nome da propriedade
	*/
	public function obtPropriedade($nome)
	{
		return $this->propriedades[$nome] ?? null;
	}

	/**
	* Método defPropriedade
	* returna propriedades do elemento
	*/
	public function obtPropriedades()
	{
		return $this->propriedades;
	}

	/**
	* Método defPropriedade - Intercepta sempre que alguém atribuir um novo valor de propriedade
	*
	* @param nome da propriedade
	* @param valor da propriedade
	*/
	public function __set($nome, $valor)
	{
		if(is_scalar($valor)) {
			$this->propriedades[$nome] = $valor;
		}
	}

	/**
	* Intercepta sempre que alguém redefinir uma valor de propriedade
	* 
	* @param nome da propriedade
	*/
	public function __unset($nome) 
	{
		unset($this->propriedades[$nome]);
	}

	/**
	* Método obtPropriedade - Retorna o valor da propriedade
	* 
	* @param nome da propriedade
	*/
	public function __get($nome)
	{
		if (isset($this->propriedades[$nome])) {
			return $this->propriedades[$nome];
		}
	}

	/**
	* Retorna se a propriedade está definida ou não
	* 
	* @param nome da propriedade
	*/
	public function __isset($nome) 
	{
		return isset($this->propriedades[$nome]);
	}

	/**
	 * Duplica o objeto
	 */
	public function __clone()
	{
		# verifica se a tag possui elementos filhos
		if ($this->filhos) {
			# itera todos os elementos filhos
			foreach ($this->filhos as $chave => $filho) {
				if(is_object($filho)) {
					$this->filhos[$chave] = clone $filho;
				} else {
					$this->filhos[$chave] = $filho;
				}
			}
		}
	}

	/**
	* Adiciona um filho
	*/
	public function adic($filho) 
	{
		$this->filhos[] = $filho;
		if ($filho instanceof Elemento) {
			$this->defEstaEmbalado(TRUE);
		}
	}

	/**
	* Define o uso de quebra de linha
	* @param $quebralinha booleano
	*/
	public function defUsaQuebraLinha($quebralinha)
	{
		$this->usaQuebraLinha = $quebralinha;
	}

	/**
	* Define o uso de aspas simples
	* @param $aspas_simples booleano
	*/
	public function defUsaAspasSimples($aspas_simples)
	{
		$this->usaAspasSimples = $aspas_simples;
	}

	/**
	* Insere um elemento filho
	*
	* @param $posicao posição do elemento
	* @param $filho Qualquer objeto que implemente o método exibe()
	*/
	public function insere($posicao, $filho)
	{
		array_splice($this->filhos, $posicao, 0, [$filho]);

		if ($filho instanceof Elemento) {
			$filho->defEstaEmbalado(TRUE);
		}
	}
	
	/**
	* Apaga um elemento filho 
	*
	* @param $filho Qualquer objeto que implemente o método exibe()
	*/
	public function apag($objeto)
	{
		foreach ($this->filhos as $chave => $filho) {
			if ($filho === $objeto) {
				unset($this->filhos[$chave]);
			}
		}
	}

	/**
	* Retorna os filhos
	*/
	public function obtFilhos()
	{
		return $this->filhos;
	}

	/**
	* Localiza elemento filho
	* 
	* @param $elemento nome da tag
	* @param $propriedades
	*/
	public function localiza($elemento, $propriedades = NULL)
	{
		if($this->filhos) {
			foreach ($this->filhos as $filho) {
				if ($filho instanceof Elemento) {
					if ($filho->obtNome() == $elemento) {
						$combina = true;
						if ($propriedades) {
							foreach ($propriedades as $chave => $valor) {
								if ($filho->obtPropriedade($chave) !== $valor) {
									$combina = false;
								}
							}
						}

						if ($combina) {
							return array_merge([$filho, $filho->localiza($elemento, $propriedades)]);
						}
					}
					return $filho->localiza($elemento, $propriedades);
				}
			}
		}
		return [];
	}
	
	/**
	* Abre a tag
	*/
	public function abre()
	{
		echo "<{$this->nometag}";

		if ($this->propriedades) {
			foreach ($this->propriedades as $nome => $valor) {

				if ($this->usaAspasSimples) {
					$valor = str_replace("'", "&#039;", $valor);
					echo " {$nome}='{$valor}'";
				} else {
					$valor = str_replace('"', "&quot;", $valor);
					echo " {$nome}=\"{$valor}\"";
				}
			}
		}

        # caso a tag não esteja no array, fazemos o fechamento
        if (in_array($this->nometag, self::$elementos_vazios)) {
        	echo '/>';
        } else {
        	echo '>';  	
        }
	}
	/**
	* Método exibe (qual a aplicação prática disso?)
	*/
	public function exibe()
	{
		if ($this->oculto) {
			return;
		}

		# abre a tag
		$this->abre();

		if ($this->filhos) {

			if (count($this->filhos) > 1) {
				if ($this->usaQuebraLinha) {
					echo PHP_EOL;
				}
			}

			foreach ($this->filhos as $filho) {
				
				# verifica se o objeto tem um filho. Caso contrário é um valor escalar ou numérico
				if (is_object($filho)) {
					$filho->exibe();
				} else if (is_scalar($filho) OR is_numeric($filho)) {
					echo $filho;
				}
			}
		}

		if (!in_array($this->nometag, self::$elementos_vazios)) {

			# Fecha a tag
        	$this->fecha();
        }

        # código do elemento posterior (?)
        if (!empty($this->elementoPosterior))
        {
            $this->elementoPosterior->exibe();
        }
	}

	/**
	* Método fecha
	*/
	public function fecha()
	{
		echo "</{$this->nometag}>";
		if ($this->usaQuebraLinha) {
			echo PHP_EOL;
		}
	}

	/**
	* Converte o objeto em uma string
	*/
	public function __toString()
	{
		return $this->obtConteudos();
	}

	/**
	* Retorna o conteúdo do elemento como uma string
	*/
	public function obtConteudos()
	{
		ob_start();
		$this->exibe();
		$conteudo = ob_get_contents();
		ob_end_clean();
		return $conteudo;
	}

	/**
	* Esvazia elemento filhos
	*/
	public function limpaFilhos()
	{
		$this->filhos = [];
	}
}