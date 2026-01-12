<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

$mail = new PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = 2; 
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'saimaniesward@gmail.com';
    $mail->Password   = 'tmcm pmar ndny eybi';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('saimaniesward@gmail.com', 'Test Email');
    $mail->addAddress('sai2ytvtty@gmail.com'); // Sending to self/test email found in the screenshot

    $mail->isHTML(true);
    $mail->Subject = 'Test Email from AyurPredict Debugger';
    $mail->Body    = 'This is a test email to verify SMTP configuration.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
