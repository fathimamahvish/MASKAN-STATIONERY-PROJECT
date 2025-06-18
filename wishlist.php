<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Get user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit;
}

$user_id = $user['id'];

// Handle remove request
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    $removeStmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $removeStmt->bind_param("ii", $user_id, $product_id);
    $removeStmt->execute();
    header("Location: wishlist.php");
    exit;
}

// Fetch wishlist items
$sql = "SELECT p.* FROM wishlist w JOIN inventory p ON w.product_id = p.id WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wishlist | MASKAN STATIONERY SHOP</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 0;
            background: #f6f6f6;
            transition: background 0.3s, color 0.3s;
        }
        .navbar {
            background: linear-gradient(90deg, #2196f3, #e91e63);
            padding: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 1em;
        }
        .toggle-btn {
            cursor: pointer;
            padding: 6px 12px;
            background-color: white;
            color: #2196f3;
            border-radius: 5px;
            font-weight: bold;
        }
        .container {
            padding: 2em;
        }
        .product {
            background: white;
            padding: 1em;
            margin-bottom: 1em;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: background 0.3s;
        }
        .product img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            background: #eee;
        }
        .product-details {
            flex-grow: 1;
        }
        .product h3 {
            margin: 0 0 0.5em;
        }
        .product p {
            margin: 0.2em 0;
        }
        .remove-btn {
            background-color: #e53935;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .remove-btn:hover {
            background-color: #c62828;
        }
        .empty {
            text-align: center;
            margin-top: 3em;
            color: #888;
        }

        /* Dark Mode */
        body.dark {
            background: #121212;
            color: #eee;
        }
        body.dark .navbar {
            background: linear-gradient(90deg, #1e88e5, #d81b60);
        }
        body.dark .product {
            background: #1e1e1e;
        }
        body.dark .remove-btn {
            background-color: #ff5252;
        }
        body.dark .remove-btn:hover {
            background-color: #e53935;
        }
        body.dark .toggle-btn {
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <strong>MASKAN STATIONERY SHOP</strong>
        <div>
            <a href="shop.php">Shop</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php" style="color: red;">Logout</a>
            <button class="toggle-btn" onclick="toggleTheme()">🌙 Dark Mode</button>
        </div>
    </div>

    <div class="container">
        <h2>Your Wishlist</h2>
        <?php if ($products->num_rows > 0): ?>
            <?php while ($row = $products->fetch_assoc()): ?>
                <div class="product">
                    <?php if (!empty($row['image']) && file_exists("uploads/" . $row['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['item_name']) ?>">
                    <?php else: ?>
                        <div style="width:120px; height:120px; background:#ccc; display:flex; align-items:center; justify-content:center; border-radius:10px;">No Image</div>
                    <?php endif; ?>
                    <div class="product-details">
                        <h3><?= htmlspecialchars($row['item_name']) ?></h3>
                        <p>Price: ₹<?= number_format($row['price'], 2) ?></p>
                        <p>Stock: <?= $row['quantity'] ?></p>
                    </div>
                    <a href="wishlist.php?remove=<?= $row['id'] ?>" class="remove-btn">Remove</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty">Your wishlist is empty.</p>
        <?php endif; ?>
    </div>

    <script>
        // Load theme preference
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark");
            document.querySelector('.toggle-btn').innerText = '🌞 Light Mode';
        }

        function toggleTheme() {
            const btn = document.querySelector('.toggle-btn');
            document.body.classList.toggle("dark");
            if (document.body.classList.contains("dark")) {
                localStorage.setItem("theme", "dark");
                btn.innerText = '🌞 Light Mode';
            } else {
                localStorage.setItem("theme", "light");
                btn.innerText = '🌙 Dark Mode';
            }
        }
    </script>
</body>
</html>
