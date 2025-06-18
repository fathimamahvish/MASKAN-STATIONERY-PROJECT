<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome | MASKAN STATIONERY SHOP</title>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(to right, #e1f5fe, #fce4ec);
            font-family: 'Segoe UI', sans-serif;
            transition: background 0.5s ease;
            overflow-x: hidden;
        }

        .dark-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .dark-toggle button {
            background: #333;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .dark-toggle button:hover {
            background: #555;
        }

        .nav-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .nav-buttons a {
            background: #2196f3;
            color: white;
            padding: 8px 14px;
            margin-left: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .nav-buttons a:hover {
            background: #1976d2;
        }

        .container {
            text-align: center;
            color: #333;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease;
            margin: 80px auto 30px auto;
            z-index: 2;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn-start {
            background: #2196f3;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
            display: inline-block;
        }

        .btn-start:hover {
            background: #1976d2;
        }

        body.dark-mode {
            background: linear-gradient(to right, #0f172a, #1e1e2f);
            color: #eee;
        }

        body.dark-mode .container,
        body.dark-mode .about-box,
        body.dark-mode .product-gallery {
            background: rgba(40, 40, 50, 0.85);
            color: #f0f0f0;
        }

        body.dark-mode .btn-start {
            background: #90caf9;
            color: #111;
        }

        body.dark-mode .btn-start:hover {
            background: #64b5f6;
        }

        body.dark-mode .nav-buttons a {
            background: #90caf9;
            color: #111;
        }

        body.dark-mode .about-box h2,
        body.dark-mode .about-box p,
        body.dark-mode .product-gallery h2 {
            color: #fff;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo-title img {
            height: 100px;
            width: auto;
            border-radius: 50%;
        }

        .about-us {
            background: linear-gradient(135deg, #e0f7fa 0%, #fce4ec 100%);
            padding: 70px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .about-box {
            max-width: 900px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            animation: fadeInUp 1s ease-in-out;
        }

        .about-box h2 {
            font-size: 34px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #222;
        }

        .about-box p {
            font-size: 18px;
            line-height: 1.7;
            color: #444;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-gallery {
            padding: 50px 20px;
            background: #f3f3f3;
            text-align: center;
        }

        .product-gallery h2 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #222;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .gallery-grid img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .gallery-grid img:hover {
            transform: scale(1.05);
        }

        body.dark-mode .product-gallery {
            background: #1e1e2f;
        }

        body.dark-mode .product-gallery h2 {
            color: #f4f4f4;
        }

        .video-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Footer Styling */
        footer {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px 20px;
            text-align: center;
            font-size: 16px;
            color: #222;
        }

        footer h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        footer p {
            margin: 5px 0;
        }

        body.dark-mode footer {
            background: rgba(40, 40, 50, 0.85);
            color: #eee;
        }

        body.dark-mode footer h3 {
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="dark-toggle">
        <button onclick="toggleDarkMode()">🌙 Toggle Dark Mode</button>
    </div>

    <div class="nav-buttons">
        <a href="#about">About Us</a>
        <a href="#collection">Stationery Collection</a>
    </div>

    <div class="container">
        <div class="logo-title">
            <img src="logo.png" alt="Maskan Logo">
            <h1>MASKAN STATIONERY SHOP</h1>
        </div>
        <p>Your one-stop shop for all school & office needs. Track, manage, and shop with ease!</p>
        <a href="login.php" class="btn-start">Get Started</a>
    </div>

    <section id="about" class="about-us">
        <div class="about-box">
            <h2>About Us</h2>
            <p>
                10 years of experience is a boon for our organization as we have secured a remarkable position in the
                publishing industry. We are serving our global clients by offering them a range of children
                entertainment books, children educational books, stationary products, story books, calendar, panchang,
                diary, activity books, fiction books, writing books, children motivational books, coloring books,
                meaning books, motivational books, entertainment books, educational books and drawing books.
            </p>
        </div>
    </section>

    <section id="collection" class="product-gallery">
        <h2>Our Stationery Collection</h2>
        <div class="gallery-grid">
            <img src="uploads/pencil.jpeg" alt="Stationery 1">
            <img src="uploads/pen.jpeg" alt="Stationery 2">
            <img src="uploads/notebook.jpg" alt="Stationery 3">
            <img src="uploads/marker.jpeg" alt="Stationery 4">
            <img src="uploads/compass.jpeg" alt="Stationery 5">
            <img src="uploads/geometry.jpg" alt="Stationery 6">
            <img src="uploads/eraser.jpg" alt="Stationery 7">
            <img src="uploads/highlighter.jpeg" alt="Stationery 8">
            <img src="uploads/index-cards.jpg" alt="Stationery 9">
            <img src="uploads/sticky-notes.png" alt="Stationery 10">
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <h3>Our Services</h3>
        <p>Wholesale & Retail Stationery</p>
        <p>Customized Printing & Publishing</p>
        <p>Educational & Creative Products</p>
        <br>
        <h3>Contact Us</h3>
        <p>Name: Fathima Mahvish</p>
        <p>Phone: 8722877020</p>
        <p>Email: fathimamahvish38@gmail.com</p>
        <br>
        <p>&copy; 2025 MASKAN STATIONERY SHOP. All rights reserved.</p>
    </footer>

    <div class="video-wrapper">
        <video autoplay muted loop id="bg-video">
            <source src="background.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
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
