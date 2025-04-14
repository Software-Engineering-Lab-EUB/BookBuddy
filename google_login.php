<?php
require 'vendor/autoload.php'; // Ensure Google Client is loaded

session_start();

$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID_HERE');
$client->setClientSecret('YOUR_CLIENT_SECRET_HERE');

$client->setRedirectUri('http://localhost/BookStore/google_callback.php');
$client->addScope("email");
$client->addScope("profile");

$login_url = $client->createAuthUrl();

header("Location: $login_url");
exit();
?>
