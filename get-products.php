<?php
require 'db-connection.php';
require 'functions.php'; // ✅ include reusable function
require 'functions/product-function.php'; // functions specific to products

// ✅ Use reusable function
$products = getProducts();

$productListing = getProductListing($products); // This will echo the product listing HTML
echo $productListing;
?>
