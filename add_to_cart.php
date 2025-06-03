<?php
session_start();
include 'db.php';

// 1. Find if user already has an open order (latest order)
$order_sql = "SELECT id FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($order_id);
$stmt->fetch();
$stmt->close();

// 2. If no order found, create a new one
if (!$order_id) {
    $insert_order_sql = "INSERT INTO orders (user_id) VALUES (?)";
    $stmt = $conn->prepare($insert_order_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();
}

// 3. Check if this book already exists in order_items for this order
$check_item_sql = "SELECT quantity FROM order_items WHERE order_id = ? AND book_id = ?";
$stmt = $conn->prepare($check_item_sql);
$stmt->bind_param("ii", $order_id, $book_id);
$stmt->execute();
$stmt->bind_result($existing_qty);
$stmt->fetch();
$stmt->close();

if ($existing_qty) {
    // 4a. If exists, update quantity +1
    $new_qty = $existing_qty + 1;
    $update_sql = "UPDATE order_items SET quantity = ? WHERE order_id = ? AND book_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("iii", $new_qty, $order_id, $book_id);
    $stmt->execute();
    $stmt->close();
} else {
    // 4b. If not exists, insert new row
    $insert_item_sql = "INSERT INTO order_items (order_id, book_id, quantity) VALUES (?, ?, 1)";
    $stmt = $conn->prepare($insert_item_sql);
    $stmt->bind_param("ii", $order_id, $book_id);
    $stmt->execute();
    $stmt->close();
}

echo "<script>alert('Book added to cart.'); window.location.href='admin_order.php';</script>";
?>
