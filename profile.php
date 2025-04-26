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

// Handle updating user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $user_id);
    if ($stmt->execute()) {
        $_SESSION["user_name"] = $name; // Update session variable
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile.";
    }
}
?>
