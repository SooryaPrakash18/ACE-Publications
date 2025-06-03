<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['mailid']);
    $country = trim($_POST['country']);
    $feedback_text = trim($_POST['subject']);
    
    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($country) || empty($feedback_text)) {
        header("Location: feedback.html?status=error&message=All fields are required");
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: feedback.html?status=error&message=Invalid email format");
        exit();
    }
    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO feedback (first_name, last_name, email, country, feedback_text) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $country, $feedback_text);
        
        if ($stmt->execute()) {
            // Success - redirect with success message
            header("Location: feedback.html?status=success&message=Thank you for your feedback!");
        } else {
            // Database error
            header("Location: feedback.html?status=error&message=Error saving feedback. Please try again.");
        }
        
        $stmt->close();
    } else {
        // Prepare statement error
        header("Location: feedback.html?status=error&message=Database error. Please try again.");
    }
    
    $conn->close();
} else {
    // If not POST request, redirect back to feedback form
    header("Location: feedback.html");
}
?>