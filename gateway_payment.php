<?php
session_start();
include "header.php";

// Check if checkout data is set
if (!isset($_SESSION['checkout_data'])) {
    echo "<div class='container mt-5 alert alert-danger text-center'>No payment session found.</div>";
    include "footer.php";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulated Payment Gateway</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* All the CSS styles here */
    </style>
</head>
