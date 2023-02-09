<?php

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail            = new PHPMailer( true );

// SMTP settings
$host            = 'smtp.sendgrid.net';
$port            = 587;
$isSecure        = true;
$isAuth          = true;
$userName        = 'apikey';
$password        = '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';

$attachments     = array();                                                             // Attachments

$subject         = 'subject';
$isHTML          = false;
$body            = <<< heredoc
Hello user,
questa email dovrebbe avere gli "a capo" come nel codice..
John
heredoc;

$txt             = null;
$sender          = new Contact( 'John', 'john@email.com' );
$sender->replyTo = new Contact( 'Information', 'info@example.com' );
$recipients      = array( new Contact( 'Peter', 'peter@email.com' ) );
$ccs             = array( new Contact( 'Josh', 'josh@email.com' ) );
$bccs            = [];

try {

    // Server settings

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                                              // Enable verbose debug output
    $mail->isSMTP();                                                                    // Send using SMTP
    $mail->Host       = $host;                                                          // Send using SMTP
    $mail->SMTPAuth   = $isAuth;                                                        // Enable SMTP authentication
    $mail->Username   = $userName;                                                      // Enable SMTP authentication
    $mail->Password   = $password;                                                      // SMTP password
    $mail->SMTPSecure = $isSecure ? PHPMailer::ENCRYPTION_SMTPS : null;                 // Enable implicit TLS encryption
    $mail->Port       = $port;

    // Sender

    $mail->setFrom( $sender->address, $sender->name );
    $mail->addReplyTo( $sender->replyTo->address, $sender->replyTo->name );

    // Recipients

    foreach ( $recipients as $recipient )                                               // Recipients
    {
        $mail->addAddress( $recipient->address, $recipient->name );
    }

    foreach ( $ccs as $cc )                                                             // CC Recipients
    {
        $mail->addCC( $cc->address, $cc->name );
    }

    foreach ( $bccs as $bcc )                                                           // Blind CC Recipients
    {
        $mail->addCC( $bcc );
    }


    // Attachments

    foreach ( $attachments as $attachment ) {
        $mail->addAttachment( $attachment );
    }

    // Content

    $mail->isHTML( $isHTML );
    $mail->Subject = $subject;
    $mail->Body    = $body;
    if ( ! is_null( $txt ) ) {
        $mail->AltBody = $txt;
    }

    // Send

    $mail->send();

    echo 'Message has been sent';

} catch ( Exception $e ) {

    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

}

function getAutoloadPath(): string {

    $autoloadPath = __DIR__
        . DIRECTORY_SEPARATOR . '..'
        . DIRECTORY_SEPARATOR . 'vendor'
        . DIRECTORY_SEPARATOR . 'autoload.php';

    return realpath( $autoloadPath );
}

class Contact {

    public string $address;

    public string $name;

    public Contact $replyTo;

    public function __construct( $name, $address ) {

        $this->name    = $name;
        $this->address = $address;
    }

}