<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Fetch and validate inputs
$searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : ''; // Get search query

// Fetch recipes from API
$apiUrl = "https://www.themealdb.com/api/json/v1/1/search.php?s=" . urlencode($searchQuery);
$response = @file_get_contents($apiUrl);

if ($response === false) {
    die("Unable to fetch recipes. Please try again later.");
}

$data = json_decode($response, true);

// Validate and process results
$results = [];
if (isset($data['meals']) && is_array($data['meals'])) {
    $results = $data['meals'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Recipes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Base Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('assets/images/chef-background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }

        /* Content container */
        .content {
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            animation: fadeIn 1s ease-in-out;
        }

        /* Search bar styling */
        form {
            text-align: center;
            margin-bottom: 30px;
        }

        form input[type="text"] {
            width: 400px;
            padding: 15px;
            font-size: 1.1em;
            border: none;
            border-radius: 30px;
            outline: none;
            background: rgba(255, 255, 255, 0.3);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        form input[type="text"]:focus {
            transform: scale(1.05);
            box-shadow: 0 5px 25px rgba(0, 255, 125, 0.6);
        }

        form button {
            padding: 15px 25px;
            margin-left: 10px;
            border: none;
            border-radius: 30px;
            background-color: #5cb85c;
            color: white;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        form button:hover {
            background-color: #4cae4c;
            transform: scale(1.05);
        }

        /* Results container */
        #mealResults {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        /* Glassmorphism Card */
        .meal-card {
            width: 300px;
            border-radius: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            text-align: center;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .meal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        .meal-card img {
            width: 100%;
            border-radius: 15px;
            margin-bottom: 15px;
        }

        .meal-card h3 {
            font-size: 1.4em;
            margin-bottom: 10px;
        }

        .meal-card .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #5cb85c;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .meal-card .btn:hover {
            background-color: #4cae4c;
        }

        /* No results message */
        .no-results {
            text-align: center;
            color: red;
            font-size: 1.2em;
            margin-top: 20px;
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
        <form method="GET" action="search.php">
            <input type="text" name="search" placeholder="Type to find your recipe..." value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($results)): ?>
            <div id="mealResults">
                <?php foreach ($results as $meal): ?>
                    <div class="meal-card">
                        <img src="<?= htmlspecialchars($meal['strMealThumb']) ?>" alt="<?= htmlspecialchars($meal['strMeal']) ?>">
                        <h3><?= htmlspecialchars($meal['strMeal']) ?></h3>
                        <a href="recipe.php?id=<?= htmlspecialchars($meal['idMeal']) ?>" class="btn">View Recipe</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-results">No recipes found. Try searching something else!</p>
        <?php endif; ?>
    </div>
</body>
</html>
