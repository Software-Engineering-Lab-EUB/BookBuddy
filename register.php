<?php
session_start();
include "db.php";
include "header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "<div class='container mt-3'><div class='alert alert-success text-center'>Registration successful! <a href='login.php'>Login now</a></div></div>";
    } else {
        echo "<div class='container mt-3'><div class='alert alert-danger text-center'>Error: " . $stmt->error . "</div></div>";
    }
}
?>

<!-- Match styles with login -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(to right, #667eea, #764ba2);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    .auth-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        color: #fff;
        max-width: 420px;
        width: 100%;
        animation: fadeIn 0.6s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .auth-card input {
        border-radius: 12px;
        border: none;
        padding: 12px;
    }
    .auth-card input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }
    .btn-custom {
        background: #fff;
        color: #2F80ED;
        font-weight: bold;
        border-radius: 12px;
        transition: 0.3s;
    }
    .btn-custom:hover {
        background: #f0f0f0;
        color: #333;
    }
    .google-btn {
        background-color: #DB4437;
        color: #fff;
        font-weight: 600;
        border-radius: 12px;
        border: none;
    }
    .google-btn:hover {
        background-color: #c53727;
    }
    .form-label {
        font-weight: 600;
    }
</style>
<!-- Register Form with Matching UI -->
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="auth-card text-center">
        <div class="mb-4">
            <i class="fas fa-user-plus fa-3x mb-2"></i>
            <h2 class="fw-bold">Create Account</h2>
            
        </div>
        <form method="post">
            <div class="mb-3 text-start">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Your name">
            </div>
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="example@email.com">
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Choose a password">
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-custom">Register</button>
            </div>
            <div class="text-center mb-2">
                <small>Already have an account? <a href="login.php" style="color: #fff; text-decoration: underline;">Login here</a></small>
            </div>
            <hr class="bg-white">
            <a href="google_login.php" class="btn google-btn w-100"><i class="fab fa-google me-2"></i> Sign Up with Google</a>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>
