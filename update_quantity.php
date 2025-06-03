<?php
include 'db.php'; // Your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $new_quantity = $_POST['new_quantity'];
    
    // Validate input
    if (!is_numeric($book_id) || !is_numeric($new_quantity) || $new_quantity < 0) {
        echo "<script>
                alert('Invalid input. Please enter valid numbers.');
                window.location.href = 'index.php';
              </script>";
        exit();
    }
    
    // Update the quantity directly
    $update_sql = "UPDATE books SET quantity = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $new_quantity, $book_id);
    
    if ($update_stmt->execute()) {
        if ($update_stmt->affected_rows > 0) {
            echo "<script>
                    alert('Quantity updated successfully! New quantity: $new_quantity');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Book not found or no changes made.');
                    window.location.href = 'index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Error updating quantity: " . $conn->error . "');
                window.location.href = 'index.php';
              </script>";
    }
    
    $update_stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>