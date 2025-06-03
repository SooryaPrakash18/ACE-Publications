<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

$id = intval($_GET['id']);

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $message = "User deleted successfully.";
    $type = "success";
} else {
    $message = "Failed to delete user.";
    $type = "danger";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="2;url=admin.php">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="alert alert-<?= $type ?> shadow text-center" role="alert">
        <?= $message ?><br>
        Redirecting to admin panel...
    </div>
</div>

</body>
</html>
