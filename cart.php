<?php include 'db.php'; session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 60%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        .btn { padding: 6px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn-danger { background: #dc3545; }
    </style>
    <link rel="stylesheet" href="customer-style.css">
</head>
<body>
    <h2>Your Cart</h2>
    <a href="shop.php">← Continue Shopping</a>
    <?php
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        $ids = implode(",", array_keys($cart));
        $result = $conn->query("SELECT * FROM inventory WHERE id IN ($ids)");
        $total = 0;
        echo "<form method='post' action='update-cart.php'><table>
            <tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()):
            $qty = $cart[$row['id']];
            $subtotal = $qty * $row['price'];
            $total += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td>₹<?= $row['price'] ?></td>
            <td><input type="number" name="qty[<?= $row['id'] ?>]" value="<?= $qty ?>" min="1" /></td>
            <td>₹<?= $subtotal ?></td>
            <td><a href="update-cart.php?remove=<?= $row['id'] ?>" class="btn btn-danger">Remove</a></td>
        </tr>
        <?php endwhile; ?>
        <tr><td colspan="3"><strong>Total</strong></td><td colspan="2">₹<?= $total ?></td></tr>
        </table>
        <br>
        <input type="submit" value="Update Cart" class="btn">
        <a href="checkout.php" class="btn">Checkout</a>
        </form>
        <?php } ?>
        <script>
    // Toggle dark mode and store preference
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    }

    // Apply dark mode on load if previously set
    window.onload = function () {
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    };
</script>

</body>
</html>
