<?php
session_start();
require 'db-connection.php'; // your DB connection file
require 'functions/cart-functions.php'; // ✅ include reusable functions

echo getCart($pdo); // Call the function and output the cart HTML
?>
