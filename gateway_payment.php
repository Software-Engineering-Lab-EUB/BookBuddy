<?php
session_start();
include "header.php";

// Check if checkout data is set
if (!isset($_SESSION['checkout_data'])) {
    echo "<div class='container mt-5 alert alert-danger text-center'>No payment session found.</div>";
    include "footer.php";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulated Payment Gateway</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* All the CSS styles here */
    </style>
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h3 class="text-center mb-4">Simulated Payment Gateway</h3>

    <form action="payment_success.php" method="post" id="payment-form" class="border p-4 rounded bg-light">
        <!-- Mobile Number -->
        <div class="mb-3">
            <label for="mobile" class="form-label">Enter Mobile Number</label>
            <input type="text" name="mobile" class="form-control" required placeholder="e.g. 01XXXXXXXXX">
        </div>

        <!-- Transaction ID -->
        <div class="mb-3">
            <label for="trxid" class="form-label">Transaction ID</label>
            <input type="text" name="trxid" class="form-control" required placeholder="Dummy Transaction ID">
        </div>

        <!-- Payment Method Selection -->
        <div class="mb-3">
            <label class="form-label">Choose Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="">Select Payment Method</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
                <option value="stripe">Stripe</option>
            </select>
        </div>

        <!-- Stripe Payment Form -->
        <div id="stripe-form" style="display:none;">
            <div class="mb-3">
                <label for="card-element" class="form-label">Credit or Debit Card</label>
                <div id="card-element" class="form-control p-2"></div>
                <div id="card-errors" class="text-danger mt-2"></div>
            </div>
            <button type="button" id="stripe-submit" class="btn btn-success w-100">Pay with Stripe</button>
        </div>

        <!-- Normal Pay Now button -->
        <button type="submit" id="normal-submit" class="btn btn-success w-100">Pay Now</button>
    </form>
</div>
