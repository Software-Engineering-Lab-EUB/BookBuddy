
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




