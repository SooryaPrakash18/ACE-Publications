<?php
session_start();
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    echo "<script>alert('Please enter both username and password'); window.location.href='login.html';</script>";
    exit();
}

// Check credentials
$stmt = $conn->prepare("SELECT id, password, role FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $db_password, $role);
    $stmt->fetch();

    if ($password === $db_password) {  // In production, use password_hash()
        // Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Update last login
        $update = $conn->prepare("UPDATE admin SET last_login = NOW() WHERE id = ?");
        $update->bind_param("i", $user_id);
        $update->execute();
        $update->close();

        // Redirect
        if ($role === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: customer_dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Incorrect password'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('User not found'); window.location.href='login.html';</script>";
}

$stmt->close();
$conn->close();
?>
