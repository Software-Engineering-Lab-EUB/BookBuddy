<?php
session_start();
include "db.php"; // Include your database connection

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>
