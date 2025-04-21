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
    <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h3 class="mb-0">Checkout</h3>
                </div>
                <div class="card-body bg-light">
                    <form method="post" action="checkout.php" onsubmit="return validateForm();">

                        <h5 class="mb-4 text-success fw-bold">Shipping Address</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" required placeholder="Enter your full name">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="form-control" required placeholder="e.g. 01XXXXXXXXX">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="division" class="form-label">Division</label>
                            <select name="division" id="division" class="form-select" required>
                                <option value="">Select Division</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chattogram">Chattogram</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Barishal">Barishal</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">City / District</label>
                            <input type="text" name="city" id="city" class="form-control" required placeholder="e.g. Dhaka">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Full Address</label>
                            <textarea name="address" id="address" rows="3" class="form-control" required placeholder="House no, Road no, Area"></textarea>
                        </div>

                        <h5 class="mb-3 text-success fw-bold">Payment Method</h5>
                        <div class="mb-4">
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">Choose Payment Method</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="cod">Cash on Delivery</option>
                            </select>
                        </div>

                        <input type="hidden" name="total_price" value="<?= $total_price ?>">

                        <button type="submit" class="btn btn-success btn-lg w-100">✅ Confirm & Place Order (৳<?= $total_price ?>)</button>
                    </form>
                </div>
            </div>
        </div>
     </div>
 </div>


