<?php
include 'db.php';  // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match!');
                window.location.href = 'forget.html';
              </script>";
        exit();
    }

    // Check if user with this email exists
    $check_sql = "SELECT * FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        echo "<script>
                alert('Email not found in our records!');
                window.location.href = 'forget.html';
              </script>";
        $stmt_check->close();
        $conn->close();
        exit();
    }
    $stmt_check->close();

    // Update password query WITHOUT hashing (plain text)
    $update_sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("ss", $new_password, $email);

    if ($stmt_update->execute()) {
        echo "<script>
                alert('Password updated successfully!');
                window.location.href = 'login.html';
              </script>";
    } else {
        echo "<script>
                alert('Error updating password. Please try again.');
                window.location.href = 'forget.html';
              </script>";
    }

    $stmt_update->close();
    $conn->close();
}
?>
