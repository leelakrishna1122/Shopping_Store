<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Product ID is missing.";
    exit();
}

$id = $_GET['id'];

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];

    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=? WHERE id=?");
    $stmt->execute([$name, $price, $desc, $id]);

    header("Location: manage_products.php");
    exit();
}

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <style>
        form {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 10px rgba(0,0,0,0.1);
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Edit Product</h2>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
        <input type="number" name="price" step="0.01" value="<?= $product['price']; ?>" required>
        <textarea name="description" rows="5" required><?= htmlspecialchars($product['description']); ?></textarea>
        <button type="submit">Update</button>
    </form>
</body>
</html>
