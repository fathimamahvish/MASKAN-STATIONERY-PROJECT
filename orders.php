<?php
include 'db.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM orders ORDER BY order_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Orders - Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            background-color: #f4f6f8;
        }
        h2 {
            text-align: center;
            color: #222;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        table, th, td {
            border: 1px solid #bbb;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #003366;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .back {
            text-align: center;
            margin-top: 20px;
        }
        .back a {
            color: #0066cc;
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="customer-style.css">

</head>
<body>
    <h2>All Customer Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Address</th>
            <th>Items</th>
            <th>Total</th>
            <th>Placed At</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['order_id'] ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= htmlspecialchars($row['items']) ?></td>
            <td>₹<?= $row['total'] ?></td>
            <td><?= $row['order_date'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div class="back">
        <a href="index.php">← Back to Admin Dashboard</a>
    </div>
</body>
</html>
