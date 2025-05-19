session_start();
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

<!--Check if book exists and retrieve result from database -->
    if ($result->num_rows == 0) {
    echo "Book not found.";
    exit;
}
$book = $result->fetch_assoc();
<!--Initialize review permission flag as false by default-->
    $can_review = false;
<!--Check if user is logged in and retrieve user ID from session-->
    if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];






