<?php
include 'db.php'; // Your DB connection

$sql = "SELECT b.name, SUM(oi.quantity) as total_sold
        FROM order_items oi
        JOIN books b ON oi.book_id = b.id
        GROUP BY oi.book_id
        ORDER BY total_sold DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sales Report - Admin</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h2 class="text-center mb-4">Books Sold Report</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Book Name</th>
                    <th>Total Sold</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo (int)$row['total_sold']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No sales data available yet.</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php $conn->close(); ?>
