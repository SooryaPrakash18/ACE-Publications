
<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $age = $_POST['age'];
    $email = $_POST['email'];
    $contactno = $_POST['contactno'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, age, email, contactno, address, role)
            VALUES ('$username', '$password', $age, '$email', '$contactno', '$address', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
