<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
include "header.php";
include "db.php";

// Handle updating an order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    if ($stmt->execute()) {
        echo "<p class='alert alert-success text-center'>Order status updated successfully!</p>";
    } else {
        echo "<p class='alert alert-danger text-center'>Error updating order status.</p>";
    }
}

// Handle deleting an order
if (isset($_GET['delete'])) {
    $order_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: manage_orders.php");
    exit();
}

// Fetch orders from the database
$result = $conn->query("SELECT o.order_id, u.name AS user_name, o.total_price, o.status FROM orders o JOIN users u ON o.user_id = u.id");
?>

<div class="container mt-5">
    <h2 class="text-center">Manage Orders</h2>

    <!-- Order List -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["order_id"]); ?></td>
                <td><?= htmlspecialchars($row["user_name"]); ?></td>
                <td>$<?= number_format($row["total_price"], 2); ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <select name="status" class="form-control form-control-sm" required>
                            <option value="pending" <?= $row["status"] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="completed" <?= $row["status"] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="canceled" <?= $row["status"] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                        </select>
                        <input type="hidden" name="order_id" value="<?= $row["order_id"]; ?>">
                        <button type="submit" name="update_order" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>
                <td>
                    <a href="manage_orders.php?delete=<?= $row["order_id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>
