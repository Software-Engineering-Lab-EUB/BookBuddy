<?php
require 'vendor/autoload.php';
include "db.php";

session_start();

$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID_HERE');
$client->setClientSecret('YOUR_CLIENT_SECRET_HERE');

$client->setRedirectUri('http://localhost/BookStore/google_callback.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
// ✅ Check for token errors
    if (isset($token['error'])) {
        echo "Error fetching token: " . htmlspecialchars($token['error']);
        exit();
    }

    $client->setAccessToken($token);

    $oauth = new Google_Service_Oauth2($client);
    $google_user = $oauth->userinfo->get();

    $google_id = $google_user->id;
    $name = $google_user->name;
    $email = $google_user->email;
  // ✅ Check if user already exists in database
    $stmt = $conn->prepare("SELECT id, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["user_name"] = $name;
        $_SESSION["role"] = $row["role"];
    }
    header("Location: index.php");
    exit();
} else {
    echo "No authorization code received from Google.";
}
