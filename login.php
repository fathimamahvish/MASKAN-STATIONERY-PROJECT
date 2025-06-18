<?php
session_start();
include 'db.php';

require_once __DIR__ . '/vendor/autoload.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $selectedRole = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $selectedRole);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin-dashboard.php");
            } else {
                header("Location: shop.php");
            }
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username, password or role.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login - MASKAN STATIONERY SHOP</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-box {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 450px;
        }

        .login-box h2 {
            margin-bottom: 15px;
            color: #3949ab;
        }

        .login-box h3 {
            margin-bottom: 30px;
            color: #ec407a;
            font-size: 1.5em;
        }

        input[type=text],
        input[type=password],
        select {
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

        .error {
            color: red;
            margin-bottom: 20px;
            font-size: 0.9em;
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

        body.dark-mode .login-box {
            background-color: #2c2c3c;
            color: #f1f1f1;
        }

        body.dark-mode input[type=text],
        body.dark-mode input[type=password],
        body.dark-mode select {
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

        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #3949ab;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9em;
            z-index: 1000;
        }

        .home-button:hover {
            background-color: #1a237e;
        }

        body.dark-mode .home-button {
            background-color: #f48fb1;
            color: black;
        }

        .google-signin-container {
            margin-top: 30px;
        }

        .g_id_signin {
            display: flex;
            justify-content: center;
        }
    </style>

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        function handleCredentialResponse(response) {
            fetch('google-login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_token: response.credential
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'index.php';
                    } else {
                        alert('Login failed: ' + data.message);
                    }
                });
        }
    </script>
</head>

<body>
    <a href="index.php" class="home-button">🏠 Home</a>

    <div class="container">
        <div class="login-box">
            <div class="logo-title">
                <img src="logo.png" alt="Maskan Logo">
                <h1>MASKAN STATIONERY SHOP</h1>
            </div>

            <h3>Login</h3>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <select name="role" required>
                    <option value="">-- Select Role --</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select><br>
                <input type="submit" value="Login">
            </form>

            <div class="google-signin-container">
                <div id="g_id_onload"
                    data-client_id="1089209690631-8i32omasmf87eum1curji6mf2m61n27s.apps.googleusercontent.com"
                    data-callback="handleCredentialResponse"
                    data-auto_prompt="false">
                </div>
                <div class="g_id_signin"
                    data-type="standard"
                    data-size="large"
                    data-theme="outline"
                    data-text="sign_in_with"
                    data-shape="rectangular"
                    data-logo_alignment="left">
                </div>
            </div>

            <div class="link">
                <a href="register.php">Don't have an account? Register</a>
            </div>

            <div class="dark-toggle">
                <button onclick="toggleDarkMode()">🌙 Toggle Dark Mode</button>
            </div>
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

