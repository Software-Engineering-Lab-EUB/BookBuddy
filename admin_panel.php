<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
include "header.php";
?>

<div class="container mt-5">
    <h2 class="text-center">Admin Panel</h2>
    <div class="list-group">
        <a href="manage_books.php" class="list-group-item list-group-item-action">Book Management</a>
        <a href="manage_orders.php" class="list-group-item list-group-item-action">Order Management</a>
        <a href="manage_users.php" class="list-group-item list-group-item-action">User  Management</a>
    </div>
</div>

<?php include "footer.php"; ?>
