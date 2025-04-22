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

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imagePath = 'images/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
    } else {
        $imagePath = null; // Handle the case where no image is uploaded
    }

    // Insert the book into the database
    $stmt = $conn->prepare("INSERT INTO books (title, author, price, stock, category_id, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisss", $title, $author, $price, $stock, $category_id, $imagePath, $description);
    $stmt->execute();
    header("Location: manage_books.php");
    exit();
}


 // Insert the book into the database
    $stmt = $conn->prepare("INSERT INTO books (title, author, price, stock, category_id, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisss", $title, $author, $price, $stock, $category_id, $imagePath, $description);
    $stmt->execute();
    header("Location: manage_books.php");
    exit();

    // Handle editing a book
if (isset($_GET['edit'])) {
    $book_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_book'])) {
        $title = trim($_POST["title"]);
        $author = trim($_POST["author"]);
        $price = (float) trim($_POST["price"]); // Ensure this is a float
        $stock = (int) trim($_POST["stock"]); // Ensure this is an integer
        $category_id = (int) trim($_POST["category_id"]); // Ensure this is an integer
        $description = trim($_POST["description"]); // Get the description

         // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $imagePath = 'images/' . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $imagePath);

            // Update the book with the new image
            $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, price = ?, stock = ?, category_id = ?, image = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssdisssi", $title, $author, $price, $stock, $category_id, $imagePath, $description, $book_id);
        } else {
            // If no new image is uploaded, just update the other fields
            $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, price = ?, stock = ?, category_id = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssdissi", $title, $author, $price, $stock, $category_id, $description, $book_id);
        }

        $stmt->execute();
        header("Location: manage_books.php");
        exit();
    }
}

 // Handle deleting a book
if (isset($_GET['delete'])) {
    $book_id = $_GET['delete'];

    // First, delete any order items that reference this book
    $stmt = $conn->prepare("DELETE FROM order_details WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();

    // Now delete the book
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();

    header("Location: manage_books.php");
    exit();
}

    
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
                

