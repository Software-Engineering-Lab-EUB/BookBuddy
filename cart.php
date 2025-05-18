
<!-- start session and include database connection -->
<?php
session_start();
include "db.php";

// check if book ID is passed via GET request
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];



?>
