<?php
include 'db-connection.php'; // your DB connection
require 'functions.php'; // ✅ include reusable functions
require 'functions/product-function.php'; // functions specific to products

$product = getProduct_Details();

$productHTML = getProductDetailsHTML($product);
echo $productHTML;
?>
