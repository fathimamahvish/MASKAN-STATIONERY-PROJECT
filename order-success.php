<?php
include 'db.php';
session_start();

// ✅ Ensure customer is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

$name = $_REQUEST['name'] ?? '';
$address = $_REQUEST['address'] ?? '';
$method = $_REQUEST['method'] ?? '';
$payment_id = $_REQUEST['payment_id'] ?? null;

if (!$name || !$address || !$method || empty($cart)) {
    header("Location: shop.php");
    exit;
}

$total = 0;

// Fetch cart item details
$ids = implode(",", array_keys($cart));
$result = $conn->query("SELECT * FROM inventory WHERE id IN ($ids)");

// Create the order (initially 0 total)
$stmt = $conn->prepare("INSERT INTO orders (user_id, shipping_name, shipping_address, payment_method, payment_id, total_amount) VALUES (?, ?, ?, ?, ?, 0)");
$stmt->bind_param("issss", $user_id, $name, $address, $method, $payment_id);
$stmt->execute();
$order_id = $stmt->insert_id;

// Add each item to order_items
while ($row = $result->fetch_assoc()) {
    $product_id = $row['id'];
    $qty = $cart[$product_id];
    $price = $row['price'];
    $subtotal = $qty * $price;
    $total += $subtotal;

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $product_id, $qty, $price);
    $stmt->execute();

    // Decrease inventory quantity
    $conn->query("UPDATE inventory SET quantity = quantity - $qty WHERE id = $product_id");
}

// Update final total
$conn->query("UPDATE orders SET total_amount = $total WHERE id = $order_id");

// Clear cart
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Order Successful</title>
    <link rel="stylesheet" href="customer-style.css">
    <style>
        body {
            font-family: Arial;
            padding: 40px;
            text-align: center;
            background-color: #fce4ec;
        }

        h1 {
            color: #3949ab;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn:hover {
            background: #0056b3;
        }

        body.dark-mode {
            background-color: #1f2233;
            color: #f1f1f1;
        }

        .dark-mode .btn {
            background-color: #f06292;
        }

        .dark-mode .btn:hover {
            background-color: #c2185b;
        }
    </style>
</head>

<body>
    <h1>Thank you for your order!</h1>
    <p>Your order (ID: <?= $order_id ?>) has been placed successfully.</p>
    <p>Payment Method: <strong><?= htmlspecialchars($method) ?></strong></p>
    <?php if ($method === 'Razorpay' && $payment_id): ?>
        <p>Razorpay Payment ID: <strong><?= htmlspecialchars($payment_id) ?></strong></p>
    <?php endif; ?>
    <a href="shop.php" class="btn">Continue Shopping</a>

    <script>
        window.onload = function () {
            if (localStorage.getItem('darkMode') === 'true') {
                document.body.classList.add('dark-mode');
            }
        };
    </script>
</body>

</html>
