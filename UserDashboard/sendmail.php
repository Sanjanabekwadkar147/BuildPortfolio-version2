<?php
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output, change this to 2 for detailed errors
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'sanjanabekwadkar@gmail.com';       // SMTP username
    $mail->Password = 'pacc ofrf ewsx sdiu';                        // SMTP password
    $mail->SMTPSecure = 'tls';                            // Change from 'ssl' to 'tls'
    $mail->Port = 587;                                    // TCP port to connect to

    // Recipients
    $mail->setFrom('sanjanabekwadkar@gmail.com', 'Mailer');
    $mail->addAddress('sanjanab.uvxcel@gmail.com', 'Sanjana Bekwadkar');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Simple Email Test via PHP';
    $mail->Body    = 'Hi, This is test email send by PHP Script';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Email successfully sent ';
} catch (Exception $e) {
    echo "Email sending failed: {$mail->ErrorInfo}";
}
?>
