<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $profile_picture = $_FILES['profile_picture'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Begin building update SQL
    $update_sql = "UPDATE users SET phone = ?";
    $params = [$phone];
    $types = "s";

    // Handle profile picture update
    if ($profile_picture['size'] > 0) {
        $file_name = basename($profile_picture['name']);
        $target = "uploads/" . $file_name;

        if (move_uploaded_file($profile_picture['tmp_name'], $target)) {
            $update_sql .= ", profile_picture = ?";
            $types .= "s";
            $params[] = $file_name;
        } else {
            $message = "Failed to upload image.";
        }
    }

    // Handle password change
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            $message = "New passwords do not match.";
        } else {
            // Verify old password
            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();

            if ($userData && password_verify($current_password, $userData['password'])) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql .= ", password = ?";
                $types .= "s";
                $params[] = $hashed_password;
            } else {
                $message = "Current password is incorrect.";
            }
        }
    }

    // Finalize update if no error
    if (empty($message)) {
        $update_sql .= " WHERE username = ?";
        $types .= "s";
        $params[] = $username;

        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
            header("Location: admin-profile.php");
            exit;
        } else {
            $message = "Update failed.";
        }
    }
}

// Fetch existing user info
$stmt = $conn->prepare("SELECT phone FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e3f2fd;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .edit-box {
            width: 400px;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #3f51b5;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background: #3f51b5;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .dark-toggle button {
            margin-top: 15px;
            background-color: #3949ab;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .message {
            margin-top: 10px;
            color: green;
            text-align: center;
        }

        body.dark-mode {
            background: linear-gradient(to right, #2c2c3c, #1f2233);
            color: #f1f1f1;
        }

        body.dark-mode .edit-box {
            background-color: #2c2c3c;
            color: #f1f1f1;
        }

        body.dark-mode input[type="text"],
        body.dark-mode input[type="file"],
        body.dark-mode input[type="password"] {
            background-color: #444;
            color: white;
            border: 1px solid #777;
        }

        body.dark-mode input[type="submit"] {
            background-color: #f06292;
            color: black;
        }

        body.dark-mode .dark-toggle button {
            background-color: #f48fb1;
            color: black;
        }
    </style>
</head>
<body>

<div class="edit-box">
    <h2>Edit Profile</h2>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">

        <label for="profile_picture">New Profile Picture:</label>
        <input type="file" name="profile_picture">

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #ccc;">

        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password">

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password">

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password">

        <input type="submit" value="Update Profile">
    </form>

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
