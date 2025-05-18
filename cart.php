
<!-- start session and include database connection -->
<?php
session_start();
include "db.php";

// check if book ID is passed via GET request
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
// initialize cart in session if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
// Check if the book is already in the cart
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]++;
    } else {
        $_SESSION['cart'][$book_id] = 1;
    }

?>
