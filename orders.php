<?php
session_start();
include "db.php"; // Include your database connection
include "header.php"; // Include your header

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

    // Handle order deletion
if (isset($_GET['delete_id'])) {
    $order_id = intval($_GET['delete_id']);
    $user_id = $_SESSION["user_id"];

    // Check if the order belongs to the user
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        
        // Check the status of the order
        if ($order['status'] === 'completed') {
            echo "<p>You cannot delete a completed order. Please contact an admin if you need assistance.</p>";
        } else {
               // Delete the order and its associated items
             $conn->query("DELETE FROM order_items WHERE order_id = $order_id");
             $conn->query("DELETE FROM orders WHERE id = $order_id");
               // Redirect back to orders page with a success message
             header("Location: orders.php?message=Order deleted successfully.");
             exit();
        }
    } else {
        echo "<p>Order not found or you do not have permission to delete this order.</p>";
    }
}

    
?>
