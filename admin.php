<?php 
session_start(); 
include 'db.php';  

// Fetch all users 
$sql = "SELECT id, username, email, role FROM users"; 
$result = $conn->query($sql); 

// Fetch all feedback
$feedback_sql = "SELECT id, first_name, last_name, email, country, feedback_text, created_at FROM feedback ORDER BY created_at DESC";
$feedback_result = $conn->query($feedback_sql);
?>  

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <title>Admin - Manage Users & Feedback</title>     
    <!-- Bootstrap 5 CSS CDN -->     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
</head> 
<body class="bg-light">  

<div class="container mt-5">     
    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                User Management
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="feedback-tab" data-bs-toggle="tab" data-bs-target="#feedback" type="button" role="tab">
                Feedback Management
            </button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        <!-- Users Tab -->
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-4">         
                <h2 class="text-primary">User Management</h2>     
            </div>      
            
            <div class="table-responsive">         
                <table class="table table-striped table-bordered shadow">             
                    <thead class="table-dark">                 
                        <tr>                     
                            <th>ID</th>                     
                            <th>Username</th>                     
                            <th>Email</th>                     
                            <th>Role</th>                     
                            <th>Actions</th>                 
                        </tr>             
                    </thead>             
                    <tbody>             
                    <?php             
                    if ($result->num_rows > 0) {                 
                        while($user = $result->fetch_assoc()) {                     
                            echo "<tr>";                     
                            echo "<td>" . $user['id'] . "</td>";                     
                            echo "<td>" . htmlspecialchars($user['username']) . "</td>";                     
                            echo "<td>" . htmlspecialchars($user['email']) . "</td>";                     
                            echo "<td><span class='badge bg-" . ($user['role'] === 'admin' ? 'primary' : 'secondary') . "'>" . htmlspecialchars($user['role']) . "</span></td>";                     
                            echo "<td>                             
                                    <a href='edit_user.php?id=" . $user['id'] . "' class='btn btn-sm btn-warning'>Edit</a>                             
                                    <a href='delete_user.php?id=" . $user['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>                           
                                  </td>";                     
                            echo "</tr>";                 
                        }             
                    } else {                 
                        echo "<tr><td colspan='5' class='text-center'>No users found.</td></tr>";             
                    }             
                    ?>             
                    </tbody>         
                </table>     
            </div>
        </div>

        <!-- Feedback Tab -->
        <div class="tab-pane fade" id="feedback" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-4">         
                <h2 class="text-success">Feedback Management</h2>     
            </div>      
            
            <div class="table-responsive">         
                <table class="table table-striped table-bordered shadow">             
                    <thead class="table-success">                 
                        <tr>                     
                            <th>ID</th>                     
                            <th>Name</th>                     
                            <th>Email</th>                     
                            <th>Country</th>                     
                            <th>Feedback</th>
                            <th>Date</th>                     
                            <th>Actions</th>                 
                        </tr>             
                    </thead>             
                    <tbody>             
                    <?php             
                    if ($feedback_result->num_rows > 0) {                 
                        while($feedback = $feedback_result->fetch_assoc()) {                     
                            echo "<tr>";                     
                            echo "<td>" . $feedback['id'] . "</td>";                     
                            echo "<td>" . htmlspecialchars($feedback['first_name']) . " " . htmlspecialchars($feedback['last_name']) . "</td>";                     
                            echo "<td>" . htmlspecialchars($feedback['email']) . "</td>";                     
                            echo "<td>" . htmlspecialchars($feedback['country']) . "</td>";                     
                            echo "<td><button class='btn btn-sm btn-info' onclick='showFeedback(" . $feedback['id'] . ", \"" . htmlspecialchars(addslashes($feedback['feedback_text'])) . "\")'>View</button></td>";
                            echo "<td>" . date('Y-m-d H:i', strtotime($feedback['created_at'])) . "</td>";                     
                            echo "<td>                             
                                    <a href='delete_feedback.php?id=" . $feedback['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this feedback?\")'>Delete</a>                           
                                  </td>";                     
                            echo "</tr>";                 
                        }             
                    } else {                 
                        echo "<tr><td colspan='7' class='text-center'>No feedback found.</td></tr>";             
                    }             
                    ?>             
                    </tbody>         
                </table>     
            </div>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feedback Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="feedbackContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle --> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 

<script>
function showFeedback(id, feedbackText) {
    document.getElementById('feedbackContent').textContent = feedbackText;
    var modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
    modal.show();
}
</script>

</body> 
</html>  

<?php $conn->close(); ?>