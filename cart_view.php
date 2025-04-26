<?php
session_start();
include "db.php";
include "header.php";

<h2 class="text-center">Shopping Cart</h2>

<?php
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<p>Your cart is empty.</p>";
} else {
  }
?>
?>
