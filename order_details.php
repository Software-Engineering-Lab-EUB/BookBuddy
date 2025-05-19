<?php
session_start();
include "db.php";
include "header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Get the order ID from the URL
$order_id = intval($_GET['id']);

// Fetch order details along with book titles
$stmt = $conn->prepare("
    SELECT o.id AS order_id, o.total_price, o.status, o.transaction_id, o.mobile, o.created_at,
           b.title AS book_name, oi.quantity, oi.price
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN books b ON oi.book_id = b.id
    WHERE o.id = ?
");
// Bind order ID parameter to the prepared SQL statement.
$stmt->bind_param("i", $order_id);
// Add HTML boilerplate with Bootstrap and custom styling for order details page.
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
    // Style body with background color and base font.
    body {
        background-color: #f4f7fc;
        font-family: 'Arial', sans-serif;
   }
    // Design main container with padding, border-radius, and shadow.
    .container {
    max-width: 900px;
    margin-top: 50px;
    background-color: white;
    padding: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}
// Style headings and order detail section.
h2 {
    text-align: center;
    color: #333;
    font-size: 2.2rem;
    margin-bottom: 30px;
}
.order-details {
    font-size: 1.1rem;
    color: #555;
    margin-bottom: 20px;
}
.order-details strong {
    font-weight: 600;
    color: #333;
}


    </style>
</head>
<body>
    // Execute the prepared statement and retrieve the result set.
    <?php
if ($stmt->execute()) {
    $result = $stmt->get_result();
// Check if order data exists and initialize container for output.
   if ($result->num_rows > 0) {
        $orderPrinted = false;
        echo "<div class='container'>";
// Display order metadata like status, mobile, and transaction ID once.
           while ($order = $result->fetch_assoc()) {
            if (!$orderPrinted) {
                echo "<h2>Order Details (Order ID: {$order['order_id']})</h2>";
                echo "<div class='order-details'>
                        <p><strong>Total Price:</strong> \${$order['total_price']}</p>
                        <p><strong>Status:</strong> {$order['status']}</p>
                        <p><strong>Transaction ID:</strong> {$order['transaction_id']}</p>
                        <p><strong>Mobile:</strong> {$order['mobile']}</p>
                        <p><strong>Order Date:</strong> {$order['created_at']}</p>
                      </div>";
                echo "<h4>Books in this Order:</h4><ul class='list-group'>";
                $orderPrinted = true;
            }
// Loop through each ordered book and display quantity and price.
            echo "<li class='list-group-item'>
                    <div class='book-info'>
                        <span>{$order['book_name']}</span>
                        <span>Qty: {$order['quantity']} | Price: \${$order['price']}</span>
                    </div>
                </li>";
        }
        echo "</ul></div>";
    }
   // Handle empty result or query execution failure with user-friendly messages.
   else {
        echo "<div class='container mt-5 alert alert-warning'>No order details found for this order ID.</div>";
    }
} else {
    echo "<div class='container mt-5 alert alert-danger'>Error executing query: " . $stmt->error . "</div>";
}
// Include footer and close HTML document structure.
include "footer.php"; // Include footer
?>

</body>
</html>







