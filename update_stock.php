<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['book_id']) || !isset($input['quantity'])) {
        echo json_encode(array('success' => false, 'message' => 'Missing required parameters'));
        exit();
    }
    
    $book_id = intval($input['book_id']);
    $purchase_quantity = intval($input['quantity']);
    
    try {
        // Start transaction
        $conn->autocommit(FALSE);
        
        // Get current stock
        $check_sql = "SELECT name, quantity FROM books WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $book_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows == 0) {
            throw new Exception("Book not found");
        }
        
        $book = $result->fetch_assoc();
        $current_stock = intval($book['quantity']);
        $book_name = $book['name'];
        
        if ($current_stock < $purchase_quantity) {
            throw new Exception("Insufficient stock. Only $current_stock books available.");
        }
        
        if ($current_stock == 0) {
            throw new Exception("This book is out of stock!");
        }
        
        // Update stock
        $new_stock = $current_stock - $purchase_quantity;
        $update_sql = "UPDATE books SET quantity = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_stock, $book_id);
        
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update stock");
        }
        
        // Log the purchase
        $log_sql = "INSERT INTO book_log (book_name, action, quantity) VALUES (?, 'purchased', ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("si", $book_name, $purchase_quantity);
        $log_stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        $response = array(
            'success' => true,
            'message' => "Purchase successful! $purchase_quantity book(s) added to cart.",
            'new_stock' => $new_stock,
            'book_name' => $book_name
        );
        
        if ($new_stock == 0) {
            $response['out_of_stock'] = true;
            $response['message'] .= " This book is now out of stock.";
        }
        
        echo json_encode($response);
        
    } catch(Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(array(
            'success' => false, 
            'message' => $e->getMessage()
        ));
    }
    
    $conn->autocommit(TRUE);
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
}

$conn->close();
?>