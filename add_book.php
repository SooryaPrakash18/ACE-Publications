<?php
include 'db.php'; // Your DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // Image upload handling
    $upload_dir = 'image/'; // Directory where images will be stored
    $image_url = '';
    
    // Create upload directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Check if image was uploaded
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_name = $_FILES['book_image']['name'];
        $file_size = $_FILES['book_image']['size'];
        $file_tmp = $_FILES['book_image']['tmp_name'];
        $file_type = $_FILES['book_image']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            echo "<script>
                    alert('Error: Invalid file type. Please upload JPG, PNG, GIF, or WEBP images only.');
                    window.location.href = 'AddBook.html';
                  </script>";
            exit();
        }
        
        // Validate file size
        if ($file_size > $max_size) {
            echo "<script>
                    alert('Error: File size is too large. Please upload an image smaller than 5MB.');
                    window.location.href = 'AddBook.html';
                  </script>";
            exit();
        }
        
        // Check if image with same name already exists in upload directory
        $check_existing_file = $upload_dir . $file_name;
        if (file_exists($check_existing_file)) {
            // Generate unique name if file already exists
            $counter = 1;
            $name_without_ext = pathinfo($file_name, PATHINFO_FILENAME);
            do {
                $unique_name = $name_without_ext . '_' . $counter . '.' . $file_ext;
                $target_file = $upload_dir . $unique_name;
                $counter++;
            } while (file_exists($target_file));
        } else {
            $unique_name = $file_name;
            $target_file = $upload_dir . $unique_name;
        }
        
        // Move uploaded file to destination
        if (move_uploaded_file($file_tmp, $target_file)) {
            $image_url = $target_file;
        } else {
            echo "<script>
                    alert('Error: Failed to upload image. Please try again.');
                    window.location.href = 'AddBook.html';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Error: Please select an image file.');
                window.location.href = 'AddBook.html';
              </script>";
        exit();
    }
    
    // Check if book name already exists
    $check_name_sql = "SELECT id FROM books WHERE name = ?";
    $check_name_stmt = $conn->prepare($check_name_sql);
    $check_name_stmt->bind_param("s", $name);
    $check_name_stmt->execute();
    $name_result = $check_name_stmt->get_result();
    
    if ($name_result->num_rows > 0) {
        $check_name_stmt->close();
        // Delete the uploaded image if name already exists
        if (file_exists($image_url)) {
            unlink($image_url);
        }
        echo "<script>
                alert('Error: A book with the name \"$name\" already exists. Please use a different name.');
                window.location.href = 'AddBook.html';
              </script>";
        exit();
    }
    $check_name_stmt->close();
    
    // Insert book into database
    $sql = "INSERT INTO books (image_url, name, price, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $image_url, $name, $price, $quantity);
    
    if ($stmt->execute()) {
        // First, let's check what columns exist in book_log table
        // Option 1: Try common column names for book_log
        
        // Try with 'book_name' column
        $log_sql = "INSERT INTO book_log(book_name, action, quantity) VALUES (?, 'added', ?)";
        $log_stmt = $conn->prepare($log_sql);
        
        if (!$log_stmt) {
            // If 'book_name' doesn't work, try 'title'
            $log_sql = "INSERT INTO book_log(title, action, quantity) VALUES (?, 'added', ?)";
            $log_stmt = $conn->prepare($log_sql);
            
            if (!$log_stmt) {
                // If 'title' doesn't work, try 'book_title'
                $log_sql = "INSERT INTO book_log(book_title, action, quantity) VALUES (?, 'added', ?)";
                $log_stmt = $conn->prepare($log_sql);
                
                if (!$log_stmt) {
                    // If none work, show error with database structure info
                    echo "<script>
                            alert('Book added successfully, but could not log the action. Please check your book_log table structure.');
                            window.location.href = 'row.php';
                          </script>";
                    $stmt->close();
                    $conn->close();
                    exit();
                }
            }
        }
        
        // Execute the log insertion
        $log_stmt->bind_param("si", $name, $quantity);
        $log_stmt->execute();
        $log_stmt->close();
        
        echo "<script>
                alert('Book added successfully with quantity: $quantity');
                window.location.href = 'row.php';
              </script>";
    } else {
        // Delete the uploaded image if database insertion fails
        if (file_exists($image_url)) {
            unlink($image_url);
        }
        echo "<script>
                alert('Error adding book. Please try again.');
                window.location.href = 'AddBook.html';
              </script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: AddBook.html");
    exit();
}
?>