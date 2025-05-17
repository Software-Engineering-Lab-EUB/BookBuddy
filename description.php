<?php
session_start();
include "db.php";
include "header.php";

// Check if the book ID is set in the URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

