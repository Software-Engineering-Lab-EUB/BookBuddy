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

