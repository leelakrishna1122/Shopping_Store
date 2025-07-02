<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';


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
