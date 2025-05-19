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
