<?php
session_start();
include "db.php";
include "header.php";
// Check if checkout data exists
if (!isset($_SESSION['checkout_data'])) {
    echo "<p class='alert alert-danger text-center'>Payment failed or session expired.</p>";
    include "footer.php";
    exit();
}

$data = $_SESSION['checkout_data'];
$user_id = $data['user_id'];
$full_address = $data['full_address'];
$total_price = $data['total_price'];
$payment_method = $_POST['payment_method'] ?? $data['payment_method'] ?? 'N/A';
$cart = $data['cart'];
// Get transaction ID and mobile from payment form
$trxid = $_POST['trxid'] ?? 'N/A';
$mobile = $_POST['mobile'] ?? 'N/A';

// Insert order into database
$stmt = $conn->prepare("INSERT INTO orders (user_id, address, total_price, payment_method, transaction_id, mobile, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
$stmt->bind_param("ssdsss", $user_id, $full_address, $total_price, $payment_method, $trxid, $mobile);
$stmt->execute();
$order_id = $conn->insert_id;
// Insert each cart item into order_items
foreach ($cart as $book_id => $qty) {
    $stmt = $conn->prepare("SELECT price FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $price = $book['price'];

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $order_id, $book_id, $qty, $price);
    $stmt->execute();
}
// Clear cart and checkout session
unset($_SESSION['cart']);
unset($_SESSION['checkout_data']);

// Show confirmation
echo "<div class='container mt-5'>";
echo "<div class='alert alert-success text-center'>";
echo "<h4>Payment Successful!</h4>";
echo "<p>Your order has been placed successfully.</p>";
echo "<p><strong>Payment Method:</strong> $payment_method</p>";
echo "<p><strong>Transaction ID:</strong> $trxid</p>";
echo "<p><strong>Mobile:</strong> $mobile</p>";
echo "<a href='orders.php' class='btn btn-primary mt-3'>Go to Orders</a>";
echo "</div></div>";
include "footer.php";
?>
