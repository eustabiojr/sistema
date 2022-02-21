<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * TMail
 *
 * @version    7.0
 * @package    util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class Mail
{
    private $pm; // phpMailer instance
    
    /**
     * Class Constructor
     */
    function __construct()
    {
        $this->pm = new PHPMailer(true);
        
        $this->pm->SMTPOptions = array(
                    'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
        $this->pm-> CharSet = 'utf-8';
    }
    
    /**
     * Turn ON/OFF the debug
     */
    function defDepuracao($bool)
    {
        $this->pm->SMTPDebug = $bool;
    }
    
    /**
     * Set from email address
     * @param  $from = from email
     * @param  $nome = from name
     */
    function defDe($from, $nome = null)
    {
        $this->pm->From = $from;
        
        if ($nome)
        {
            $this->pm->FromName = $nome;
        }
    }
    
    /**
     * Set reply-to email address
     * @param  $email = reply-to email
     * @param  $nome  = reply-to name
     */
    function defRespostaPara($endereco, $nome = '')
    {
        $this->pm->AddReplyTo($endereco, $nome = '');
    }
    
    /**
     * Set the message subject
     * @param  $assunto of the message
     */
    function defAssunto($assunto)
    {
        $this->pm->Subject = $assunto;
    }
    
    /**
     * Set the email text body
     * @param  $corpo = text body
     */
    function defCorpoTexto($corpo)
    {
        $this->pm->Body = $corpo;
        $this->pm-> IsHTML(false);
    }
    
    /**
     * Set the email html body
     * @param  $corpo = html body
     */
    function defCorpoHtml($html)
    {
        $this->pm->MsgHTML($html);
    }
    
    /**
     * Add an TO address
     * @param  $endereco = TO email address
     * @param  $nome    = TO email name
     */
    public function adicEndereco($endereco, $nome = '')
    {
        if (!$nome)
        {
            // search by pattern: nome <email@mail.com>
            list($endereco, $nome) = $this->analisaMail($endereco);
        }
        
        $this->pm->AddAddress($endereco, $nome);
    }
    
    /**
     * Add an CC address
     * @param  $endereco = CC email address
     * @param  $nome    = CC email name
     */
    public function adicCC($endereco, $nome = '')
    {
        $this->pm->AddCC($endereco, $nome);
    }
    
    /**
     * Add an BCC address
     * @param  $endereco = BCC email address
     * @param  $nome    = BCC email name
     */
    public function adicBCC($endereco, $nome = '')
    {
        $this->pm->AddBCC($endereco, $nome);
    }
    
    /**
     * Add an attachment
     * @param  $caminho = path to file
     * @param  $nome = name of file
     */
    public function adicAnexo($caminho, $nome = '')
    {
        $this->pm->AddAttachment($caminho, $nome);
    }
    
    /**
     * Set to use Smtp
     */
    public function DefUsaSmtp($auth = true)
    {
        $this->pm-> IsSMTP();            // set mailer to use SMTP
        $this->pm->SMTPAuth = $auth;    // turn on SMTP authentication
    }
    
    /**
     * Set Smtp Host
     * @param  $hosp = smtp host
     */
    public function DefSmtpHost($hosp, $porta = 25)
    {
        $this->pm->Host = $hosp;
        $this->pm->port = $porta;
        
        if (strstr($this->pm->Host, 'gmail') !== FALSE)
        {
            $this->pm->SMTPSecure = "ssl";
        }
    }
    
    /**
     * Set Smtp User
     * @param  $usuario = smtp user
     * @param  $senha = smtp pass
     */
    public function DefUsuarioSmtp($usuario, $senha)
    {
        $this->pm->Username = $usuario;
        $this->pm->Password = $senha;
    }
    
    /**
     * Returns name and email separated
     */
    public function analisaMail($fullmail)
    {
        $pos = strpos($fullmail, '<');
        if ( $pos !== FALSE )
        {
            $nome  = trim(substr($fullmail, 0, $pos-1));
            $email = trim(substr($fullmail, $pos+1, -1));
            $nome  = str_replace("'", "''", $nome);
            
            return array($email, $nome);
        }
        
        return array($fullmail, '');
    }
    
    /**
     * Send the email
     */
    public function envia()
    {
        $this->pm-> Send();
        return TRUE;
    }
}
