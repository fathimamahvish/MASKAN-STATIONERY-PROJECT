<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'db.php';

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = trim($_POST['item_name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = "uploads/" . basename($image_name);

    // Move uploaded image to 'uploads' directory
    if (!empty($image_name) && move_uploaded_file($image_tmp, $image_path)) {
        $stmt = $conn->prepare("INSERT INTO inventory (item_name, price, quantity, image, is_deleted) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("sdis", $item_name, $price, $quantity, $image_name);
        if ($stmt->execute()) {
            $message = "✅ Product added successfully!";
        } else {
            $message = "❌ Failed to add product.";
        }
    } else {
        $message = "❌ Image upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - MASKAN STATIONERY SHOP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
            display: flex;
            justify-content: center;
        }

        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 6px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            color: green;
            margin-bottom: 15px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="customer-style.css">
</head>
<body>

<div class="form-box">
    <h2>Add New Product</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="item_name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price" step="0.01" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="file" name="image" accept="image/*" required>
        <input type="submit" value="Add Product">
    </form>

    <a class="back-link" href="admin-dashboard.php">⬅ Back to Inventory</a>
</div>
<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    }

    window.onload = function () {
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    };
</script>

</body>
</html>
