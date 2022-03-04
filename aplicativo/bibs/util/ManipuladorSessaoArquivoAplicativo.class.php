<?php

# define('NOME_APLICATIVO', 'teste');

class ManipuladorSessaoArquivoAplicativo implements SessionHandlerInterface
{
    private $salvaCaminho;
    
    public function open($salvaCaminho, $nomeSessao)
    {
        $this->salvaCaminho = $salvaCaminho ? $salvaCaminho : '/tmp';
        if (!is_dir($this->salvaCaminho)) {
            mkdir($this->salvaCaminho, 0777);
        }

        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        $aplicativo = NOME_APLICATIVO;
        return (string)@file_get_contents("{$this->salvaCaminho}/sess_{$aplicativo}_{$id}");
    }

    public function write($id, $data)
    {
        $aplicativo = NOME_APLICATIVO;
        return file_put_contents("{$this->salvaCaminho}/sess_{$aplicativo}_{$id}", $data) === false ? false : true;
    }

    public function destroy($id)
    {
        $aplicativo = NOME_APLICATIVO;
        $arquivo = "{$this->salvaCaminho}/sess_{$aplicativo}_{$id}";
        if (file_exists($arquivo)) {
            unlink($arquivo);
        }

        return true;
    }

    public function gc($maxtempovida)
    {
        $aplicativo = NOME_APLICATIVO;
        foreach (glob("{$this->salvaCaminho}/sess_{$aplicativo}_*") as $arquivo) {
            clearstatcache(true, $arquivo);
            if (filemtime($arquivo) + $maxtempovida < time() && file_exists($arquivo)) {
                unlink($arquivo);
            }
        }

        return true;
    }
}


#$tratador = new ManipuladorSessaoArquivoAplicativo();
#session_set_save_handler($tratador, true);
#session_start();