<?php
include 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $phone = trim($_POST['phone']);
    $role = 'customer'; // Force all registrations as customers

    // Handle profile picture upload
    $profile_picture = null;
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES['profile_picture']['name']);
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed)) {
            $new_filename = uniqid("profile_", true) . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            if (move_uploaded_file($file_tmp, $target_file)) {
                $profile_picture = $new_filename;
            } else {
                $message = "Failed to upload profile picture.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Username or email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, phone, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $password, $role, $phone, $profile_picture);
        if ($stmt->execute()) {
            $message = "Registration successful! <a href='login.php'>Login now</a>";
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register - MASKAN STATIONERY SHOP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .register-box {
            background-color: #ffffff;
            padding: 50px 40px;
            border-radius: 55px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 450px;
            margin: 40px 0px;
        }

        .register-box h2 {
            margin-bottom: 15px;
            color: #3949ab;
        }

        .register-box h3 {
            margin-bottom: 30px;
            color: #ec407a;
            font-size: 1.5em;
        }

        input[type=text],
        input[type=password],
        input[type=email],
        input[type=file] {
            width: 90%;
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        input[type=submit] {
            padding: 12px 30px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
            margin-top: 10px;
        }

        input[type=submit]:hover {
            background-color: #218838;
        }

        .message {
            margin-top: 15px;
            font-size: 0.95em;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        .link {
            margin-top: 20px;
        }

        .link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .dark-toggle {
            margin-top: 25px;
        }

        .dark-toggle button {
            background-color: #3949ab;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .dark-toggle button:hover {
            background-color: #1a237e;
        }

        body.dark-mode {
            background: linear-gradient(to right, #2c2c3c, #1f2233);
            color: #f1f1f1;
        }

        body.dark-mode .register-box {
            background-color: #2c2c3c;
            color: #f1f1f1;
        }

        body.dark-mode input[type=text],
        body.dark-mode input[type=email],
        body.dark-mode input[type=password],
        body.dark-mode input[type=file] {
            background-color: #3a3a3a;
            color: #f1f1f1;
            border: 1px solid #777;
        }

        body.dark-mode input[type=submit] {
            background-color: #f06292;
        }

        body.dark-mode .link a {
            color: #90caf9;
        }

        body.dark-mode .dark-toggle button {
            background-color: #f48fb1;
            color: #000;
        }

        .logo-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .logo-title img {
            height: 120px;
            width: auto;
            border-radius: 50%;
        }

        .logo-title h1 {
            font-size: 2em;
            color: #3949ab;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: linear-gradient(to right, #3949ab, #ec407a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body>
    <div class="register-box">
        <div class="logo-title">
            <img src="logo.png" alt="Maskan Logo">
            <h1>MASKAN STATIONERY SHOP</h1>
        </div>

        <h3>Register</h3>

        <?php if (!empty($message)): ?>
            <p class="message <?= strpos($message, 'successful') !== false ? 'success' : 'error' ?>">
                <?= $message ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="text" name="phone" placeholder="Phone Number" required><br>
            <input type="file" name="profile_picture" accept="image/*"><br>
            <input type="submit" value="Register">
        </form>

        <div class="link">
            <a href="login.php">Already have an account? Login</a>
        </div>

        <div class="dark-toggle">
            <button onclick="toggleDarkMode()">🌙 Toggle Dark Mode</button>
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
