<?php
session_start();
include "db.php";
include "header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$total_price = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $book_id => $qty) {
        $result = $conn->query("SELECT price FROM books WHERE id = $book_id");
        if ($row = $result->fetch_assoc()) {
            $total_price += $row["price"] * $qty;
        }
    }
} else {
    echo "<p class='alert alert-warning text-center'>Your cart is empty!</p>";
    include "footer.php";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $division = $_POST["division"];
    $city = $_POST["city"];
    $address_detail = $_POST["address"];
    $payment_method = $_POST["payment_method"];

    $full_address = "$full_name, $phone, $address_detail, $city, $division";
    $user_id = $_SESSION["user_id"];

    if ($payment_method === "cod") {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, address, total_price, payment_method, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssds", $user_id, $full_address, $total_price, $payment_method);
        $stmt->execute();
        $order_id = $conn->insert_id;

        foreach ($_SESSION['cart'] as $book_id => $qty) {
            $stmt = $conn->prepare("SELECT price FROM books WHERE id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $book = $result->fetch_assoc();
            $price = $book['price'];

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $order_id, $book_id, $qty, $price);
            $stmt->execute();
        }

        unset($_SESSION['cart']);
        header("Location: orders.php");
        exit();
    } else {
        $_SESSION['checkout_data'] = [
            'user_id' => $user_id,
            'full_address' => $full_address,
            'total_price' => $total_price,
            'payment_method' => $payment_method,
            'cart' => $_SESSION['cart']
        ];
        header("Location: gateway_payment.php");
        exit();
    }
}

?>
