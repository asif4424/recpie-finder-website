<?php
session_start();
include __DIR__ . '/loadLanguage.php'; // Corrected file inclusion

// Load translations
$lang = loadLanguage();

// Check if $lang is not an array or empty, and provide fallback translations
if (!is_array($lang) || empty($lang)) {
    // Default fallback text to prevent further errors
    $lang = [
        'login_title' => 'Login',
        'email_label' => 'Email Address',
        'password_label' => 'Password',
        'login_button' => 'Login',
        'register_prompt' => "Don't have an account? Register here",
        'invalid_credentials' => 'Invalid email or password',
    ];
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            header("Location: home.php");
            exit();
        }
    }
    $error = $lang['invalid_credentials']; // Display error message in selected language
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['login_title'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Base styling */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-image: url('assets/images/background.jpg'); /* Keep the chef background */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }

        /* Container styling */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Login card styling */
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: rgba(0, 0, 0, 0.8); /* Frosted glass effect */
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .login-card h2 {
            margin-bottom: 25px;
            font-size: 2em;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        .login-card form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .login-card label {
            text-align: left;
            font-weight: 500;
            color: #ccc;
            font-size: 0.9em;
        }

        .login-card input {
            padding: 15px;
            font-size: 1em;
            border: none;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            outline: none;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .login-card input:focus {
            box-shadow: 0 0 10px #5cb85c;
            transform: scale(1.05);
        }

        .login-card button {
            padding: 15px;
            border: none;
            border-radius: 30px;
            background-color: #5cb85c;
            color: white;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .login-card button:hover {
            background-color: #4cae4c;
            transform: scale(1.05);
        }

        .login-card .error {
            color: red;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        .register-link {
            display: inline-block;
            margin-top: 15px;
            font-size: 0.9em;
            color: #5cb85c;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .register-link:hover {
            color: #4cae4c;
        }

        /* Animation for fade-in effect */
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
    <div class="container">
        <div class="login-card">
            <h2><?= $lang['login_title'] ?></h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="POST" action="login.php">
                <label for="email"><?= $lang['email_label'] ?></label>
                <input type="email" id="email" name="email" placeholder="<?= $lang['email_label'] ?>" required>

                <label for="password"><?= $lang['password_label'] ?></label>
                <input type="password" id="password" name="password" placeholder="<?= $lang['password_label'] ?>" required>

                <button type="submit"><?= $lang['login_button'] ?></button>
            </form>
            <a href="register.php" class="register-link"><?= $lang['register_prompt'] ?></a>
        </div>
    </div>
</body>
</html>
