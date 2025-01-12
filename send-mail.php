<?php

use MagicObject\MagicObject;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . "/inc.app/app.php";

$mail = new PHPMailer(true);

$resetPasswordConfig = new MagicObject();



$resetPasswordConfig->loadYamlString(
'
mailer:
  host: smtp.gmail.com
  port: 587
  username: your_email@gmail.com
  password: your_password
  sender_address: your_email@gmail.com 
  sender_name: Mailer

subject_reset_password: Reset Password
subject_registration: Registrasi
subject_account_acctivation: Aktivasi Akun
'    
);

$recipient = new MagicObject();

try {
    //Server settings
    $mail->SMTPDebug = 2;                                             // Enable verbose debug output
    $mail->isSMTP();                                                  // Set mailer to use SMTP
    $mail->Host       = $resetPasswordConfig->getHost();              // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                         // Enable SMTP authentication
    $mail->Username   = $resetPasswordConfig->getUsername();          // SMTP username
    $mail->Password   = $resetPasswordConfig->getPassword();                        // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;               // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = intval($resetPasswordConfig->getPassword());  // TCP port to connect to

    //Recipients
    $mail->setFrom($resetPasswordConfig->getSenderAddress(), $resetPasswordConfig->getSenderName());
    $mail->addAddress($recipient->getEmail(), $recipient->getName()); // Add a recipient

    // Content
    $mail->isHTML(true);                                              // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    error_log($e->getMessage());
}