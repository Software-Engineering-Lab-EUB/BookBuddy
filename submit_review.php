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




