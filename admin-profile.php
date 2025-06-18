<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT username, phone, profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile | MASKAN STATIONERY SHOP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e3f2fd;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            max-width: 500px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background: #fff;
            text-align: center;
        }

        h2 {
            color: #3f51b5;
        }

        .profile-img img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #3f51b5;
            margin-bottom: 20px;
        }

        .profile-info {
            margin-bottom: 20px;
            text-align: left;
        }

        .profile-info p {
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin: 5px;
        }

        .btn-shop {
            background: #3f51b5;
            color: white;
        }

        .btn-edit {
            background: #f06292;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .dark-toggle button {
            margin-top: 15px;
            background-color: #3949ab;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .dark-toggle button:hover {
            background-color: #1a237e;
        }

        body.dark-mode {
            background: linear-gradient(to right, #2c2c3c, #1f2233);
            color: #f1f1f1;
        }

        body.dark-mode .profile-container {
            background-color: #2c2c3c;
        }

        body.dark-mode .btn-shop {
            background-color: #90caf9;
            color: black;
        }

        body.dark-mode .btn-edit {
            background-color: #f48fb1;
            color: black;
        }

        body.dark-mode .dark-toggle button {
            background-color: #f48fb1;
            color: #000;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>User Profile</h2>

    <div class="profile-img">
        <img src="<?= file_exists("uploads/" . $user['profile_picture']) ? 'uploads/' . htmlspecialchars($user['profile_picture']) : 'default-avatar.png' ?>" alt="Profile Picture">
    </div>

    <div class="profile-info">
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not Provided') ?></p>
    </div>

    <a href="admin-dashboard.php" class="btn btn-shop">← Back to Shop</a>
    <a href="admin-edit-profile.php" class="btn btn-edit">✏️ Edit Profile</a>

    <div class="dark-toggle">
        <button onclick="toggleDarkMode()">🌙 Toggle Dark Mode</button>
    </div>
</div>

<script>
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
}
window.onload = () => {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
};
</script>

</body>
</html>

