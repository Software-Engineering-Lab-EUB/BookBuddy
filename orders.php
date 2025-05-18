<?php
session_start();
include "db.php"; // Include your database connection
include "header.php"; // Include your header

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Handle order deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ? AND user_id = ? AND status IN ('pending', 'cancelled')");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    header("Location: orders.php");
    exit;
}

include 'header.php';
?>
<!-- extract order list container and heading into partial -->
<div class="container mt-5">
    <h2 class="text-center mb-4">My Orders</h2>
    <div class="table-responsive">
<!--separate order table headers for reuse and clarity -->
        <table class="table table-bordered shadow-sm">
    <thead class="thead-dark">
        <tr>
            <th>Order ID</th>
            <th>Status</th>
            <th>Ordered On</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<!-- move order fetching logic to includes/fetch_orders.php -->
<?php
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
// add loop to iterate and display user orders if available
 if ($result->num_rows > 0):
     while ($order = $result->fetch_assoc()):
?>
<!-- display order ID, status with badge, and formatted order date -->
    <tr>
    <td>#<?= $order['id'] ?></td>
    <td>
        <span class="badge badge-<?= $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary') ?>">
            <?= ucfirst($order['status']) ?>
        </span>
    </td>
    <td><?= date("F j, Y, g:i a", strtotime($order['created_at'])) ?></td>
<!-- add view details button linking to order_details.php -->
        <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-info btn-sm mb-2">View Details</a>
<!-- fetch book list for completed orders using prepared statement -->
        <?php
        if ($order['status'] === 'completed') {
            $order_id = $order['id'];

      $book_stmt = $conn->prepare("
           SELECT b.id, b.title
           FROM order_items oi
           JOIN books b ON oi.book_id = b.id
            WHERE oi.order_id = ?
         ");
      $book_stmt->bind_param("i", $order_id);
      $book_stmt->execute();
      $book_result = $book_stmt->get_result();
        
  // display review button for each book in completed order
      while ($book = $book_result->fetch_assoc()) {
        echo "<a href='description.php?id={$book['id']}' class='btn btn-success btn-sm mb-1'>Review \"{$book['title']}\"</a><br>";
    }
}
// add delete button for pending and cancelled orders with confirmation
if (in_array($order['status'], ['pending', 'cancelled'])) {
    echo "<a href='orders.php?delete_id={$order['id']}' class='btn btn-danger btn-sm mt-2' onclick=\"return confirm('Are you sure you want to delete this order?');\">Delete</a>";
} else {
    echo "<span class='text-muted d-block mt-2'>Cannot delete</span>";
   }
?>
<!-- display message when no orders are found --> 
           <?php 
         endwhile;
      else:
          ?>
          <tr>
               <td colspan="4" class="text-center">No orders found.</td>
           </tr>
        <?php endif; ?>

    



   
