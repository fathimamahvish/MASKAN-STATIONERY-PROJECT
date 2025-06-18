<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Load profile picture
$username = $_SESSION['username'];
$stmt_pic = $conn->prepare("SELECT profile_picture FROM users WHERE username = ?");
$stmt_pic->bind_param("s", $username);
$stmt_pic->execute();
$result_pic = $stmt_pic->get_result();
$row_pic = $result_pic->fetch_assoc();

$profile_picture = (!empty($row_pic['profile_picture']) && file_exists('uploads/' . $row_pic['profile_picture']))
    ? 'uploads/' . $row_pic['profile_picture']
    : 'default-avatar.png';

// Set low stock threshold
$lowStockThreshold = 5;
$stmt_low_stock = $conn->prepare("SELECT * FROM inventory WHERE quantity <= ? AND is_deleted = 0");
$stmt_low_stock->bind_param("i", $lowStockThreshold);
$stmt_low_stock->execute();
$lowStockResult = $stmt_low_stock->get_result();

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE is_deleted = 0 AND item_name LIKE ? ORDER BY id DESC");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
} else {
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE is_deleted = 0 ORDER BY id DESC");
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - MASKAN STATIONERY SHOP</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(to right, #e3f2fd, #fce4ec);
            color: #333;
            transition: background 0.3s, color 0.3s;
        }
        .container { width: 95%; max-width: 1200px; margin: auto; }
        .header { text-align: center; padding: 20px; }
        .logo-title {
            display: flex; align-items: center; justify-content: center; gap: 15px;
        }
        .logo-title img { height: 80px; border-radius: 50%; }
        h1 { font-size: 28px; margin: 10px 0 0; }

        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 15px; flex-wrap: wrap;
        }
        .top-bar .info, .top-bar .links {
            display: flex; align-items: center; gap: 15px; flex-wrap: wrap;
        }
        .top-bar a {
            color: #007bff; text-decoration: none; font-weight: bold;
        }
        .top-bar a:hover { text-decoration: underline; }
        .top-bar img {
            width: 35px; height: 35px; object-fit: cover; border-radius: 50%;
        }

        .alert {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .search-bar {
            text-align: center; margin: 20px 0;
        }
        .search-bar input[type="text"] {
            padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 5px;
        }
        .search-bar input[type="submit"] {
            padding: 8px 16px; background-color: #007bff;
            border: none; color: white; font-weight: bold;
            border-radius: 5px; cursor: pointer;
        }
        .search-bar input[type="submit"]:hover { background-color: #0056b3; }

        .action-buttons {
            text-align: center; margin-bottom: 20px;
        }
        .action-buttons a {
            margin: 0 10px; color: #28a745;
            text-decoration: none; font-weight: bold;
        }

        .table-wrapper { overflow-x: auto; }
        table {
            width: 100%; border-collapse: collapse;
            background: #fff; box-shadow: 0 0 10px #ccc;
        }
        th, td {
            padding: 12px; border: 1px solid #ddd; text-align: center;
        }
        th { background-color: #1976d2; color: white; }
        tr:hover { background-color: #f9f9f9; }

        .dark-toggle {
            position: fixed; top: 20px; left: 20px; z-index: 1000;
        }
        .dark-toggle button {
            padding: 7px 12px; background-color: #333;
            color: white; border: none; border-radius: 5px; cursor: pointer;
        }
        .dark-toggle button:hover { background-color: #444; }

        body.dark-mode {
            background: #1e1e2f; color: #e0e0e0;
        }
        body.dark-mode table { background-color: #2c2c3e; color: #e0e0e0; }
        body.dark-mode th { background-color: #37474f; }
        body.dark-mode td { background-color: #2e3b4e; }
        body.dark-mode a { color: #90caf9; }
        body.dark-mode input[type="text"] {
            background-color: #37474f; color: white; border: 1px solid #666;
        }
        body.dark-mode input[type="submit"] {
            background-color: #4fc3f7; color: #000;
        }
    </style>
</head>

<body>

<div class="dark-toggle">
    <button onclick="toggleDarkMode()">🌙 Toggle Dark Mode</button>
</div>

<div class="container">
    <div class="header">
        <div class="logo-title">
            <img src="logo.png" alt="Logo">
            <h1>MASKAN STATIONERY SHOP</h1>
        </div>
    </div>

    <div class="top-bar">
        <div class="info">
            <span>Logged in as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
        </div>
        <div class="links">
            <a href="admin-profile.php">
                <img src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile">
            </a>
            <a href="report.php">📊 Report</a>
            <a href="logout.php" style="color: #dc3545;">Logout</a>
        </div>
    </div>

    <!-- 🔔 Low Stock Alert -->
    <?php if ($lowStockResult->num_rows > 0): ?>
        <div class="alert">
            <strong>⚠ Low Stock Alert:</strong>
            <ul>
                <?php while($item = $lowStockResult->fetch_assoc()): ?>
                    <li><strong><?= htmlspecialchars($item['item_name']) ?></strong> - Only <?= $item['quantity'] ?> left</li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="search-bar">
        <form method="get">
            <input type="text" name="search" placeholder="Search item..." value="<?= htmlspecialchars($search) ?>">
            <input type="submit" value="Search">
        </form>
    </div>

    <div class="action-buttons">
        <a href="add.php">+ Add New Item</a> |
        <a href="trash.php">🗑 View Deleted Items</a>
    </div>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Price (₹)</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>
                            <?php if (!empty($row['image']) && file_exists("uploads/" . $row['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Image" style="width:60px; height:60px; object-fit:cover; border-radius: 50%;">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>">✏️ Edit</a> |
                            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this item?');">🗑 Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No items found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

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
