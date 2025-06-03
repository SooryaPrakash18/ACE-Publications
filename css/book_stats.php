<?php
include 'db.php'; // Your DB connection

// Initialize counts to 0 in case queries fail
$added = 0;
$deleted = 0;

// Count added books
$result = $conn->query("SELECT COUNT(*) AS total FROM book_log WHERE action = 'added'");
if ($result) {
    $added = $result->fetch_assoc()['total'];
}

// Count deleted books
$result = $conn->query("SELECT COUNT(*) AS total FROM book_log WHERE action = 'deleted'");
if ($result) {
    $deleted = $result->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book Statistics</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Book Action Statistics</h2>
        <div class="card p-4">
            <p><strong>Total Books Added:</strong> <?php echo $added; ?></p>
            <p><strong>Total Books Deleted:</strong> <?php echo $deleted; ?></p>
            <a href="index.php" class="btn btn-primary mt-3">Back to Book List</a>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
