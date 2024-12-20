<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $language = $_POST['language'];
    $gender = $_POST['gender'];
    $experience = $_POST['experience'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query to insert user data
    $stmt = $conn->prepare("INSERT INTO users (email, password, language, gender, experience) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("sssss", $email, $hashedPassword, $language, $gender, $experience);
    $stmt->execute();

    // Redirect to login page after successful registration
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Base Styling */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-image: url('assets/images/background.jpg'); /* Keep the same chef background */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }

        /* Form Container */
        form {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1); /* Glassmorphism Effect */
            backdrop-filter: blur(15px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            animation: float 4s ease-in-out infinite;
            transition: all 0.3s ease;
            transform: translateY(0);
            text-align: center;
        }

        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6);
        }

        form h2 {
            font-size: 2em;
            font-weight: 600;
            margin-bottom: 20px;
            color: white;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
        }

        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: white;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
        }

        form input, form select, form button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1em;
            outline: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        form input:focus, form select:focus {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.8);
            transform: scale(1.05);
        }

        form button {
            background: linear-gradient(90deg, #5cb85c, #4cae4c);
            color: white;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 255, 0, 0.3);
        }

        form button:hover {
            background: linear-gradient(90deg, #4cae4c, #5cb85c);
            transform: scale(1.1);
        }

        /* Radio Group */
        .radio-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1em;
            cursor: pointer;
        }

        .radio-group input[type="radio"] {
            width: auto;
            cursor: pointer;
        }

        /* Keyframe Animations */
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
    <form method="POST" action="register.php">
        <h2>Register</h2>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter a strong password" required>

        <label for="language">Preferred Language:</label>
        <select id="language" name="language" required>
            <option value="" disabled selected>Select a language</option>
            <option value="English">English</option>
            <option value="Telugu">Telugu</option>
            <option value="Hindi">Hindi</option>
            <option value="Tamil">Tamil</option>
            <option value="Kannada">Kannada</option>
            <option value="Spanish">Spanish</option>
            <option value="French">French</option>
            <option value="German">German</option>
            <option value="Chinese">Chinese</option>
        </select>

        <label for="gender">Gender:</label>
        <div class="radio-group">
            <label>
                <input type="radio" id="male" name="gender" value="Male" required> Male
            </label>
            <label>
                <input type="radio" id="female" name="gender" value="Female" required> Female
            </label>
        </div>

        <label for="experience">Cooking Experience:</label>
        <div class="radio-group">
            <label>
                <input type="radio" id="professional" name="experience" value="Professional" required> Professional
            </label>
            <label>
                <input type="radio" id="beginner" name="experience" value="Beginner" required> Beginner
            </label>
        </div>

        <button type="submit">Register</button>
    </form>
</body>
</html>
