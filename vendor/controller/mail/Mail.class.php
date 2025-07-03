<?php
/**
 * Classe Mail.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package       vendor
 * @subpackage    controller
 * @version       1.0
 * @date          06/05/2022
 */

/** Defino o local onde esta a classe */
namespace vendor\controller\mail;

/** Importar classes PHPMailer para o namespace global */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/** Carregar o autoloader do Composer/PHPMailer */
require 'vendor/library/PHPMailer/vendor/autoload.php';

class Mail
{
    /** Declaro as vaiavéis da classe */
    private $messageType = null;
    private $data = [];
    private $mail = null;
    private $host = null;
    private $username = null;
    private $password = null;
    private $port = null;
    private $fromEmail = null;
    private $fromName = null;
    private $destinyEmail = null;
    private $destinyName = null;
    private $subject = null;
    private $body = null;


    /** Construtor da classe */
    function __construct()
    {
        /** Crie uma instância da biblioteca PHPMailer; passar `true` habilita exceções */
        $this->mail = new PHPMailer(true);
        $this->mail->CharSet = "UTF-8";
    }

    /** Envia o e-mail a partir do seu tipo informado */
    public function sendMail(string $host, string $username, string $password, int $port, string $fromEmail, string $fromName, string $destinyEmail, string $destinyName, string $subject, string $body)
    {

        /** Parametros de entrada */
        $this->host         = $host;
        $this->username     = $username;
        $this->password     = $password;
        $this->port         = $port;
        $this->fromEmail    = $fromEmail;
        $this->fromName     = $fromName;
        $this->destinyEmail = $destinyEmail;
        $this->destinyName  = $destinyName;
        $this->subject      = $subject;
        $this->body         = $body;        


        try {

            //Server settings
            //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;         //Enable verbose debug output
            $this->mail->isSMTP();                                 //Send using SMTP
            $this->mail->Host       = $this->host;                 //Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                        //Enable SMTP authentication
            $this->mail->Username   = $this->username;             //SMTP username
            $this->mail->Password   = $this->password;             //SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $this->mail->Port       = $this->port;                 //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            /**  Destinatário */
            $this->mail->setFrom($this->fromEmail, $this->fromName);
            $this->mail->addAddress($this->destinyEmail, $this->destinyName);  

            /** Conteúdo a ser enviado */
            $this->mail->isHTML(true); # Habilita o envio da mensagem no formato HTML
            $this->mail->Subject = $this->subject; # Assunto da mensagem enviada
            $this->mail->Body    = $this->body; # Corpo da mensagem enviada 
            
            /** Desativa a verificação de certificado */
            $this->mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                 )
             );            
        
            /** Envio da mensagem */
            $this->mail->send();          

        } catch (\Exception $e) {

            /** Informo */
            throw new \InvalidArgumentException("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}", 0);            
        }

    }

    /** Finaliza a classe instanciada */
    function __destruct(){}
}
