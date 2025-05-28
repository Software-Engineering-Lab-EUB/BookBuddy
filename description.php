<?php
session_start();

// Include database config
include "db.php";

// Get book ID from URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) :
if ($book_id <= 0) {
    echo "Invalid book ID.";
    exit;
}
// Fetch book data
$query = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Book not found.";
    exit;
}
$book = $result->fetch_assoc();

