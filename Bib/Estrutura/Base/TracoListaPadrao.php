<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 22/03/2021
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\Base;

use Estrutura\Base\TracoColecaoPadrao;
use Estrutura\Bugigangas\Base\Elemento;
use Estrutura\BancoDados\Transacao;
use Estrutura\Bugigangas\Dialogo\Mensagem;
use Estrutura\Bugigangas\Dialogo\Pergunta;
use Estrutura\Controle\Acao;
use Estrutura\Controle\Janela;
use Estrutura\Controle\Pagina;

use Exception;
use DomDocument;
use Dompdf\Dompdf;
/**
 * Standard List Trait
 *
 * @version    7.1
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
trait TracoListaPadrao
{
    protected $totalRow;
    
    use TracoColecaoPadrao;
    
    /**
     * Enable total row
     */
    public function habilitaTotalLinha()
    {
        $this->setAfterLoadCallback( function($gradedados, $informacao) {
            $rodapet = new Elemento('tfoot');
            $rodapet->{'class'} = 'tgradedados_footer';
            $linha = new Elemento('tr');
            $rodapet->adic($linha);
            $gradedados->adic($rodapet);
            
            $linha->{'style'} = 'height: 30px';
            $celula = new Elemento('td');
            $celula->adic( $informacao['count'] . ' ' . 'Records');
            $celula->{'colspan'} = $gradedados->obtTotalColunas();
            $celula->{'style'} = 'text-align:center';
            
            $linha->adic($celula);
        });
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function aoEditarEmLinha($param) 
    {
        try {
            // get the parameter $chave
            $campo = $param['campo'];
            $chave = $param['chave'];
            $valor = $param['valor'];
            
            // open a transaction with bancodados
            Transacao::abre($this->bancodados);
            
            // instantiates object {ACTIVE_RECORD}
            $classe = $this->registroAtivo;
            
            // instantiates object
            $objeto = new $classe($chave);
            
            // deletes the object from the bancodados
            $objeto->{$campo} = $valor;
            $objeto->store();
            
            // close the transaction
            Transacao::fecha();
            
            // reload the listing
            $this->aoRecarregar($param);
            // shows the success message
            new Mensagem('info', 'Registro atualizado');
        }
        catch (Exception $e) { // in case of exception
            // shows the exception error message
            new Mensagem('erro', $e->getMessage());
            // undo all pending operations
            Transacao::desfaz();
        }
    }
    
    /**
     * Ask before delete record collection
     */
    public function aoApagarColecao( $param ) 
    {
        $dados = $this->formgrid->obtDados(); // get selected records from gradedados
        $this->formgrid->setData($dados); // keep form filled
        
        if ($dados) {
            $selecionado = array();
            
            // get the record id's
            foreach ($dados as $indice => $verificacao) {
                if ($verificacao == 'on') {
                    $selecionado[] = substr($indice,5);
                }
            }
            
            if ($selecionado) {
                // encode record id's as json
                $param['selected'] = json_encode($selecionado);
                
                // define the delete action
                $acao = new Acao(array($this, 'apagaColecao'));
                $acao->defParametros($param); // pass the key parameter ahead
                
                // shows a dialog to the user
                new Pergunta('Você quer apagar realmente?', $acao);
            }
        }
    }
    
    /**
     * method apagaColecao()
     * Delete many records
     */
    public function apagaColecao($param) 
    {
        // decode json with record id's
        $selecionado = json_decode($param['selected']);
        
        try {
            Transacao::abre($this->bancodados);
            if ($selecionado) {
                // delete each record from collection
                foreach ($selecionado as $id) {
                    $classe = $this->registroAtivo;
                    $objeto = new $classe;
                    $objeto->delete( $id );
                }
                $posAction = new Acao(array($this, 'aoRecarregar'));
                $posAction->defParametros( $param );
                new Mensagem('info', 'Registros apagados');
            }
            Transacao::fecha();
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
            Transacao::desfaz();
        }
    }
    
    /**
     * Export to CSV
     * @param $saida Output file
     */
    public function exportaParaCSV($saida)
    {
        $this->limite = 0;
        $objetos = $this->aoRecarregar();
        
        if ( (!file_exists($saida) && is_writable(dirname($saida))) OR is_writable($saida))
        {
            Transacao::abre($this->bancodados);
            $tratador = fopen($saida, 'w');
            if ($objetos)
            {
                foreach ($objetos as $objeto) {
                    $linha = [];
                    foreach ($this->gradedados->obtColunas() as $coluna) {
                        $nome_coluna = $coluna->obtNome();
                        
                        if (isset($objeto->$nome_coluna)) {
                            $linha[] = is_scalar($objeto->$nome_coluna) ? $objeto->$nome_coluna : '';
                        } else if (method_exists($objeto, 'render')) {
                            $linha[] = $objeto->renderiza($nome_coluna);
                        }
                    }
                    
                    fputcsv($tratador, $linha);
                }
            }
            fclose($tratador);
            Transacao::fecha();
        }
        else
        {
            throw new Exception('Permissão negada' . ': ' . $saida);
        }
    }
    
    /**
     * Export to XML
     * @param $saida Output file
     */
    public function exportaParaXML($saida)
    {
        $this->limite = 0;
        $objetos = $this->aoRecarregar();
        
        if ( (!file_exists($saida) && is_writable(dirname($saida))) OR is_writable($saida))
        {
            Transacao::abre($this->bancodados);
            
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->{'formatOutput'} = true;
            $dadosset = $dom->appendChild( $dom->createElement('dataset') );
            
            if ($objetos) {
                foreach ($objetos as $objeto) {
                    $linha = $dadosset->appendChild( $dom->createElement( $this->registroAtivo ) );
                    
                    foreach ($this->gradedados->obtColunas() as $coluna) {
                        $nome_coluna = $coluna->obtNome();
                        $nome_coluna_cru = str_replace(['(','{','->', '-','>','}',')', ' '], ['','','_','','','','','_'], $nome_coluna);
                        
                        if (isset($objeto->$nome_coluna))
                        {
                            $valor = is_scalar($objeto->$nome_coluna) ? $objeto->$nome_coluna : '';
                            $linha->appendChild($dom->createElement($nome_coluna_cru, $valor)); 
                        } else if (method_exists($objeto, 'render')) {
                            $valor = $objeto->renderiza($nome_coluna);
                            $linha->appendChild($dom->createElement($nome_coluna_cru, $valor));
                        }
                    }
                }
            }
            
            $dom->save($saida);
            
            Transacao::fecha();
        } else {
            throw new Exception(_t('Permissão negada') . ': ' . $saida);
        }
    }
    
    /**
     * Export to PDF
     * @param $saida Output file
     */
    public function exportaParaPDF($saida)
    {
        if ( (!file_exists($saida) && is_writable(dirname($saida))) OR is_writable($saida))
        {
            $this->limite = 0;
            $this->gradedados->prepareForPrinting();
            $this->aoRecarregar();
            
            // string with HTML contents
            $html = clone $this->gradedados;
            $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();
            
            // converts the HTML template into PDF
            $dompdf = new Dompdf;
            $dompdf->loadHtml($contents);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            // write and open file
            file_put_contents($saida, $dompdf->output());
        }
        else
        {
            throw new Exception('Permissão negada' . ': ' . $saida);
        }
    }
    
    /**
     * Export to CSV
     */
    public function aoExportaParaCSV($param)
    {
        try
        {
            $saida = 'Aplic/Saida/'.uniqid().'.csv';
            $this->exportaParaCSV( $saida );
            Pagina::abreArquivo( $saida );
        }
        catch (Exception $e)
        {
            return new Mensagem('erro', $e->getMessage());
        }
    }
    
    /**
     * Export to XML
     */
    public function onExportXML($param)
    {
        try
        {
            $saida = 'Aplic/Saida/'.uniqid().'.xml';
            $this->exportaParaXML( $saida );
            Pagina::abreArquivo( $saida );
        }
        catch (Exception $e)
        {
            return new Mensagem('erro', $e->getMessage());
        }
    }
    
    /**
     * Export gradedados as PDF
     */
    public function aoExportaPDF($param)
    {
        try
        {
            $saida = 'Aplic/Saida/'.uniqid().'.pdf';
            $this->exportaParaPDF($saida);
            
            $janela = Janela::cria('Export', 0.8, 0.8);
            $objeto = new Elemento('object');
            $objeto->data  = $saida;
            $objeto->type  = 'application/pdf';
            $objeto->style = "width: 100%; height:calc(100% - 10px)";
            $janela->adic($objeto);
            $janela->exibe();
        } catch (Exception $e) {
            new Mensagem('erro', $e->getMessage());
        }
    }
}
