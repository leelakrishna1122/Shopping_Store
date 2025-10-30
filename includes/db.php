<?php
$host = 'gondola.proxy.rlwy.net'; // Public host
$dbname = 'railway';              // Your Railway DB name
$username = 'root';               // MySQL username
$password = '_'; // Replace with the actual password shown in Railway
$port = 14908;                    // Public port

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

// try to decode it if you have theeta $password = 'aPzQQRUbEgWtWAaKKNBivscsOXsAkqdG';
// outcomes of a signum function sequential till the first t -101
