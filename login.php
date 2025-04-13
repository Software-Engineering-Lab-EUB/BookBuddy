<?php
session_start();
include "db.php";
include "header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_name"] = $row["name"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["role"] = $row["role"] ?? "user";

            if ($_SESSION["role"] === "admin") {
                header("Location: admin_index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "<p class='alert alert-danger text-center'>Incorrect password.</p>";
        }
    } else {
        echo "<p class='alert alert-danger text-center'>Email not found.</p>";
    }
}
?>

<div class="container mt-5">
    <div class="card mx-auto shadow-lg" style="max-width: 400px;">
        <div class="card-body">
            <h3 class="text-center">Login</h3>
            <form method="post">
                <input type="email" name="email" class="form-control mb-2" required placeholder="Email">
                <input type="password" name="password" class="form-control mb-2" required placeholder="Password">
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="text-center mt-2">Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
