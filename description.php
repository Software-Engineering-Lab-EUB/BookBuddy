// solve  confilict
<?php
session_start();

// Include database config
include "db.php";

<!--Validate book ID from URL and handle invalid input-->
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($book_id <= 0) {
    echo "Invalid book ID.";
    exit;
}
<!--Prepare and execute query to fetch book data by ID-->
    $query = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

<!--Check if book exists and retrieve result from database -->
    if ($result->num_rows == 0) {
    echo "Book not found.";
    exit;
}
$book = $result->fetch_assoc();

<!--Initialize review permission flag as false by default-->
    $can_review = false;

<!--Check if user is logged in and retrieve user ID from session-->
    if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
<!--Fetch count of completed orders for this book by the user-->
        $query = "SELECT COUNT(*) as completed_orders 
              FROM orders o 
              JOIN order_items oi ON o.id = oi.order_id 
              WHERE o.user_id = ? AND oi.book_id = ? AND o.status = 'completed'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $completed_orders = $result->fetch_assoc()['completed_orders'] ?? 0;

<!--Allow review only if completed orders exceed submitted reviews-->    
    if ($completed_orders > $user_reviews) {
        $can_review = true;
    }
}

include "header.php";
?>




