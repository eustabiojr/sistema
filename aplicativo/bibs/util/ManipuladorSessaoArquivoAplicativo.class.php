<?php
class ManipuladorSessaoArquivoAplicativo implements SessionHandlerInterface
{
    private $salvaCaminho;
    public function abre($salvaCaminho, $nomeSessao)
    {
        $this->salvaCaminho = $salvaCaminho ? $salvaCaminho : '/tmp';
        if (!is_dir($this->salvaCaminho)) {
            mkdir($this->salvaCaminho, 0777);
        }

        return true;
    }

    public function fecha()
    {
        return true;
    }

    public function ler($id)
    {
        $application = NOME_APLICATIVO;
        return (string)@file_get_contents("{$this->salvaCaminho}/sess_{$application}_{$id}");
    }

    public function escreve($id, $data)
    {
        $application = NOME_APLICATIVO;
        return file_put_contents("{$this->salvaCaminho}/sess_{$application}_{$id}", $data) === false ? false : true;
    }

    public function destroi($id)
    {
        $application = NOME_APLICATIVO;
        $file = "{$this->salvaCaminho}/sess_{$application}_{$id}";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    public function gc($maxlifetime)
    {
        $application = NOME_APLICATIVO;
        foreach (glob("{$this->salvaCaminho}/sess_{$application}_*") as $file) {
            clearstatcache(true, $file);
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}
