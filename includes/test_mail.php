<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use phpmailer\phpmailer\PHPMailer;

$mail = new PHPMailer(true);
echo "PHPMailer loaded successfully!";
