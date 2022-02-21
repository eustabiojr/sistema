<?php

use Estrutura\BancoDados\Transacao;

/**
 * Serviço de E-mail
 *
 * @version    7.0
 * @package    util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ServicoMail
{
    /**
     * Send email
     * @param $destinatarios array of target emails
     * @param $assunto assunto da mensagem
     * @param $corpo corpo da mensagem
     * @param $tipocorpo body type (text, html)
     */
    public static function envia($destinatarios, $assunto, $corpo, $tipocorpo = 'text')
    {
        Transacao::abre('permission');
        $preferencias = SystemPreference::getAllPreferences();
        Transacao::fecha();
        
        $mail = new Mail;
        $mail->defDe( trim($preferencias['mail_from']), NOME_APLICATIVO );
        
        if (is_string($destinatarios))
        {
            $destinatarios = str_replace(',', ';', $destinatarios);
            $destinatarios = explode(';', $destinatarios);
        }
        
        if (is_array($destinatarios))
        {
            foreach ($destinatarios as $para)
            {
                $mail->adicEndereco( $para );
            }
        }
        else
        {
            $mail->adicEndereco( $destinatarios );
        }
        $mail->defAssunto( $assunto );
        
        if ($preferencias['smtp_auth'])
        {
            $mail->DefUsaSmtp();
            $mail->DefSmtpHost($preferencias['smtp_host'], $preferencias['smtp_port']);
            $mail->DefUsuarioSmtp($preferencias['smtp_user'], $preferencias['smtp_pass']);
        }
        
        if ($tipocorpo == 'text')
        {
            $mail->defCorpoTexto($corpo);
        }
        else
        {
            $mail->defCorpoHtml($corpo);
        }
        
        $mail->envia();
    }
}
