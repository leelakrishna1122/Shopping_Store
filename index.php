<?php
// Start the session and check if the user is logged in
session_start();

// Logout Logic - placed at the top of the script
if (isset($_POST['logout'])) {
    session_unset(); // Remove all session variables
    session_destroy(); // Destroy the session
    header("Location: pages/login.php"); // Redirect to login page
    exit(); // Make sure no further code is executed after redirection
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: pages/login.php");
    exit();
}

// Fetch products from the database
include 'includes/db.php';
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .intro-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,rgb(118, 116, 235),rgb(229, 172, 172));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5em;
            z-index: 9999;
            animation: fadeOut 2.5s ease-in-out 2s forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>
</head>
<body>
    <div class="intro-overlay">
        😊 Happy Shopping!
    </div>
    <header>
        <div class="header-container">
            <h1>Welcome to KITTU'S Store</h1>
            <nav>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="pages/login.php">Login</a>
                    <a href="pages/register.php">Register</a>
                <?php else: ?>
                    <a href="pages/cart.php" class="cart-link">
                        <img src="images/cart-icon.png" alt="Cart" class="cart-icon">
                        Cart
                    </a>
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="logout" class="logout-button">Logout</button>
                    </form>
                <?php endif; ?>
                <a href="index.php">Home</a>
                <a href="pages/about.php">About Us</a>
                <a href="pages/contact.php">Contact</a>
            </nav>
        </div>
    </header>
    <div class="main-container">
        <main>
            <h2>Products</h2>
            <div class="product-list">
                <?php if (empty($products)) : ?>
                    <p>No products available.</p>
                <?php else : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="product">
                            <h3><?= htmlspecialchars($product['name']); ?></h3>
                            <p>Price: $<?= number_format($product['price'], 2); ?></p>
                            <p><?= htmlspecialchars($product['description']); ?></p>
                            <?php if (!empty($product['image'])) : ?>
                                <img src="images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                            <?php endif; ?>
                            <form method="POST" action="pages/add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>
