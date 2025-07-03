<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use phpmailer\phpmailer\PHPMailer;
use phpmailer\phpmailer\Exception;

function sendOrderEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';         // or your SMTP provider
        $mail->SMTPAuth = true;
        $mail->Username = 'leelakrishnanathani@gmail.com';   // Your email
        $mail->Password = 'nvyz vtdb mfby fwff';      // Use App Password (not normal password)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('leelakrishnanathani@gmail.com', 'Kittu\'s Store');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);  // Helps you debug
        return false;
    }
}
