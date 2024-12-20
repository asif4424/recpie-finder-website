<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Validate and get the recipe ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid recipe ID.");
}

$mealId = htmlspecialchars($_GET['id']);

// Fetch recipe details from the MealDB API using the meal ID
$apiUrl = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . urlencode($mealId);
$response = @file_get_contents($apiUrl);

if ($response === false) {
    die("Unable to fetch recipe details. Please try again later.");
}

$data = json_decode($response, true);
if (!isset($data['meals'][0])) {
    die("Recipe not found.");
}

$recipe = $data['meals'][0];

// Extract ingredients and measures
$ingredients = [];
for ($i = 1; $i <= 20; $i++) {
    $ingredient = $recipe["strIngredient$i"];
    $measure = $recipe["strMeasure$i"];
    if (!empty($ingredient)) {
        $ingredients[] = $measure . " " . $ingredient;
    }
}

// Break down instructions into steps
$instructions = array_filter(array_map('trim', explode('.', $recipe['strInstructions'])));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recipe['strMeal']) ?> - Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Base Styling */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-image: url('assets/images/chef-background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }

        /* Content Container */
        .content {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        /* Recipe Image */
        .recipe-image {
            width: 300px;
            height: auto;
            margin: 20px auto;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }

        .recipe-image:hover {
            transform: scale(1.05);
        }

        /* Ingredients Section */
        .ingredients {
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .ingredients ul {
            list-style-type: none;
            padding: 0;
            text-align: left;
        }

        .ingredients ul li {
            margin: 5px 0;
            font-size: 1.1em;
        }

        /* Instructions Section */
        .steps {
            margin-top: 20px;
        }

        .step {
            display: none;
            margin-bottom: 15px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease-in-out;
        }

        .step.active {
            display: block;
        }

        /* Button Group */
        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .button-group button {
            padding: 15px 30px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
            box-shadow: 0 6px 12px rgba(0, 255, 0, 0.3);
        }

        .button-group button:hover {
            background-color: #4cae4c;
            transform: scale(1.1);
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 30px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 1em;
            font-weight: 600;
            box-shadow: 0 6px 12px rgba(0, 0, 255, 0.3);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        /* Fade-In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
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
        <h1><?= htmlspecialchars($recipe['strMeal']) ?></h1>
        <img src="<?= htmlspecialchars($recipe['strMealThumb']) ?>" alt="<?= htmlspecialchars($recipe['strMeal']) ?>" class="recipe-image">

        <div class="ingredients">
            <h2>Ingredients</h2>
            <ul>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li><?= htmlspecialchars($ingredient) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <h2>Instructions</h2>
        <div class="steps">
            <?php foreach ($instructions as $index => $step): ?>
                <div class="step <?= $index === 0 ? 'active' : '' ?>" id="step-<?= $index ?>">
                    <strong>Step <?= $index + 1 ?>:</strong>
                    <p><?= htmlspecialchars($step) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="button-group">
            <button id="prevStep" onclick="changeStep(-1)" disabled>Previous</button>
            <button id="nextStep" onclick="changeStep(1)">Next</button>
        </div>

        <a href="review.php?id=<?= htmlspecialchars($mealId) ?>" class="btn">Review This Recipe</a>
    </div>

    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll('.step');
        const prevButton = document.getElementById('prevStep');
        const nextButton = document.getElementById('nextStep');

        function changeStep(direction) {
            steps[currentStep].classList.remove('active');
            currentStep += direction;

            // Enable or disable buttons based on the step
            prevButton.disabled = currentStep === 0;
            nextButton.disabled = currentStep === steps.length - 1;

            steps[currentStep].classList.add('active');
        }
    </script>
</body>
</html>