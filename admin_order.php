<?php
include 'db.php';

$sql = "
    SELECT 
        u.username,
        b.name AS book_name,
        SUM(oi.quantity) AS total_quantity
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN users u ON o.user_id = u.id
    JOIN books b ON oi.book_id = b.id
    GROUP BY u.username, b.name
    ORDER BY u.username
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Orders Summary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-center">Books Sold Summary</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Customer Name</th>
                    <th>Book Name</th>
                    <th>Quantity Ordered</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                        <td><?php echo (int)$row['total_quantity']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No orders found.</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php $conn->close(); ?>
