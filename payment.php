<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header("Location: shop.php");
    exit;
}

$name = $_POST['name'];
$address = $_POST['address'];
$payment_method = $_POST['payment_method'];

$total = 0;
$ids = implode(",", array_keys($cart));
$result = $conn->query("SELECT * FROM inventory WHERE id IN ($ids)");

$product_prices = [];
while ($row = $result->fetch_assoc()) {
    $product_id = $row['id'];
    $price = $row['price'];
    $qty = $cart[$product_id];
    $subtotal = $price * $qty;
    $total += $subtotal;
    $product_prices[$product_id] = $price;
}

// Insert order into orders table
$status = ($payment_method == 'COD') ? 'Pending' : 'Initiated';
$stmt = $conn->prepare("INSERT INTO orders (user_id, shipping_name, shipping_address, total_amount, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issdss", $user_id, $name, $address, $total, $payment_method, $status);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert order items
foreach ($cart as $product_id => $qty) {
    $price = $product_prices[$product_id];
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt_item->bind_param("iiid", $order_id, $product_id, $qty, $price);
    $stmt_item->execute();
}

if ($payment_method === 'COD') {
    // Update inventory immediately
    foreach ($cart as $product_id => $qty) {
        $conn->query("UPDATE inventory SET quantity = quantity - $qty WHERE id = $product_id");
    }

    unset($_SESSION['cart']);
    header("Location: order-success.php");
    exit;
} else {
    // Redirect to Razorpay payment page with order ID and total
    $_SESSION['razorpay_order'] = [
        'order_id' => $order_id,
        'total' => $total
    ];

    header("Location: razorpay-pay.php");
    exit;
}
?>
