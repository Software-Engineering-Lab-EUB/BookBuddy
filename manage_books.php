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

    <!-- Add Book Form -->
    <form method="post" enctype="multipart/form-data" class="mb-3">
        <h4>Add New Book</h4>
        <input type="text" name="title" class="form-control mb-2" required placeholder="Book Title">
        <input type="text" name="author" class="form-control mb-2" required placeholder="Author">
        <input type="number" name="price" class="form-control mb-2" required placeholder="Price" step="0.01">
        <input type="number" name="stock" class="form-control mb-2" required placeholder="Stock">
        <select name="category_id" class="form-control mb-2" required>
            <option value="">Select Category</option>
            <?php
            $category_result = $conn->query("SELECT * FROM categories");
            while ($category = $category_result->fetch_assoc()) {
                echo "<option value='" . $category['category_id'] . "'>" . htmlspecialchars($category['category_name']) . "</option>";
            }
            ?>
        </select>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>
        <label for="image">Upload Image:</label>
        <input type="file" name="image" class="form-control mb-2" accept="image/*" required>
        <button type="submit" name="add_book" class="btn btn-primary w-100">Add Book</button>
    </form>



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
                 <td>
                    <?php if ($row["image"]): ?>
                        <img src="<?= htmlspecialchars($row["image"]); ?>" alt="<?= htmlspecialchars($row["title"]); ?>" style="width: 50px; height: auto;" data-toggle="modal" data-target="#imageModal" data-image="<?= htmlspecialchars($row["image"]); ?>" class="thumbnail">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <a href="manage_books.php?edit=<?= $row["id"]; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="manage_books.php?delete=<?= $row["id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
                

