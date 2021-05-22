<?php
/********************************************************************************************
 * Sistema Agenet
 * 
 * Data: 09/03/2021
 ********************************************************************************************/
 # Espaço de nomes
namespace Estrutura\Embrulho;s

use FPDF;
use Exception;
use SimpleXMLIterator;

/**
 * Adaptador FPDF que analisa arquivos XML para Framework Ageunet
 * 
 * @version   0.1
 * @package   base
 * @author    Eustábio J. Silva Jr.
 * @author    Pablo Dall'Oglio
 * @copyright Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license   http://www.adianti.com.br/framework-license 
 */
class AgeunetConstrutorPDF extends FPDF
{
    private $localizacao_atual;
    private $elementos;
    private $ancoras;
    private $orientacao;
    private $formato;
    private $substituicoes;
    private $B;
    private $I;
    private $U;

    /**
     * Método Construtor
     * 
     * @param $orientacao - Orientação da página
     * @param $formato - Formato da página
     * @author Eustábio J. Silva Jr.
     * @author Pablo Dall'Oglio
     */
    public function __construct($orientacao = 'P', $formato = 'a4', $unit = 'pt')
    {
        parent::__construct($orientacao, $unit, $formato);

        $this->defLocalizacao();

        parent::DefAutoQuebraPagina(true);
        parent::DefMargens(0, 0, 0);
        parent::DefCriador('Estudio Ageunet Construtor PDF');
        parent::DefPreencheCor(255, 255, 255);
        parent::Abre();
        parent::AtalhoPaginasNb();
        parent::DefX(20);

        $this->substituicoes = array();
        $this->href = '';
        $this->ancoaras = array();
        $this->orientacao = $orientacao;
        $this->formato = $formato;
        parent::DefFonte('Arial', '', 10 * 1.3);
    }

    /**
     * Carrega elementos projetados do XML
     * 
     * @param $nomearquivo - Localização do arquivo XML
     * @param Eustábio J. Silva Jr.
     * @author Pablo Dall'Oglio
     */
    public function doXml($nomearquivo) 
    {
        if (file_exists($nomearquivo)) {
            $xml = new SimpleXMLIterator(file_get_contents($nomearquivo));

            $elementos = array();
            foreach ($xml as $tag => $objetoxml) {
                $propriedades = (array) $objetoxml;
                array_walk_recursive($propriedades, array($this, 'arrayParaIso8859'));

                # ###
                if ($tag == 'page') {
                    $this->formato = (string) $propriedades['format'];
                    $this->orientacao = (string) $propriedades['orientation'];
                } else {
                    $elementos[] = $propriedades;
                }
            }

            $this->carregaElementos($elementos);
        } else {
            throw new Exception(_t('Arquivo (^1) não existe', $nomearquivo)); ### ???
        }
    }

    /**
     * Carrega elementos
     * 
     * @param $elementos - Elementos (formas) para carregar
     */
    public function carregaElementos($elementos)
    {
        $this->elementos = $elementos;

        # mapa de âncora
        if ($this->elementos) {
            foreach ($this->elementos as $elemento) {
                if (isset($elemento['classe']) AND $elemento['classe'] == 'Anchor') {
                    $nome_ancora = $elemento['nome'];
                    $this->ancoras[$nome_ancora] = $elemento;
                }
            }
        }
    }

