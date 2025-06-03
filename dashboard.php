<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied'); window.location.href='login.html';</script>";
    exit();
}

include 'db.php';

// Count by role
$sql = "SELECT role, COUNT(*) AS count FROM admin GROUP BY role";
$result = $conn->query($sql);

$counts = [
    'admin' => 0,
    'customer' => 0
];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $counts[$row['role']] = $row['count'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { color: #333; }
    </style>
</head>
<body>

<h1>Admin Dashboard</h1>

<div class="box">
    <h2>Total Admins: <?php echo $counts['admin']; ?></h2>
</div>
<div class="box">
    <h2>Total Customers: <?php echo $counts['customer']; ?></h2>
</div>

</body>
</html>
