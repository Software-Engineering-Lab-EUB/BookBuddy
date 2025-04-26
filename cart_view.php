<?php
session_start();
include "db.php";
include "header.php";

<h2 class="text-center">Shopping Cart</h2>

<?php
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<p>Your cart is empty.</p>";
} else {
 echo '<table class="table">';
 echo '<tr><th>Title</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>';
 
 $total_price = 0;
 foreach ($_SESSION['cart'] as $book_id => $qty) {
     $result = $conn->query("SELECT * FROM books WHERE id = $book_id");
     if ($row = $result->fetch_assoc()) {
         $total = $row["price"] * $qty;
         $total_price += $total;
         echo "<tr>
                 <td>{$row['title']}</td>
                 <td>$qty</td>
                 <td>\${$row['price']}</td>
                 <td>\$$total</td>
                 <td><a href='cart_remove.php?id=$book_id' class='btn btn-danger btn-sm'>Remove</a></td>
               </tr>";
         }
     }
  }
?>
?>
