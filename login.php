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
                header("Location: index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "<div class='container mt-3'><div class='alert alert-danger text-center'>Incorrect password.</div></div>";
        }
    } else {
        echo "<div class='container mt-3'><div class='alert alert-danger text-center'>Email not found.</div></div>";
    }
}
?>

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(to right, #667eea, #764ba2);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    .login-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        color: #fff;
        max-width: 400px;
        width: 100%;
        animation: slideUp 0.6s ease-out;
    }
    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    .login-card input {
        border-radius: 12px;
        border: none;
        padding: 12px;
    }
    .login-card input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }
    .login-btn {
        background: #fff;
        color: #764ba2;
        font-weight: bold;
        border-radius: 12px;
        transition: 0.3s;
    }
    .login-btn:hover {
        background: #f0f0f0;
        color: #333;
    }
    .form-label {
        font-weight: 600;
    }
</style>

<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="login-card text-center">
        <div class="mb-4">
            <i class="fas fa-user-circle fa-3x mb-2"></i>
            <h2 class="fw-bold">Sign In</h2>
            <p class="mb-0">Welcome back! Please login to continue</p>
        </div>
        <form method="post">
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" required placeholder="example@email.com">
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn login-btn">Login</button>
            </div>
            <small>Don’t have an account? <a href="register.php" style="color: #fff; text-decoration: underline;">Register here</a></small>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>
