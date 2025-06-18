<?php
session_start();

if (isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
}

header("Location: cart.php");
exit;
