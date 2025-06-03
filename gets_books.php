<?php
include 'db.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT id, image_url, name, price, quantity FROM books ORDER BY id DESC";
    $result = $conn->query($sql);
    
    $books = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Convert full path to web path for display
            $web_image_path = str_replace('D:\\xampp\\htdocs\\usersdb\\', '', $row['image_url']);
            $web_image_path = str_replace('\\', '/', $web_image_path);
            
            $books[] = array(
                'id' => $row['id'],
                'image_url' => $web_image_path,
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $row['quantity']
            );
        }
    }
    
    echo json_encode($books);
    
} catch(Exception $e) {
    echo json_encode(array('error' => 'Failed to fetch books: ' . $e->getMessage()));
}

$conn->close();
?>