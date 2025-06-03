<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; // For security, use password_hash() in production
    $age = $_POST['age'];
    $email = $_POST['email'];
    $contactno = $_POST['contactno'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, age, email, contactno, address, role)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissss", $username, $password, $age, $email, $contactno, $address, $role);

    if ($stmt->execute()) {
        echo "<script>
                alert('Registration successful!');
                window.location.href = 'login.html';
              </script>";
    } else {
        echo "<script>
                alert('Registration failed!');
                window.location.href = 'login.html';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
