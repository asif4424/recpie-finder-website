<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Database connection
include 'db.php';

// Initialize variables
$reviewSubmitted = false;
$errorMessage = "";

// Get recipe ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid recipe ID.");
}

$recipeId = htmlspecialchars($_GET['id']);
$userEmail = $_SESSION['email'];

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'])) {
    if (!empty(trim($_POST['review']))) {
        $reviewText = htmlspecialchars($_POST['review']);

        // Insert review into database
        $stmt = $conn->prepare("INSERT INTO reviews (recipe_id, user_email, review_text) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $recipeId, $userEmail, $reviewText);

        if ($stmt->execute()) {
            $reviewSubmitted = true; // Set review submitted flag
        } else {
            $errorMessage = "Error saving your review: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errorMessage = "Review cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Recipe</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #3f51b5, #5c6bc0);
            background-size: cover;
            background-position: center;
            color: #ffffff;
        }
        .content {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.8); /* Semi-transparent background */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        form {
            display: <?= isset($reviewSubmitted) && $reviewSubmitted ? "none" : "block" ?>;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        button:hover {
            background-color: #388e3c;
            transform: scale(1.05);
        }
        .error-message {
            color: #f44336;
            margin-bottom: 10px;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .actions button {
            padding: 15px 30px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 12px rgba(0, 0, 255, 0.3);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .actions button:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Review Recipe</h1>

        <!-- Display error message if any -->
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <!-- Form to submit a review -->
        <form method="POST" action="">
            <textarea name="review" placeholder="Write your review here..." required></textarea>
            <button type="submit">Submit Review</button>
        </form>

        <!-- Thank-you message and buttons -->
        <?php if ($reviewSubmitted): ?>
            <h2>Thank you for your review!</h2>
            <div class="actions">
                <button onclick="window.location.href='home.php';">Back to Home</button>
                <button onclick="window.location.href='review.php?id=<?= htmlspecialchars($recipeId) ?>';">Resubmit Review</button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
