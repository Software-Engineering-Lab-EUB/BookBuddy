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
