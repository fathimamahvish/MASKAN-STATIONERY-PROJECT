<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || !isset($_GET['id'])) {
    header("Location: wishlist.php");
    exit;
}

$username = $_SESSION['username'];
$product_id = intval($_GET['id']);

// Get user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $user_id = $user['id'];

    $delete = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $delete->bind_param("ii", $user_id, $product_id);
    $delete->execute();
}

header("Location: wishlist.php");
exit;
?>
