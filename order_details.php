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



