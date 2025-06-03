<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please login to place an order.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $customer_name = $_POST['customer_name'];
    $book_ids = $_POST['book_ids'];
    $quantities = $_POST['quantities'];

    $order_sql = "INSERT INTO orders (customer_name, user_id) VALUES (?, ?)";
    $stmt_order = $conn->prepare($order_sql);
    $stmt_order->bind_param("si", $customer_name, $user_id);
    $stmt_order->execute();
    $order_id = $stmt_order->insert_id;

    $item_sql = "INSERT INTO order_items (order_id, book_id, quantity) VALUES (?, ?, ?)";
    $stmt_item = $conn->prepare($item_sql);

    for ($i = 0; $i < count($book_ids); $i++) {
        $stmt_item->bind_param("iii", $order_id, $book_ids[$i], $quantities[$i]);
        $stmt_item->execute();
    }

    echo "<script>alert('Order placed!'); window.location='index.html';</script>";
}
?>
