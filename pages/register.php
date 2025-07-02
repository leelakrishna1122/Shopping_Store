<?php
session_start();
require_once '../includes/db.php';   // DB connection

// ────────────────
// Handle POST
// ────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $city     = trim($_POST['city']     ?? '');
    $password = $_POST['password']      ?? '';
    $role     = 'user';

    // 1️⃣ Validate Username
    if ($username === '' || strlen($username) < 3) {
        $error_message = 'Username must be at least 3 characters.';
    }

    // 2️⃣ Validate Email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    }

    // 3️⃣ Validate City
    elseif ($city === '' || strlen($city) < 2) {
        $error_message = 'Please enter a valid city name.';
    }

    // 4️⃣ Validate Password
    elseif (strlen($password) < 6) {
        $error_message = 'Password must be at least 6 characters.';
    }

    // 5️⃣ Check for existing email
    else {
        $stmt = $conn->prepare('SELECT 1 FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error_message = 'This email is already registered.';
        }
    }

    // 6️⃣ Insert user if everything is valid
    if (empty($error_message)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare(
            'INSERT INTO users (username, email, password, role, city) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$username, $email, $hashed, $role, $city]);

        $_SESSION['user_id'] = $conn->lastInsertId();
        header('Location: ../index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Register</title>
  <style>
    body{font-family:Arial,sans-serif;background:#f4f4f9;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}
    .register-container{background:#fff;padding:30px;border-radius:8px;box-shadow:0 4px 10px rgba(0,0,0,.1);max-width:400px;width:100%}
    h2{text-align:center;margin-bottom:20px;color:#333}
    label{display:block;margin-bottom:5px;font-size:1.05em}
    input{width:100%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:5px;font-size:1em}
    button{width:100%;padding:12px;background:#28a745;color:#fff;border:none;border-radius:5px;font-size:1.1em;cursor:pointer;transition:.3s}
    button:hover{background:#218838}
    .error-message{color:#e74c3c;text-align:center;margin-top:10px}
    .switch-link{text-align:center;margin-top:15px}
  </style>
</head>
<body>
<div class="register-container">
  <h2>Create an Account</h2>

  <?php if (!empty($error_message)): ?>
    <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
  <?php endif; ?>

  <form method="POST" novalidate>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required minlength="3"
           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required
           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

    <label for="city">City:</label>
    <input type="text" id="city" name="city" required minlength="2"
           value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required minlength="6">

    <button type="submit" name="register">Register</button>
  </form>

  <div class="switch-link">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>
</body>
</html>
