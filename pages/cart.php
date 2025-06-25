<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Handle quantity update
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$quantity, $user_id, $product_id]);
}

// Handle item removal
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

// Fetch updated cart items
$stmt = $conn->prepare("SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, cart.quantity 
                        FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .back-button:hover {
            background-color: #45a049;
        }

        .cart-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        button {
            padding: 5px 10px;
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #e60000;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .checkout-btn {
            display: block;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            width: 200px;
            margin: 0 auto;
        }

        .checkout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <a href="../index.php" class="back-button">← Continue Shopping</a>

    <h2>Your Shopping Cart</h2>

    <table>
        <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($cart_items) > 0): ?>
            <?php foreach ($cart_items as $item): ?>
                <?php $subtotal = $item['price'] * $item['quantity']; ?>
                <?php $total_cost += $subtotal; ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td>$<?= number_format($item['price'], 2); ?></td>
                    <td>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1" style="width: 60px;">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                    </td>
                    <td>$<?= number_format($subtotal, 2); ?></td>
                    <td>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                            <button type="submit" name="remove_from_cart">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Your cart is empty.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="total">
        Total: $<?= number_format($total_cost, 2); ?>
    </div>

    <?php if (count($cart_items) > 0): ?>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    <?php endif; ?>
</div>

</body>
</html>
