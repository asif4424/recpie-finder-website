<?php
session_start();
include 'loadLanguage.php'; // Include the language loader

// If language is not set in session, set it to English by default
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'english'; // Default to English if no language is selected
}

// Load translations based on the session language
$lang = loadLanguage();

include 'db.php'; // Make sure to include the database connection

// Handle login request
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
            $_SESSION['language'] = $user['language']; // Store the user's selected language in session
            header("Location: home.php");
            exit();
        }
    }
    // If invalid credentials, show the error message in the selected language
    $error = $lang['invalid_credentials']; // Use the language-specific error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['login_title'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-image: url('assets/images/background.jpg'); /* Replace with the path to your background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent black for readability */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .login-card h2 {
            margin-bottom: 20px;
            color: #f5f5f5;
            font-weight: 500;
        }

        .login-card form {
            display: flex;
            flex-direction: column;
        }

        .login-card label {
            text-align: left;
            margin-bottom: 8px;
            font-weight: 500;
            color: #ddd;
        }

        .login-card input {
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            background: #333;
            color: white;
        }

        .login-card input::placeholder {
            color: #aaa;
        }

        .login-card button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            font-size: 1.1em;
            cursor: pointer;
            font-weight: 500;
        }

        .login-card button:hover {
            background-color: #0056b3;
        }

        .login-card .error {
            color: red;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        .register-link {
            margin-top: 20px;
            font-size: 0.9em;
            color: #007BFF;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <h2><?= $lang['login_title'] ?></h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="POST" action="session.php">
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