    /**
     * Coloca o cursor na posição de âncora xy
     * 
     * @param $nome_ancora - Nome âncora
     * @returns TRUE se a âncora existe
     */
    public function vaiparaAncoraXY($nome_ancora) 
    {
        if (isset($this->ancoras[$nome_ancora])) {
            $ancora_x = $this->ancoras[$nome_ancora]['x'];
            $ancora_y = $this->ancoras[$nome_ancora]['y'];

            $this->DefY($ancora_y);
            $this->DefX($ancora_x);

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Coloca o cursor na posição de âncora X
     * 
     * @param $nome_ancora - Nome âncora
     * @returns TRUE se a âncora existe
     */
    public function vaiparaAncoraX($nome_ancora)
    {
        if (isset($this->ancoras[$nome_ancora])) {
            $ancora_x = $this->ancoras[$nome_ancora]['x'];
            $this->DefX($ancora_x);

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Coloca o cursor na posição de âncora Y
     * 
     * @param $nome_ancora - Nome âncora
     * @returns TRUE se a âncora existe
     */
    public function vaiparaAncoraY($nome_ancora)
    {
        if (isset($this->ancoras[$nome_ancora])) {
            $ancora_y = $this->ancoras[$nome_ancora]['y'];
            $this->DefY($ancora_y);

            return TRUE;
        }
        return FALSE;
    }

    /**
     * Escreve na posição da âncora
     * 
     * @param $nome_ancora - Nome âncora
     * $param $texto - Texto a ser escrito
     * @returns TRUE se a âncora existe
     */
    public function escreveNaAncora($nome_ancora, $texto)
    {
        if ($this->vaiparaAncoraXY($nome_ancora)) {
            parent::Escreve($this->TamanhoFontePt, $texto);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Substitui uma pedaço do {texto}
     * 
     * @param $marcacao - parte a ser substituída
     * @param $texto - novo conteúdo
     */
    public function substitui($marcacao, $texto) 
    {
        $this->substituicoes[$marcacao] = $texto;
    }

    /**
     * Gera uma página PDF com elementos analisados
     */
    public function gera()
    {
        $this->AdicPagina($this->orientacao, $this->formato);
        $estilo = '';

        foreach ($this->elementos as $elemento) {
            if ($this->elemento['classe']) {
                switch ($elemento['classe']) {
                    case 'Retangulo':
                        if ($elemento['deslocamentosombra'] > 0) {
                            $this->defPreenchimentoCorRGB($elemento['corsombra']);
                            $this->Retan($elemento['x'] + $elemento['deslocamentosombra'], $elemento['y'] + $elemento['deslocamentosombra'], $elemento['largura'], $elemento['altura'], 'F');
                        }
                        parent::DefLargLinha($elemento['larguralinha']);
                        $this->defDesenhaColorRGB($elemento['corlinha']);
                        $this->defPreencheCorRGB($elemento['preenchecor']);
                        $modo = $elemento['larguralinha'] > 0 ? 'FD' : 'F';
                        parent::Retan($elemento['x'], $elemento['y'], $elemento['largura'], $elemento['largura'], $modo);
                    break;

                    case 'Elipse':
                        $x = $elemento['x'] + ($elemento['largura'] / 2);
                        $y = $elemento['y'] + ($elemento['altura'] / 2);

                        if ($elemento['deslocamentosombra'] > 0) {
                            $preenchec = $this->rgb2int255($elemento['corsombra']);
                            parent::DefPreencheCor($preenchec[0], $preenchec[1],$preenchec[2]);
                            $this->elipse($x + $elemento['deslocamentosombra'], $y + $elemento['deslocamentosombra'], $elemento['largura'] / 2, $elemento['altura'] / 2, 'F');
                        }
                        $modo = $elemento['larguralinha'] > 0 ? 'FD' : 'F';
                        parent::DefLarguraLinha($elemento['larguralinha']);
                        $this->defDesenhaCorRGB($elemento['corlinha']);
                        $this->elipse($x, $y, $elemento['largura'] / 2, $elemento['largura'] / 2, $modo);
                    break;

                    case 'Texto':
                        $fator_altura['Courier'] = 0.335;
                        $fator_altura['Arial'] = 0.39;
                        $fator_altura['Times'] = 0.42;
                        $texto = str_replace(array_keys($this->substituicoes), array_values($this->substituicoes), $elemento['texto']);

                        $x = $elemento['x'] - 2;
                        $y = $elemento['y'] + ($elemento['tamanho'] * $fator_altura[$elemento['fonte']]) - (30 * (1/$elemento['tamanho']));
                        if ($elemento['deslocamentosombra'] > 0) {
                            $this->defCorFonteRGB($elemento['corsombra']);
                            parent::DefFonte($elemento['fonte'], $estilo, $elemento['tamanho']);
                            $this->escreveHTML($x + $elemento['deslocamentosombra'], $y + $elemento['deslocamentosombra'], $texto);
                        }
                         parent::DefFonte($elemento['fonte'], $estilo, $elemento['tamanho']);
                         $this->escreveHTML($x, $y, $texto);
                    break;
                    case 'Linha':
                        parent::DefLarguraLinha($elemento['larguralinha']);
                        $this->defCorFonteRGB($elemento['corlinha']);
                        parent::Linha($elemento['x'], $elemento['y'], $elemento['x2'], $elemento['y2']);
                    break;
                    case 'Imagem':
                        if (file_exists($elemento['arquivo'])) {
                            parent::Imagem($elemento['arquivo'], $elemento['x'], $elemento['y'], $elemento['largura'], $elemento['altura']);
                        }
                    break;
                }
            }
        }
    }

    /**
     * Desenha uma elipse
     * 
     * @param $x X
     * @param $y Y
     * @param $rx Raio X
     * @param $ry Raio Y
     * @param $estilo - Estilo da linha
     * @author Olivier Plathey
     */
    public function elipse($x, $y, $rx, $ry, $estilo = 'D')
    {
        if ($estilo == 'F') {
            $op = 'f';
        } elseif ($estilo == 'FD' OR $estilo == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }

        $lx = 4/3 * (M_SQRT2 - 1) * $rx;
        $ly = 4/3 * (M_SQRT2 - 1) * $ry;

        $this->_saida(sprintf('%.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c',
            ($x+$rx) * $k, ($h - $y) * $k,
            ($x+$rx) * $k, ($h - ($y - $ly)) * $k,
            ($x+$lx) * $k, ($h - ($y - $ry)) * $k,
            $x * $k, ($h - ($y - $ry)) * $k));
        $this->_saida(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
            ($x - $lx) * $k, ($h - ($y - $ry)) * $k,
            ($x - $lx) * $k, ($h - ($y - $ry)) * $k,
            ($x - $rx) * $k, ($h - $y) *$k));
        $this->_saida(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
            ($x - $rx) * $k, ($h - ($y + $ly)) * $k,
            ($x - $rx) * $k, ($h - ($y + $ry)) * $k,
            $x * $k, ($h - ($y + $ry)) * $k));
        $this->_saida(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c %s',
            ($x + $lx) * $k, ($h - ($y + $ry)) * $k,
            ($x + $rx) * $k, ($h - ($y + $ly)) * $k,
            ($x + $rx) * $k, ($h - $y) * $k,
            $op));
    }

    /**
     * Escreve HTML
     * 
     * @param $x X
     * @param $y Y
     * @param $html HTML
     * @author Azeem Abbas (contribuidor de fpdf.org)
     */
    public function escreveHTML($x, $y, $html) 
    {
        $this->DefY($y);
        $this->DefX($x);

        # Analisa HTML
        $html = str_replace("\n", '<br>', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i %2==0) {
                # Texto
                if ($this->href) {
                    $this->colocaLink($this->href, $e);
                } else {
                    $this->Escreve(5, $e);
                }
            } else {
                # Tag
                if (substr($e, 0, 1) == '/') {
                    $this->fechaTag(strtoupper(substr($e, 1)));
                } else {
                    # Extrai atributos
                    $a2   = explode(' ', $e);
                    $tag  = strtoupper(array_shift($a2));
                    $atrib = array();
                    foreach ($a2 as $v) {
                        if (\preg_match('^([^=]*)=["\']?([^"\']*)["\']?$', $v, $a3)) {
                            $atrib[strtoupper($a3[1])]=$a3[2];
                        }
                    }
                    $this->abreTag($tag, $atrib, $x);
                }
            }
        }
    }

    /**
     * Abre uma TAG HTML
     * @param $tag Tag
     * @param $x Posição X
     * @author Azeem Abbas (contribuidor de fpdf.org)
     */
    public function abreTag($tag, $atrib, $x)
    {
        // Tab de abertura
        if ($tag == 'B' or $tag == 'I' or $tag == 'U') {
            $this->defEstilo($tag, true);
        }
        if ($tag == 'A') {
            $this->href = $atrib['href'];
        }
        if ($tag == 'BR') {
            parent::Ln($this->TamanhoFontePt * 1.1);
            parent::DefX($x);
        }
    }

    /**
     * Fecha Tag HTML
     * @param $tag Tag
     * @author Azeem Abbas (contribuidor de fpdf.org)
     */
    public function fechaTag($tag)
    {
        // Tab de abertura
        if ($tag == 'B' or $tag == 'I' or $tag == 'U') {
            $this->defEstilo($tag, false);
        }
        if ($tag == 'A') {
            $this->href = '';
        }
    }

    /**
     * Define estilo
     * @param $tag Tag
     * @param $habilita Habilita
     * @author Azeem Abbas (contribuidor de fpdf.org)
     */
    public function defEstilo($tag, $habilita)
    {
        # Modifica o estilo e seleciona a fonte correspondente
        $this->$tag += ($habilita ? 1 : -1);
        $estilo = '';
        foreach (array('B', 'I', 'U') as $s) {
            if (isset($this->$s)) {
                if ($this->$s > 0) {
                    $estilo .= $s;
                }
            }
        }
        $this->DefFonte('', $estilo);
    }

    /**
     * Coloca link
     * @param $URL
     * @param $txt 
     * @author Azeem Abbas (contribuidor de fpdf.org)
     */
    public function colocalink($URL, $txt)
    {
        parent::DefCorTexto(0,0,255);
        $this->defEstilo('U', true);
        parent::Escreve(5, $txt, $URL);
        $this->defEstilo('U', false);
        parent::DefCorTexto(0);
    }

    /**
     * Altera local PDF
     * @author Pablo Dall'Oglio
     */
    public function defLocal()
    {
        $this->local_atual = setlocale(LC_ALL, 0);

        if (OS == 'WIN') {
            setlocale(LC_ALL, 'english');
        } else {
            setlocale(LC_ALL, 'POSIX');
        }
    }

    /**
     * Retorna ao local antigo
     * @author Pablo Dall'Oglio
     */
    public function redefineLocal()
    {
        setlocale(LC_ALL, $this->local_atual);
    }

    /**
     * Altera a cor
     * @param $cor Cor em RGB
     * @author Pablo Dall'Oglio
     */
    public function defCorFonteRGB($cor)
    {
        $corR = hexdec(substr($cor, 1, 2));
        $corG = hexdec(substr($cor, 3, 2));
        $corB = hexdec(substr($cor, 5, 2));

        parent::DefCorTexto($corR, $corG, $corB);
    }

    /**
     * Altera a cor de preenchimento
     * @param $cor Cor em RGB
     * @author Pablo Dall'Oglio
     */
    public function defCorPreenchimentoRGB($cor)
    {
        $cpreenchimento = $this->rgb2int255($cor);
        parent::DefCorPreenchimento($cpreenchimento[0],$cpreenchimento[1],$cpreenchimento[2]);
    }

    /**
     * Altera a cor do desenho
     * @param $cor Cor em RGB
     * @author Pablo Dall'Oglio
     */
    public function defCorDesenhoRGB($cor)
    {
        $cpreenchimento = $this->rgb2int255($cor);
        parent::DefCorDesenho($cpreenchimento[0],$cpreenchimento[1],$cpreenchimento[2]);
    }

    /**
     * Converte RGB em array (0..255)
     * @param $rgb String de cor RGB
     * @author Pablo Dall'Oglio
     */
    public function rgb2int255($rgb)
    {
        $ints = self::rgb2int($rgb);
        $ints[0] = $ints[0] * 255;
        $ints[1] = $ints[1] * 255;
        $ints[2] = $ints[2] * 255;
        return $ints;
    }

    /**
     * Converte RGB em array (0..1)
     * @param $rgb String de cor RGB
     * @author Pablo Dall'Oglio
     */
    private function rgb2int($rgb)
    {
        $hex_vermelho = substr($rgb, 1, 2);
        $hex_verde    = substr($rgb, 3, 2);
        $hex_azul     = substr($rgb, 5, 2);

        $dec_vermelho = hexdec($hex_vermelho);
        $dec_verde    = hexdec($hex_verde);
        $dec_azul     = hexdec($hex_azul);

        $int_vermelho = $dec_vermelho / 255;
        $int_verde    = $dec_verde / 255;
        $int_azul     = $dec_azul / 255;

        return array($int_vermelho, $int_verde, $int_azul);
    }

    /**
     * Converte de UTF8 para ISO
     * @author Pablo Dall'Oglio
     */
    private function arrayParaIso8859(&$valor, $chave)
    {
        if (\is_scalar($valor)) {
            $valor = utf8_decode($valor);
        }
    }

    /**
     * Salva o PDF
     * @param $saida Caminho saída
     * 
     */
    public function salva($saida)
    {
        parent::Saida($saida);
        $this->redefineLocal();
    }
}