<?php
include 'db.php'; // Your database connection

// Get total books added
$added_sql = "SELECT COUNT(*) as total_added FROM book_log WHERE action = 'added'";
$added_result = $conn->query($added_sql);
$total_added = $added_result->fetch_assoc()['total_added'];

// Get total books deleted
$deleted_sql = "SELECT COUNT(*) as total_deleted FROM book_log WHERE action = 'deleted'";
$deleted_result = $conn->query($deleted_sql);
$total_deleted = $deleted_result->fetch_assoc()['total_deleted'];

// Get current books count
$current_sql = "SELECT COUNT(*) as current_books FROM books";
$current_result = $conn->query($current_sql);
$current_books = $current_result->fetch_assoc()['current_books'];

// Calculate books sold (assuming orders table exists and tracks book sales)
// If you don't have an orders table yet, we'll show 0 for now
$sold_sql = "SELECT COUNT(*) as total_sold FROM orders";
$sold_result = $conn->query($sold_sql);
if ($sold_result) {
    $total_sold = $sold_result->fetch_assoc()['total_sold'];
} else {
    $total_sold = 0; // If orders table doesn't exist yet
}

// Get recent book activities
$recent_sql = "SELECT book_name, action, timestamp FROM book_log ORDER BY timestamp DESC LIMIT 10";
$recent_result = $conn->query($recent_sql);

// Get books added per month (last 6 months)
$monthly_sql = "SELECT 
    DATE_FORMAT(timestamp, '%Y-%m') as month,
    COUNT(*) as count,
    action
    FROM book_log 
    WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY month, action
    ORDER BY month DESC";
$monthly_result = $conn->query($monthly_sql);

$monthly_data = [];
while ($row = $monthly_result->fetch_assoc()) {
    $monthly_data[$row['month']][$row['action']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>ACE Publications - Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        .dashboard-card {
            transition: transform 0.2s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="container-fluid my-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-bar"></i> ACE Publications Dashboard</h2>
            <div>
                <a href="index.php" class="btn btn-primary mr-2">
                    <i class="fas fa-book"></i> View Books
                </a>
                <a href="AddBook.html" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Book
                </a>
            </div>
        </div>

        <!-- Statistics Cards Row -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card dashboard-card bg-primary text-white h-100">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?php echo $current_books; ?></h3>
                                <p class="mb-0">Current Books</p>
                            </div>
                            <i class="fas fa-book stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card dashboard-card bg-success text-white h-100">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?php echo $total_added; ?></h3>
                                <p class="mb-0">Books Added</p>
                            </div>
                            <i class="fas fa-plus-circle stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card dashboard-card bg-info text-white h-100">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?php echo $total_sold; ?></h3>
                                <p class="mb-0">Books Sold</p>
                            </div>
                            <i class="fas fa-shopping-cart stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card dashboard-card bg-danger text-white h-100">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?php echo $total_deleted; ?></h3>
                                <p class="mb-0">Books Deleted</p>
                            </div>
                            <i class="fas fa-trash stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Activities -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Book Name</th>
                                            <th>Action</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($activity = $recent_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($activity['book_name']); ?></td>
                                                <td>
                                                    <?php if ($activity['action'] == 'added'): ?>
                                                        <span class="badge badge-success">Added</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger">Deleted</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M j, Y', strtotime($activity['timestamp'])); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center">No recent activities found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Monthly Statistics -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-calendar"></i> Monthly Statistics</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($monthly_data)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Added</th>
                                            <th>Deleted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($monthly_data as $month => $data): ?>
                                            <tr>
                                                <td><?php echo date('F Y', strtotime($month . '-01')); ?></td>
                                                <td>
                                                    <span class="badge badge-success">
                                                        <?php echo isset($data['added']) ? $data['added'] : 0; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-danger">
                                                        <?php echo isset($data['deleted']) ? $data['deleted'] : 0; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center">No monthly data available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h6>Net Books Added</h6>
                                <h4 class="text-primary"><?php echo ($total_added - $total_deleted); ?></h4>
                                <small class="text-muted">Total Added - Total Deleted</small>
                            </div>
                            <div class="col-md-4">
                                <h6>Success Rate</h6>
                                <h4 class="text-success">
                                    <?php 
                                    if ($total_added > 0) {
                                        echo round((($total_added - $total_deleted) / $total_added) * 100, 1) . '%';
                                    } else {
                                        echo '0%';
                                    }
                                    ?>
                                </h4>
                                <small class="text-muted">Books retained vs added</small>
                            </div>
                            <div class="col-md-4">
                                <h6>Revenue Potential</h6>
                                <h4 class="text-info">₹<?php echo number_format($total_sold * 500, 2); ?></h4>
                                <small class="text-muted">Estimated (₹500 avg per book)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php $conn->close(); ?>