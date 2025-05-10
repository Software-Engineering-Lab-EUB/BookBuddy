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
