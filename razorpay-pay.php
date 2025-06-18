<?php
session_start();
if (!isset($_SESSION['razorpay_order'])) {
    header("Location: checkout.php");
    exit;
}

$order = $_SESSION['razorpay_order'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body style="text-align:center; padding-top:50px; font-family:Arial;">

    <h2>Processing Payment...</h2>
    <p>Order Total: ₹<?= number_format($order['total_amount'], 2) ?></p>

    <script>
        var options = {
            "key": "rzp_test_jR08RuiE1NWjOR", // Replace with your Razorpay Test API Key
            "amount": <?= $order['total_amount'] * 100 ?>, // Amount in paisa
            "currency": "INR",
            "name": "Maskan Stationery Shop",
            "description": "Order #<?= $order['order_id'] ?>",
            "image": "logo.png", // Optional
            "handler": function (response) {
                // Send payment_id & order_id to your server
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "razorpay-callback.php";

                var paymentIdInput = document.createElement("input");
                paymentIdInput.type = "hidden";
                paymentIdInput.name = "razorpay_payment_id";
                paymentIdInput.value = response.razorpay_payment_id;
                form.appendChild(paymentIdInput);

                var orderIdInput = document.createElement("input");
                orderIdInput.type = "hidden";
                orderIdInput.name = "order_id";
                orderIdInput.value = "<?= $order['order_id'] ?>";
                form.appendChild(orderIdInput);

                document.body.appendChild(form);
                form.submit();
            },
            "prefill": {
                "name": "<?= htmlspecialchars($order['name']) ?>",
                "email": "<?= $_SESSION['username'] ?>@example.com", // Replace or store real email if available
                "contact": "<?= $order['phone'] ?? '9999999999' ?>" // Placeholder phone
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>

    <p>Please do not refresh this page.</p>
</body>

</html>