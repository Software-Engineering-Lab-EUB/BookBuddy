<?php
 // Start the session to access session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Book Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= isset($_SESSION["role"]) && $_SESSION["role"] === "admin" ? "admin_index.php" : "index.php"; ?>">BookBuddy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

                    <?php if (isset($_SESSION["user_id"])): ?>
                        <li class="nav-item">
                            <a class="nav-link">Welcome, <?= $_SESSION["role"] === "admin" ? "Admin " : ""; ?><?= htmlspecialchars($_SESSION["user_name"]); ?></a>
                        </li>

                        <?php if ($_SESSION["role"] === "admin"): ?>
                            <li class="nav-item"><a class="nav-link" href="admin_panel.php">Admin Panel</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="cart_view.php">Cart</a></li>
                            <li class="nav-item"><a class="nav-link" href="orders.php">My Orders</a></li>
                        <?php endif; ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                My Account
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                                <li class="dropdown-item">Username: <?= htmlspecialchars($_SESSION["user_name"]); ?></li>
                                <li class="dropdown-item">Email: <?= htmlspecialchars($_SESSION["email"] ?? 'Not Available'); ?></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
