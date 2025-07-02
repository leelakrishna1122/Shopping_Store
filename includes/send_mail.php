<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendOrderEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';         // or your SMTP provider
        $mail->SMTPAuth = true;
        $mail->Username = 'your_mail@gmail.com';   // Your email
        $mail->Password = 'your_16digit_pass';      // Use App Password (not normal password)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_mail@gmail.com', 'Kittu\'s Store');
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
