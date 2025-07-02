<?php
$host = 'gondola.proxy.rlwy.net'; // Public host
$dbname = 'railway';              // Your Railway DB name
$username = 'root';               // MySQL username
$password = 'YOUR_PASSWORD_HERE'; // Replace with the actual password shown in Railway
$port = 14908;                    // Public port

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
