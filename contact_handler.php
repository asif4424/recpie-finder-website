<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Here, you can save the data to a database or send an email.
    // Example: Save to a database (requires db.php)
    include 'db.php';

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    if ($stmt->execute()) {
        echo "Thank you, $name! Your message has been sent.";
    } else {
        echo "There was an error. Please try again later.";
    }
    $stmt->close();
    $conn->close();
}
?>
