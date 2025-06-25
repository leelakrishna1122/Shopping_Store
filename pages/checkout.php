<?php
session_start();
include '../includes/db.php';
include '../includes/send_mail.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID
$user_id = $_SESSION['user_id'];

$showConfirmation = false;
$confirmationHTML = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $payment_method = $_POST['payment_method'];

    $stmt = $conn->prepare("SELECT p.name, p.price, c.quantity 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    $product_summary = "";
    foreach ($cart_items as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        $product_summary .= "{$item['name']} - Quantity: {$item['quantity']} - Subtotal: ₹{$subtotal}\n";
    }

    $admin_stmt = $conn->prepare("SELECT * FROM users WHERE role = 'admin' AND city = ? ORDER BY RAND() LIMIT 1");
    $admin_stmt->execute([$city]);
    $admin = $admin_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $fallback_stmt = $conn->query("SELECT * FROM users WHERE role = 'admin' ORDER BY RAND() LIMIT 1");
        $admin = $fallback_stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($admin) {
        $admin_email = $admin['email'];
        $subject = "🛒 New Order from $name";
        $message = "Customer Name: $name\n"
                 . "Mobile: $mobile\n"
                 . "City: $city\n"
                 . "Address: $address\n\n"
                 . "Products Ordered:\n$product_summary\n"
                 . "Total Amount: ₹$total\n"
                 . "Payment Method: $payment_method";

        $mailSent = sendOrderEmail($admin_email, $subject, $message);

        $showConfirmation = true;
        $confirmationHTML = $mailSent ?
            "<div class='confirmation-message'>
                <h2>Order Placed Successfully! 🎉</h2>
                <p>Thank you for shopping with <strong>KITTU'S STORE</strong>.</p>
                <p>You will receive a confirmation mail from our admin and a text message to your phone number. Please respond to confirm your order.</p>
                <p>We appreciate your support! 🛍️</p>
            </div>" :
            "<div class='confirmation-message' style='color:red'>
                <h3>Order placed but failed to send email to admin.</h3>
            </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Kittu's Store</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('https://images.unsplash.com/photo-1606312619344-e017f255b8c2?auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .checkout-container, .confirmation-message {
            max-width: 650px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        label {
            font-weight: bold;
            font-size: 15px;
            display: block;
            margin-bottom: 5px;
        }
        .submit-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-button:hover {
            background-color: #218838;
        }
        .confirmation-message {
            text-align: center;
            background: linear-gradient(to right, #e0f7fa, #e0f2f1);
        }
        .confirmation-message h2 {
            color: #2e7d32;
            font-size: 28px;
        }
        .confirmation-message p {
            font-size: 18px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php if ($showConfirmation): ?>
        <?= $confirmationHTML ?>
    <?php else: ?>
        <div class="checkout-container">
            <h2>Checkout</h2>
            <form method="POST">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>

                <label for="mobile">Mobile Number</label>
                <input type="text" id="mobile" name="mobile" required>

                <label for="city">City</label>
                <input type="text" id="city" name="city" required>

                <label for="address">Delivery Address</label>
                <textarea id="address" name="address" required></textarea>

                <label for="payment_method">Payment Method</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="UPI">UPI Payment</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="Card">Card Payment</option>
                </select>

                <button type="submit" name="place_order" class="submit-button">Place Order</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
