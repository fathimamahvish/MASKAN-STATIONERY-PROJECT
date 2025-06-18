<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

// Calculate total
$total = 0;
if (!empty($cart)) {
    $ids = implode(",", array_keys($cart));
    $result = $conn->query("SELECT * FROM inventory WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $qty = $cart[$row['id']] ?? 0;
        $subtotal = $row['price'] * $qty;
        $total += $subtotal;
    }
} else {
    header("Location: shop.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Checkout - MASKAN STATIONERY SHOP</title>
    <link rel="stylesheet" href="customer-style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fce4ec;
            padding: 30px;
        }

        .checkout-box {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #3949ab;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            margin-top: 20px;
            padding: 10px 16px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background: #218838;
        }

        .total {
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
            color: #333;
        }

        .logo-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo-title img {
            height: 80px;
            width: auto;
            border-radius: 50%;
        }

        .logo-title h1 {
            font-size: 1.6em;
            background: linear-gradient(to right, #3949ab, #ec407a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dark-mode {
            background-color: #1f2233;
            color: #f1f1f1;
        }

        .dark-mode .checkout-box {
            background: #2c2c3c;
        }

        .dark-mode input,
        .dark-mode textarea,
        .dark-mode select {
            background: #3a3a3a;
            color: #f1f1f1;
            border: 1px solid #777;
        }

        .dark-mode label {
            color: #fce4ec;
        }

        .dark-mode .btn {
            background-color: #f06292;
        }

        .dark-mode .btn:hover {
            background-color: #c2185b;
        }

        .dark-mode .total {
            color: #fce4ec;
        }
    </style>
</head>

<body>

    <div class="checkout-box">
        <div class="logo-title">
            <img src="logo.png" alt="Maskan Logo">
            <h1>MASKAN STATIONERY SHOP</h1>
        </div>

        <h2>Checkout</h2>
        <p class="total"><strong>Total: ₹<?= number_format($total, 2) ?></strong></p>

        <form id="checkout-form">
            <label>Name:</label>
            <input type="text" id="name" required>

            <label>Address:</label>
            <textarea id="address" rows="4" required></textarea>

            <label>Payment Method:</label>
            <select id="payment_method" required>
                <option value="">-- Select --</option>
                <option value="COD">Cash on Delivery</option>
                <option value="Online">Razorpay (Test)</option>
            </select>

            <input type="button" value="Proceed to Payment" class="btn" onclick="handleCheckout()">
        </form>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function handleCheckout() {
            const name = document.getElementById('name').value;
            const address = document.getElementById('address').value;
            const method = document.getElementById('payment_method').value;

            if (!name || !address || !method) {
                alert('Please fill in all fields.');
                return;
            }

            if (method === 'COD') {
                // Simple redirect for COD (use POST for production)
                window.location.href = `order-success.php?method=COD&name=${encodeURIComponent(name)}&address=${encodeURIComponent(address)}`;
            } else if (method === 'Online') {
                const options = {
                    "key": "rzp_test_jR08RuiE1NWjOR", // Replace with your Razorpay Test Key
                    "amount": <?= $total * 100 ?>, // Amount in paise
                    "currency": "INR",
                    "name": "MASKAN STATIONERY SHOP",
                    "description": "Order Payment",
                    "handler": function (response) {
                        window.location.href = `order-success.php?method=Razorpay&payment_id=${response.razorpay_payment_id}&name=${encodeURIComponent(name)}&address=${encodeURIComponent(address)}`;
                    },
                    "prefill": {
                        "name": name,
                        "email": "", // Optional
                        "contact": "" // Optional
                    },
                    "theme": {
                        "color": "#3949ab"
                    }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            }
        }

        // Dark Mode Toggle (Optional)
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>

</html>

