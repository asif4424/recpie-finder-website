<?php
session_start();
if (!isset($_SESSION['loggedin'])) header("Location: login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Recipe Finder</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Base styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.9)), 
                              url('assets/images/chef-background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed; /* Enables parallax effect */
            color: white;
        }

        /* Content container */
        .content {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        /* Welcome text styling */
        .content h1 {
            font-size: 2.5em;
            font-weight: 600;
            margin-bottom: 20px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.7); /* Adds depth to text */
        }

        /* Navigation container */
        .nav-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        /* Navigation links styled as cards */
        .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 150px;
            margin: 10px;
            background: rgba(255, 255, 255, 0.2); /* Transparent card background */
            color: white;
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 1.2em;
            font-weight: 500;
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        /* Hover effect for navigation cards */
        .nav-link:hover {
            transform: scale(1.1);
            background-color: rgba(92, 184, 92, 0.8); /* Green background on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Optional decorative line */
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            width: 0%;
            height: 3px;
            background-color: white;
            transition: width 0.3s ease, left 0.3s ease;
        }
        .nav-link:hover::before {
            width: 100%;
            left: 0;
        }

        /* Icon styling for the cards (Optional) */
        .nav-link i {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['email']) ?>!</h1>
        <div class="nav-container">
            <a href="search.php" class="nav-link">
                <i class="fas fa-search"></i> <!-- Optional: Add icons -->
                Search Recipes
            </a>
            <a href="about.php" class="nav-link">
                <i class="fas fa-info-circle"></i> <!-- Optional: Add icons -->
                About Us
            </a>
            <a href="contact.php" class="nav-link">
                <i class="fas fa-envelope"></i> <!-- Optional: Add icons -->
                Contact Us
            </a>
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> <!-- Optional: Add icons -->
                Logout
            </a>
        </div>
    </div>
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
