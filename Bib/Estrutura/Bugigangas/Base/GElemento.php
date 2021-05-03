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
class GElemento
{
	private $nometag;
	private $usaQuebraLinha;
	private $propriedades;
	protected $filhos;
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
		$this->propriedades	   = [];
		$this->oculto		   = FALSE;

		if (empty(self::$elementos_vazios))
        {
            self::$elementos_vazios = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr','img', 'input', 
            								'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr');
        }
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
        if (!in_array($this->nometag, self::$elementos_vazios)) {
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
        #if (!empty($this->afterElement))
        #{
        #    $this->afterElement->show();
        #}
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
}

//--------------------------------------------------------------------------------------------

$e = new GElemento('input');
$e->defPropriedade("type", "text");
$e->id = 'id1';
$e->exibe();
