<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 06/05/2021
 ********************************************************************************************/
# Espaço de nomes
namespace Estrutura\Bugigangas\Embalagem;

use Estrutura\BancoDados\Criterio;
use Estrutura\BancoDados\Transacao;
use Estrutura\Base\BuscaPadrao;
use Estrutura\Bugigangas\Form\BotaoBusca;
use Estrutura\Controle\Acao;
use Estrutura\Nucleo\ConfigAplicativo;
use Exception;

/**
 * Abstract Record Lookup Widget: Creates a lookup field used to search values from associated entities
 *
 * @version    7.1
 * @package    widget
 * @subpackage wrapper
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BDBotaoBusca extends BotaoBusca
{
    /**
     * Class Constructor
     * @param  $name name of the form field
     * @param  $bancodedados name of the database connection
     * @param  $form name of the parent form
     * @param  $modelo name of the Active Record to be searched
     * @param  $campo_exibe name of the field to be searched and shown
     * @param  $chave_receptor name of the form field to receive the primary key
     * @param  $receptor_campo_exibe name of the form field to receive the "display field"
     */
    public function __construct($name, $bancodedados, $form, $modelo, $campo_exibe, $chave_receptor = null, $receptor_campo_exibe = null, Criterio $criterio = NULL, $operador = 'like')
    {
        parent::__construct($name);
        
        if (empty($bancodedados))
        {
            throw new Exception("O parâmetro (bancodedados) de {__CLASS__} é obrigatório"); 
        }
        
        if (empty($modelo))
        {
            throw new Exception("O parâmetro (modelo) de {__CLASS__} é obrigatório"); 
        }
        
        if (empty($campo_exibe))
        {
            throw new Exception("O parâmetro (campo_exibe) de {__CLASS__} é obrigatório");
        }
        
        $obj  = new BuscaPadrao;
        $ini  = ConfigAplicativo::obt();
        $seed = NOME_APLICATIVO . ( !empty($ini['general']['seed']) ? $ini['general']['seed'] : 's8dkld83kf73kf094' );
        
        // define the action parameters
        $acao = new Acao(array($obj, 'onSetup'));
        $acao->defParametro('hash',          md5("{$seed}{$bancodedados}{$modelo}{$campo_exibe}"));
        $acao->defParametro('database',      $bancodedados);
        $acao->defParametro('parent',        $form);
        $acao->defParametro('model',         $modelo);
        $acao->defParametro('display_field', $campo_exibe);
        $acao->defParametro('receive_key',   !empty($chave_receptor) ? $chave_receptor : $name);
        $acao->defParametro('receive_field', !empty($receptor_campo_exibe) ? $receptor_campo_exibe : null); 
        $acao->defParametro('criteria',      base64_encode(serialize($criterio)));
        $acao->defParametro('operator',      ($operador == 'ilike') ? 'ilike' : 'like');
        $acao->defParametro('mask',          '');
        $acao->defParametro('label',         'Descrição');
        parent::defAcao($acao);
    }
    
    /**
     * Set search criteria
     */
    public function defCriterio(Criterio $criterio)
    {
        $this->obtAcao()->defParametro('criteria', base64_encode(serialize($criterio)));
    }
    
    /**
     * Set operator
     */
    public function defOperador($operador)
    {
        $this->obtAcao()->defParametro('operator', ($operador == 'ilike') ? 'ilike' : 'like');
    }
    
    /**
     * Set display mask
     * @param $mascara Display mask
     */
    public function defMascaraDisplay($mascara)
    {
        $this->obtAcao()->defParametro('mask', $mascara);
    }
    
    /**
     * Set display label
     * @param $mascara Display label
     */
    public function defRotuloDisplay($rotulo)
    {
        $this->obtAcao()->defParametro('label', $rotulo);
    }
    
    /**
     * Define the field's value
     * @param $valor Current value
     */
    public function defValor($valor)
    {
        parent::defValor($valor);
        
        if (!empty($this->auxiliar))
        {
            $bancodedados = $this->obtAcao()->obtParametro('database');
            $modelo       = $this->obtAcao()->obtParametro('model');
            $mascara      = $this->obtAcao()->obtParametro('mask');
            $campo_exibe  = $this->obtAcao()->obtParametro('display_field');
            
            if (!empty($valor))
            {
                Transacao::abre($bancodedados);
                $activeRecord = new $modelo($valor);
                
                if (!empty($mascara))
                {
                    $this->auxiliar->defValor($activeRecord->render($mascara));
                }
                else if (isset($activeRecord->$campo_exibe))
                {
                    $this->auxiliar->defValor( $activeRecord->$campo_exibe );
                }
                Transacao::fecha();
            }
        }
    }
}
