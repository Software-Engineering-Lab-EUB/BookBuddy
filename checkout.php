<?php
session_start();
include "db.php";
include "header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$total_price = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $book_id => $qty) {
        $result = $conn->query("SELECT price FROM books WHERE id = $book_id");
        if ($row = $result->fetch_assoc()) {
            $total_price += $row["price"] * $qty;
        }
    }
} else {
    echo "<p class='alert alert-warning text-center'>Your cart is empty!</p>";
    include "footer.php";
    exit();
}
?>
