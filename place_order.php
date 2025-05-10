session_start();
include "db.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $total_price = 0;

    foreach ($_SESSION['cart'] as $book_id => $qty) {
        $book = $conn->query("SELECT price FROM books WHERE id = $book_id")->fetch_assoc();
        $total_price += $book['price'] * $qty;
    }
  // Insert into orders
    $conn->query("INSERT INTO orders (user_id, address, payment_method, total_price) VALUES (
        $user_id, '$address', '$payment_method', $total_price
    )");
    $order_id = $conn->insert_id;
