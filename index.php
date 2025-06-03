<?php
include 'db.php'; // Your database connection

$sql = "SELECT * FROM books ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>ACE Publications</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <style>
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .quantity-btn {
            background: #007bff;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-btn:hover {
            background: #0056b3;
        }
        
        .quantity-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        
        .quantity-display {
            font-weight: bold;
            font-size: 18px;
            min-width: 40px;
            text-align: center;
        }
        
        .stock-status {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .in-stock {
            background: #d4edda;
            color: #155724;
        }
        
        .low-stock {
            background: #fff3cd;
            color: #856404;
        }
        
        .out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Available Books</h2>
            <div>
                <a href="AddBook.html" class="btn btn-success mr-2">Add Book</a>
                <button class="btn btn-danger" onclick="deleteBook()">Delete Book</button>
            </div>
        </div>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($book = $result->fetch_assoc()): ?>
                    <?php
                        // Get only the filename part, in case the DB has a path
                        $filename = basename($book['image_url']);
                        $quantity = isset($book['quantity']) ? (int)$book['quantity'] : 0;
                        
                        // Determine stock status
                        $stockClass = '';
                        $stockText = '';
                        if ($quantity == 0) {
                            $stockClass = 'out-of-stock';
                            $stockText = 'Out of Stock';
                        } elseif ($quantity <= 5) {
                            $stockClass = 'low-stock';
                            $stockText = 'Low Stock';
                        } else {
                            $stockClass = 'in-stock';
                            $stockText = 'In Stock';
                        }
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img 
                              src="image/<?php echo htmlspecialchars($filename); ?>" 
                              class="card-img-top" 
                              alt="<?php echo htmlspecialchars($book['name']); ?>" 
                              style="height: 250px; object-fit: cover;"
                              onerror="this.onerror=null;this.src='image/default-book.png';" 
                            >
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($book['name']); ?></h5>
                                <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($book['price'], 2); ?></p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><strong>Quantity:</strong></span>
                                    <span class="stock-status <?php echo $stockClass; ?>"><?php echo $stockText; ?></span>
                                </div>
                                
                                <div class="quantity-controls">
                                    <button 
                                        class="quantity-btn" 
                                        onclick="updateQuantity(<?php echo $book['id']; ?>, -1, <?php echo $quantity; ?>)"
                                        <?php echo $quantity <= 0 ? 'disabled' : ''; ?>
                                    >-</button>
                                    <span class="quantity-display" id="qty-<?php echo $book['id']; ?>"><?php echo $quantity; ?></span>
                                    <button 
                                        class="quantity-btn" 
                                        onclick="updateQuantity(<?php echo $book['id']; ?>, 1, <?php echo $quantity; ?>)"
                                    >+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No books available yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
    function deleteBook() {
        var bookName = prompt("Enter the name of the book to delete:");
        if (bookName != null && bookName.trim() != "") {
            if (confirm("Are you sure you want to delete '" + bookName + "'?")) {
                window.location.href = "delete_book.php?name=" + encodeURIComponent(bookName);
            }
        }
    }
    
    function updateQuantity(bookId, change, currentQty) {
        var newQty = currentQty + change;
        
        if (newQty < 0) {
            alert("Quantity cannot be negative!");
            return;
        }
        
        if (confirm("Update quantity to " + newQty + "?")) {
            // Create a form and submit it
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'update_quantity.php';
            
            var bookIdInput = document.createElement('input');
            bookIdInput.type = 'hidden';
            bookIdInput.name = 'book_id';
            bookIdInput.value = bookId;
            
            var quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'new_quantity';
            quantityInput.value = newQty;
            
            form.appendChild(bookIdInput);
            form.appendChild(quantityInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</body>

</html>

<?php $conn->close(); ?>