<?php
require __DIR__ . '/vendor/autoload.php'; // ✅ Correct path to autoload

require __DIR__ . '/includes/send_mail.php';

$result = sendOrderEmail(
    'leelakrishnanathani@gmail.com',                 // ✅ Your test recipient
    '✔️ Test Email from Kittu\'s Store',
    "This is a test email sent from your local server using PHPMailer."
);

if ($result) {
    echo "✅ Email sent successfully!";
} else {
    echo "❌ Failed to send email.";
}
