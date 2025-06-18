<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit;
}

$product_id = intval($_GET['id']);
$username = $_SESSION['username'];

// Get user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: shop.php");
    exit;
}

$user_id = $user['id'];

// Check if already in wishlist
$check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    // Add to wishlist
    $add = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $add->bind_param("ii", $user_id, $product_id);
    $add->execute();
}

// Redirect
header("Location: wishlist.php");
exit;
?>

