<?php
// Start the session and include the database
session_start();
include '../includes/db.php';

// Handle form submission
$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $admin_passcode = $_POST['admin_passcode'];

    if ($admin_passcode === 'I_AM_VERIFIED') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $city = trim($_POST['city']);

        // Check for duplicate email before inserting
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $checkStmt->execute([$email]);
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
            $successMessage = "Email already exists. Please use a different one.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, city, role) VALUES (?, ?, ?, ?, 'admin')");
            if ($stmt->execute([$username, $email, $password, $city])) {
                $successMessage = "Registration successful!";
            } else {
                $successMessage = "Registration failed. Please try again.";
            }
        }
    } else {
        $successMessage = "Incorrect verification code.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #8EC5FC, #E0C3FC);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        .register-box h2 {
            margin-bottom: 20px;
        }
        .register-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .register-box button {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .register-box button:hover {
            background-color: #45a049;
        }
        .success-message {
            color: green;
            margin: 15px 0;
        }
        .login-link {
            margin-top: 15px;
        }
    </style>
    <script>
        function enableForm() {
            const passcode = document.getElementById('admin_passcode').value;
            const form = document.getElementById('adminForm');
            if (passcode === 'I_AM_VERIFIED') {
                form.style.display = 'block';
                document.getElementById('verifyBtn').style.display = 'none';
            } else {
                alert("Incorrect verification code.");
            }
        }
    </script>
</head>
<body>
<div class="register-box">
    <h2>Admin Verification</h2>
    <input type="password" id="admin_passcode" placeholder="Enter Admin Code">
    <button id="verifyBtn" onclick="enableForm()">Verify</button>

    <form id="adminForm" method="POST" style="display: none;">
        <input type="hidden" name="admin_passcode" value="I_AM_VERIFIED">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="city" placeholder="City" required>
        <button type="submit" name="register">Register</button>
    </form>

    <?php if ($successMessage): ?>
        <div class="success-message">
            <?= htmlspecialchars($successMessage); ?>
        </div>
        <?php if ($successMessage === "Registration successful!"): ?>
            <div class="login-link">
                <a href="login.php">Login Now</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
