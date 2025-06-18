<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db.php';

if (!isset($_GET['id'])) {
    die("No product ID provided.");
}

$id = intval($_GET['id']);
$message = "";

// Get current product details
$stmt = $conn->prepare("SELECT * FROM inventory WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = trim($_POST['item_name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $new_image = $_FILES['image']['name'];
    $image_path = $product['image']; // default to old image

    // Upload new image if provided
    if (!empty($new_image)) {
        $target_path = "uploads/" . basename($new_image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $new_image;
        }
    }

    $stmt = $conn->prepare("UPDATE inventory SET item_name = ?, price = ?, quantity = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdisi", $item_name, $price, $quantity, $image_path, $id);
    if ($stmt->execute()) {
        $message = "✅ Product updated successfully!";
        // refresh the product data
        $stmt = $conn->prepare("SELECT * FROM inventory WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
    } else {
        $message = "❌ Failed to update product.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product - MASKAN STATIONERY SHOP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
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
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 6px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        img.preview {
            width: 100px;
            margin-top: 10px;
            display: block;
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
    <h2>Edit Product</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="item_name" value="<?= htmlspecialchars($product['item_name']) ?>" required>
        <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required>
        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>
        
        <label>Current Image:</label><br>
        <?php if (!empty($product['image'])): ?>
            <img class="preview" src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="Current Image">
        <?php else: ?>
            <p style="color: #777;">No image uploaded.</p>
        <?php endif; ?>
        
        <label>Upload New Image (optional):</label>
        <input type="file" name="image" accept="image/*">
        
        <input type="submit" value="Update Product">
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
