session_start();
include "db.php"; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
<!-- Retrieve and sanitize user input for review submission. -->
$user_id = $_SESSION['user_id'];
$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$review = isset($_POST['review']) ? trim($_POST['review']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate inputs
    if ($book_id === 0 || $rating === 0 || empty($review)) {
        echo "All fields are required.";
        exit;
    }

    if ($rating < 1 || $rating > 5) {
        echo "Rating must be between 1 and 5.";
        exit;
    }
<!-- Fetch count of completed orders for the given book. -->
            $query = "SELECT COUNT(*) as completed_orders 
              FROM orders o 
              JOIN order_items oi ON o.id = oi.order_id 
              WHERE o.user_id = ? AND oi.book_id = ? AND o.status = 'completed'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $completed_orders = $result->fetch_assoc()['completed_orders'] ?? 0;
<!-- Fetch count of existing reviews by the user for this book.-->
    $query = "SELECT COUNT(*) as review_count FROM reviews WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $review_count = $result->fetch_assoc()['review_count'] ?? 0;

<!-- Prevent review submission if user has already reviewed as many times as completed orders. -->
            if ($review_count >= $completed_orders) {
        echo "You have already submitted all reviews for this book based on your completed orders.";
        exit;
    }







