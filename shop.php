<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$profile_picture = !empty($user['profile_picture']) && file_exists("uploads/" . $user['profile_picture'])
    ? "uploads/" . $user['profile_picture']
    : "default-user.png";

// Search logic
$search_query = "";
$search_term = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE is_deleted = 0 AND quantity > 0 AND item_name LIKE ?");
    $like = "%" . $search_term . "%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    $products = $conn->query("SELECT * FROM inventory WHERE is_deleted = 0 AND quantity > 0");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop | MASKAN STATIONERY SHOP</title>
    <link rel="stylesheet" href="customer-style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
        }

        .header-bar {
            background-color: #2196f3;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 32px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .logo-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-title img {
            height: 60px;
            width: auto;
            border-radius: 50%;
        }

        .header-bar h1 {
            margin: 0;
            font-size: 22px;
        }

        .header-links {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-links a {
            color: #fff;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .header-links a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .header-links img {
            height: 28px;
            width: 28px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid white;
        }

        .toggle-btn {
            cursor: pointer;
            padding: 6px 10px;
            border: none;
            background-color: #fff;
            color: #2196f3;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .toggle-btn:hover {
            background-color: #f1f1f1;
        }

        .search-bar {
            max-width: 600px;
            margin: 20px auto;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 70%;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .search-bar button {
            padding: 10px 18px;
            background-color: #2196f3;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #1976d2;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
            padding: 40px 30px;
            max-width: 1200px;
            margin: auto;
        }

        .product {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
            position: relative;
        }

        .product:hover {
            transform: scale(1.03);
        }

        .product img {
            width: 100%;
            height: 160px;
            object-fit: contain;
            border-radius: 8px;
            background: #f1f1f1;
        }

        .product h3 {
            margin: 12px 0 6px;
            color: #333;
        }

        .product p {
            margin: 4px 0;
            color: #555;
        }

        .btn {
            background-color: #28a745;
            color: white;
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #218838;
        }

        .wishlist-icon {
            font-size: 20px;
            color: #e91e63;
            position: absolute;
            top: 10px;
            right: 15px;
            text-decoration: none;
        }

        .wishlist-icon:hover {
            color: #c2185b;
        }

        .dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        .dark-mode .product {
            background-color: #1f1f1f;
        }

        .dark-mode .header-bar {
            background-color: #1a1a1a;
        }

        .dark-mode .btn {
            background-color: #4caf50;
        }

        .dark-mode .btn:hover {
            background-color: #388e3c;
        }

        .dark-mode .toggle-btn {
            background-color: #333;
            color: #eee;
        }

        .dark-mode .header-links a {
            color: #ddd;
        }

        .dark-mode .header-links a:hover {
            background-color: #333;
        }

        .dark-mode .wishlist-icon {
            color: #ff80ab;
        }

        .dark-mode .wishlist-icon:hover {
            color: #ff4081;
        }

        .no-results {
            text-align: center;
            margin: 50px auto;
            font-size: 20px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="header-bar">
        <div class="logo-title">
            <img src="logo.png" alt="Maskan Logo">
            <h1>MASKAN STATIONERY SHOP</h1>
        </div>

        <div class="header-links">
            <span>👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="profile.php" title="View Profile"><img src="<?= $profile_picture ?>" alt="Profile Picture"></a>
            <a href="cart.php" title="View Cart">🛒 Cart</a>
            <a href="wishlist.php" title="Wishlist">❤️ Wishlist</a>
            <a href="logout.php" style="background-color:#e53935;">Logout</a>
            <button class="toggle-btn" onclick="toggleDarkMode()">🌙</button>
        </div>
    </div>

    <form class="search-bar" method="GET" action="shop.php">
        <input type="text" name="search" placeholder="Search for products..." value="<?= htmlspecialchars($search_term) ?>">
        <button type="submit">Search</button>
    </form>

    <?php if ($products->num_rows > 0): ?>
    <div class="product-grid">
        <?php while ($row = $products->fetch_assoc()): ?>
            <div class="product">
                <a href="add-to-wishlist.php?id=<?= $row['id'] ?>" class="wishlist-icon" title="Add to Wishlist">❤️</a>
                <?php if (!empty($row['image']) && file_exists("uploads/" . $row['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['item_name']) ?>">
                <?php else: ?>
                    <div style="width:100%; height:160px; display:flex; align-items:center; justify-content:center; background:#eee; border-radius:8px;">No Image</div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['item_name']) ?></h3>
                <p>Price: ₹<?= number_format($row['price'], 2) ?></p>
                <p>Stock: <?= $row['quantity'] ?></p>
                <a href="add-to-cart.php?id=<?= $row['id'] ?>" class="btn">Add to Cart</a>
            </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
        <p class="no-results">No products found for "<?= htmlspecialchars($search_term) ?>".</p>
    <?php endif; ?>

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
