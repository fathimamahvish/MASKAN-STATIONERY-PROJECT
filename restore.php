<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("UPDATE inventory SET is_deleted = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: trash.php");
exit;
