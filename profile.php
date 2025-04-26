<?php
session_start();
include "db.php"; // Include your database connection

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
    // Fetch user details
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
