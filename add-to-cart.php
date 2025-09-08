<?php
session_start();
// session_unset();


// get POST data
$id       = $_POST['id'];
$name     = $_POST['name'];
$quantity = $_POST['quantity'] ?? 1;
$price    = $_POST['price'] ?? 0;
$color    = $_POST['color'] ?? '';
$size     = $_POST['size'] ?? '';

// initialize cart session as JSON string if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = json_encode([]); // start with empty JSON array
}

if (is_array($_SESSION['cart'])) {
    // convert array to JSON string
    $_SESSION['cart'] = json_encode($_SESSION['cart']);
}

// decode session cart JSON to PHP array
$cart = json_decode($_SESSION['cart'], true);


// add new product
$cart[] = [
    "id" => $id,
    "quantity" => $quantity,
    "name" => $name,
    "price" => $price,
    "size" => $size,
    "color" => $color
];

$cartCount = count($cart);
// save back as JSON
$_SESSION['cart'] = json_encode($cart);
$_SESSION['cart_count'] = $cartCount;

// optional: return JSON response
echo json_encode([
    "status" => "$name is added to cart!",
    "cart"   => $cart,
    "cart_count" => $cartCount
]);
