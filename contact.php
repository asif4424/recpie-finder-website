<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Recipe Finder</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="contact.php">Contact Us</a>
        <a href="about.php">About Us</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Contact Us</h1>
        <p>
            Have a question or need assistance? Fill out the form below, and we’ll get back to you
            as soon as possible.
        </p>
        <form method="POST" action="contact_handler.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Your Name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Your Email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" placeholder="Your Message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>
