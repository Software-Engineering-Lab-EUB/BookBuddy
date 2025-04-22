<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
include "header.php";
include "db.php";


// Handle adding a new book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $title = trim($_POST["title"]);
    $author = trim($_POST["author"]);
    $price = (float) trim($_POST["price"]); // Ensure this is a float
    $stock = (int) trim($_POST["stock"]); // Ensure this is an integer
    $category_id = (int) trim($_POST["category_id"]); // Ensure this is an integer
    $description = trim($_POST["description"]); // Get the description


 // Insert the book into the database
    $stmt = $conn->prepare("INSERT INTO books (title, author, price, stock, category_id, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisss", $title, $author, $price, $stock, $category_id, $imagePath, $description);
    $stmt->execute();
    header("Location: manage_books.php");
    exit();

// Fetch books from the database
$result = $conn->query("SELECT * FROM books");
?>

<div class="container mt-5">
    <h2 class="text-center">Manage Books</h2>


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
     <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["id"]); ?></td>
                <td><?= htmlspecialchars($row["title"]); ?></td>
                <td><?= htmlspecialchars($row["author"]); ?></td>
                <td>$<?= number_format($row["price"], 2); ?></td>
                <td><?= htmlspecialchars($row["stock"]); ?></td>
                

