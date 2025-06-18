<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'db.php';

$query = "
    SELECT 
        users.username,
        users.phone,
        orders.order_date,
        orders.total_amount,
        orders.shipping_name,
        orders.shipping_address,
        orders.payment_id,
        orders.payment_method,
        orders.payment_status
    FROM orders
    LEFT JOIN users ON users.id = orders.user_id
    ORDER BY orders.order_date DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Orders Report | MASKAN STATIONERY SHOP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            padding: 30px;
            transition: background 0.3s, color 0.3s;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .dark-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .dark-toggle button {
            padding: 6px 12px;
            background: #37474f;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .dark-toggle button:hover {
            background: #546e7a;
        }

        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #3f51b5;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            font-weight: bold;
            color: #3f51b5;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* DARK MODE */
        body.dark-mode {
            background: #121212;
            color: #f0f0f0;
        }

        body.dark-mode table {
            background: #1e1e2f;
            color: #fff;
        }

        body.dark-mode th {
            background: #394867;
        }

        body.dark-mode td {
            background-color: #2a2a3a;
            border-color: #444;
        }

        body.dark-mode a.back-link {
            color: #90caf9;
        }
    </style>
</head>
<body>

    <div class="dark-toggle">
        <button onclick="toggleDarkMode()">🌗 Toggle Dark Mode</button>
    </div>

    <h1>Customer Orders Report</h1>

    <table>
        <tr>
            <th>Username</th>
            <th>Phone</th>
            <th>Order Date</th>
            <th>Total Amount (₹)</th>
            <th>Shipping Name</th>
            <th>Shipping Address</th>
            <th>Payment ID</th>
            <th>Payment Method</th>
            <th>Payment Status</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td><?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['shipping_name']) ?></td>
                    <td><?= htmlspecialchars($row['shipping_address']) ?></td>
                    <td><?= htmlspecialchars($row['payment_id'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['payment_method'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['payment_status'] ?? 'Pending') ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9">No orders found.</td></tr>
        <?php endif; ?>
    </table>

    <a class="back-link" href="admin-dashboard.php">← Back to Dashboard</a>

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
