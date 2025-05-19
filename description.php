session_start();
include "db.php";
<!--Validate book ID from URL and handle invalid input-->
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($book_id <= 0) {
    echo "Invalid book ID.";
    exit;
}


