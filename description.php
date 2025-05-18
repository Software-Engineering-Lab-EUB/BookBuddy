
<?php
session_start();
include "db.php";

// / Get book ID from URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($book_id <= 0) {
    echo "Invalid book ID.";
    exit;
}
// // Fetch book data
// prepare and execute query to fetch book by ID
$query = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
// handle case where book is not found and fetch book data
if ($result->num_rows == 0) {
    echo "Book not found.";
    exit;
}
$book = $result->fetch_assoc();

//  Check if user can review
//  review eligibility flag and fetch user ID from session
$can_review = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Check completed orders
    $query = "SELECT COUNT(*) as completed_orders 
              FROM orders o 
              JOIN order_items oi ON o.id = oi.order_id 
              WHERE o.user_id = ? AND oi.book_id = ? AND o.status = 'completed'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $completed_orders = $result->fetch_assoc()['completed_orders'] ?? 0;

    // Check existing reviews
    $query = "SELECT COUNT(*) as user_reviews FROM reviews WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_reviews = $result->fetch_assoc()['user_reviews'] ?? 0;






