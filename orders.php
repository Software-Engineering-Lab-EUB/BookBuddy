<?php
session_start();
include "db.php"; // Include your database connection
include "header.php"; // Include your header

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Handle order deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ? AND user_id = ? AND status IN ('pending', 'cancelled')");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    header("Location: orders.php");
    exit;
}

include 'header.php';
?>

   
