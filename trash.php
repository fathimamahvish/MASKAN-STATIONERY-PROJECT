<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'db.php';

$result = $conn->query("SELECT * FROM inventory WHERE is_deleted = 1");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deleted Items - MASKAN STATIONERY SHOP</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; text-align: center; }
        table {
            margin: auto;
            margin-top: 30px;
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        h2 {
            margin-top: 30px;
        }
    </style>
    <link rel="stylesheet" href="customer-style.css">
</head>
<body>
    <h2>Deleted Items</h2>
    <a href="admin-dashboard.php">⬅ Back to Inventory</a><br><br>

    <table>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><a href="restore.php?id=<?= $row['id'] ?>">Restore</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
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
