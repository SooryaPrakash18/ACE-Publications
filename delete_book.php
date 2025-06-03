<?php
include 'db.php'; // Your database connection

if (isset($_GET['name'])) {
    $bookName = $_GET['name'];

    // First, get the image_url before deletion
    $select_sql = "SELECT image_url FROM books WHERE name = ?";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param("s", $bookName);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        $image_url = $book['image_url'];
        
        // Prepare deletion statement
        $sql = "DELETE FROM books WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $bookName);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Delete the image file if it exists
                if (!empty($image_url) && file_exists($image_url)) {
                    unlink($image_url);
                }

                echo "<script>
                        alert('Book \"$bookName\" deleted successfully!');
                        window.location.href='index.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Book \"$bookName\" not found!');
                        window.location.href='index.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error deleting book!');
                    window.location.href='index.php';
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Book \"$bookName\" not found!');
                window.location.href='index.php';
              </script>";
    }
    
    $select_stmt->close();
} else {
    echo "<script>
            alert('No book name provided!');
            window.location.href='index.php';
          </script>";
}

$conn->close();
?>