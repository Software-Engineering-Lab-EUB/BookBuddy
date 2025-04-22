<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
include "header.php";
include "db.php";

 // Insert the book into the database
    $stmt = $conn->prepare("INSERT INTO books (title, author, price, stock, category_id, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisss", $title, $author, $price, $stock, $category_id, $imagePath, $description);
    $stmt->execute();
    header("Location: manage_books.php");
    exit();

<!-- Book List -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>

