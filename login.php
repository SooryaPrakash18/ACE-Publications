<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password); // comparing plain text

    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $username = $user['username'];
        $role = strtolower($user['role']); // lowercase to avoid mismatch

        if ($role === "admin") {
            echo "<script>
                    alert('Login successful! Welcome Admin $username');
                    window.location.href = 'admin.php';
                  </script>";
        } else if ($role === "customer") {
            echo "<script>
                    alert('Login successful! Welcome $username');
                    window.location.href = 'row.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Unknown role. Please contact support.');
                    window.location.href = 'login.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid email or password.');
                window.location.href = 'login.html';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
