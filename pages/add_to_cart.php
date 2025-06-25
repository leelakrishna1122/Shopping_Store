<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Check if the product is already in the cart
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);

if ($stmt->rowCount() > 0) {
    // If it exists, increase quantity
    $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
} else {
    // Else, add new entry
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt->execute([$user_id, $product_id]);
}

header("Location: cart.php");
exit();
