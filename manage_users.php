<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
include "header.php";
include "db.php";

// Handle adding a new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $role = trim($_POST["role"]);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();
}

// Handle editing a user
if (isset($_GET['edit'])) {
    $user_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $role = trim($_POST["role"]);

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
        $stmt->execute();
        header("Location: manage_users.php");
        exit();
    }
}

// Handle deleting a user
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Fetch users from the database
$result = $conn->query("SELECT * FROM users");
?>

<div class="container mt-5">
    <h2 class="text-center">Manage Users</h2>

    <!-- Add User Form -->
    <form method="post" class="mb-3">
        <h4>Add New User</h4>
        <input type="text" name="name" class="form-control mb-2" required placeholder="User  Name">
        <input type="email" name="email" class="form-control mb-2" required placeholder="Email">
        <input type="password" name="password" class="form-control mb-2" required placeholder="Password">
        <select name="role" class="form-control mb-2" required>
            <option value="user">User </option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="add_user" class="btn btn-primary w-100">Add User</button>
    </form>

    <!-- User List -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["id"]); ?></td>
                <td><?= htmlspecialchars($row["name"]); ?></td>
                <td><?= htmlspecialchars($row["email"]); ?></td>
                <td><?= htmlspecialchars($row["role"]); ?></td>
                <td>
                    <a href="manage_users.php?edit=<?= $row["id"]; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="manage_users.php?delete=<?= $row["id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Edit User Form -->
    <?php if (isset($_GET['edit'])): ?>
        <h4>Edit User</h4>
        <form method="post">
            <input type="text" name="name" class="form-control mb-2" required placeholder="User  Name" value="<?= htmlspecialchars($user['name']); ?>">
            <input type="email" name="email" class="form-control mb-2" required placeholder="Email" value="<?= htmlspecialchars($user['email']); ?>">
            <select name="role" class="form-control mb-2" required>
                <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>User </option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <button type="submit" name="update_user" class="btn btn-primary w-100">Update User</button>
        </form>
    <?php endif; ?>
</div>

<?php include "footer.php"; ?>
