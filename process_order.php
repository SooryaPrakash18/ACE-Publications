<?php
include 'db.php';

// Check if customer_name is present and valid
if (!isset($_POST['customer_name']) || empty(trim($_POST['customer_name']))) {
    echo "<script>alert('Please enter your name!'); window.location.href='cart.html';</script>";
    exit();
}
$customer_name = trim($_POST['customer_name']);

// Check if cart data is present
if (!isset($_POST['cart_data']) || empty($_POST['cart_data'])) {
    echo "<script>alert('Cart is empty!'); window.location.href='cart.html';</script>";
    exit();
}

$cart = json_decode($_POST['cart_data'], true);
if (!$cart || count($cart) === 0) {
    echo "<script>alert('Invalid cart data!'); window.location.href='cart.html';</script>";
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (customer_name) VALUES (?)");
    if (!$stmt) {
        throw new Exception("Order insert prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $customer_name);
    if (!$stmt->execute()) {
        throw new Exception("Order insert execute failed: " . $stmt->error);
    }
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Prepare order_items insertion
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Order items prepare failed: " . $conn->error);
    }

    foreach ($cart as $item) {
        if (!isset($item['id']) || !isset($item['quantity'])) {
            throw new Exception("Cart item missing book ID or quantity.");
        }

        $book_id = intval($item['id']);
        $quantity = intval($item['quantity']);
        if ($quantity < 1) $quantity = 1;

        $stmt->bind_param("iii", $order_id, $book_id, $quantity);
        if (!$stmt->execute()) {
            throw new Exception("Order item insert failed: " . $stmt->error);
        }
    }
    $stmt->close();

    // Commit all changes
    $conn->commit();

    // Send success message and clear cart
    echo "<script>
            alert('Order placed successfully!');
            localStorage.removeItem('cart');
            window.location.href='index.html';
          </script>";
} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Failed to place order: " . addslashes($e->getMessage()) . "'); window.location.href='cart.html';</script>";
}

$conn->close();
?>
