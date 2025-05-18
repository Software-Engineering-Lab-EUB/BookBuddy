
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
    
//  determine review eligibility based on order and review count
        if ($completed_orders > $user_reviews) {
        $can_review = true;
    }
}
include "header.php";
?>

<!--display book title and image section in description page -->
<div class="container mt-5">
    <h2 class="text-center mb-4"><?= htmlspecialchars($book["title"]); ?></h2>

    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($book['image'] ?? 'Lore.jpg'); ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']); ?>">
        </div>
<!-- render book details and conditionally show Add to Cart button -->
                <div class="col-md-6">
            <p><strong>Author:</strong> <?= htmlspecialchars($book["author"]); ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($book["price"]); ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($book["description"])); ?></p>

            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="cart.php?id=<?= $book["id"]; ?>" class="btn btn-primary">Add to Cart</a>
            <?php else: ?>
                <button class="btn btn-warning" onclick="redirectToLogin();">Add to Cart</button>
            <?php endif; ?>
        </div>
    </div>

<!-- add review section heading and layout container -->
    <div class="mt-5">
    <h4><i class="fas fa-comments"></i> Reviews</h4>
    <div class="row">
<!-- prepare and execute query to fetch reviews and usernames for a book -->
    <?php
        $query = "SELECT r.rating, r.review, u.name AS username FROM reviews r 
          JOIN users u ON r.user_id = u.id 
          WHERE r.book_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

// display book reviews with rating stars and fallback message if empty
if ($result->num_rows > 0) {
    while ($review = $result->fetch_assoc()) {
        echo "<div class='col-md-6'>";
        echo "<div class='card review-card shadow-sm mb-3'>";
        echo "<div class='card-body'>";
        echo "<div class='d-flex align-items-center'>";
        echo "<i class='fas fa-user-circle profile-icon'></i>";
        echo "<h5 class='card-title'>" . htmlspecialchars($review["username"]) . "</h5>";
        echo "</div>";
        echo "<h6 class='card-subtitle mb-2 text-muted'>Rating: " . str_repeat('⭐', $review["rating"]) . "</h6>";
        echo "<p class='card-text'><strong>Comment:</strong> " . nl2br(htmlspecialchars($review["review"])) . "</p>";
        echo "</div></div></div>";
    }
} else {
    echo "<p class='col-12'>No reviews yet.</p>";
         }
     ?>
     </div>
</div>













