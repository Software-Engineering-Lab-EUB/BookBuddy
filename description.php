<?php
session_start();
include "db.php";
include "header.php";

// Check if the book ID is set in the URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

// Prepare and execute the query to fetch book details
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the book exists
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "<p class='text-center'>Book not found.</p>";
        exit;
    }
} else {
    echo "<p class='text-center'>No book selected.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title><?= htmlspecialchars($book['title']); ?> - Book Details</title>
</head>
<body>
