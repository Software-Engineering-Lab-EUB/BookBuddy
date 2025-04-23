<?php
session_start();
include "db.php";
include "header.php";
// Check if checkout data exists
if (!isset($_SESSION['checkout_data'])) {
    echo "<p class='alert alert-danger text-center'>Payment failed or session expired.</p>";
    include "footer.php";
    exit();
}

$data = $_SESSION['checkout_data'];
$user_id = $data['user_id'];
$full_address = $data['full_address'];
$total_price = $data['total_price'];
$payment_method = $_POST['payment_method'] ?? $data['payment_method'] ?? 'N/A';
$cart = $data['cart'];
include "footer.php";
?>
