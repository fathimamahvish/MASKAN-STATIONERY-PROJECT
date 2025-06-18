<?php
include 'db.php';
session_start();

// Ensure it's a POST request with required fields
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['razorpay_payment_id'], $_POST['order_id'])) {
    $payment_id = $_POST['razorpay_payment_id'];
    $order_id = (int) $_POST['order_id'];

    // Update the order in the database
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'Paid', payment_id = ?, payment_method = 'Razorpay' WHERE id = ?");
    $stmt->bind_param("si", $payment_id, $order_id);
    $stmt->execute();

    // Clear cart
    unset($_SESSION['cart']);
    unset($_SESSION['razorpay_order']);

    // Respond with success
    http_response_code(200);
    echo "Payment recorded successfully.";
    exit;
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
