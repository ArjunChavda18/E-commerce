<?php
session_start();
require 'db-connection.php';
require 'functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = json_encode([]);
}

$cartData = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];

if (!empty($cartData)) {
    // Decode current cart from session JSON
    $cart = json_decode($_SESSION['cart'], true);

    // Update quantities and remove items with qty=0
    foreach ($cartData as $item) {
        foreach ($cart as $key => &$c) {
            if ($c['id'] == $item['id']) {
                $qty = (int)$item['quantity'];
                if ($qty > 0) {
                    $c['quantity'] = $qty; // update
                } else {
                    unset($cart[$key]); // remove product
                    unset($_SESSION['old_total']);
                }
            }
        }
    }

    // Reindex array after removals
    $cart = array_values($cart);
    $cartCount = count($cart);
    // Save back to session as JSON
    $_SESSION['cart'] = json_encode($cart);
    $_SESSION['cart_count'] = $cartCount;
    
    if ($cartCount > 0) {
    echo json_encode([
        "status" => "success",
        "cart"   => $cart,
        "html"   => getCartTotalBlock(),
        "products_html" => productDetailBlock(),
        "cart_count" => $cartCount
    ]);
    } else {
        echo json_encode([
            "status" => "success",
            "cart"   => [],
            "html"   => "",          // no totals
            "products_html" => "",   // no products
            "cart_count" => 0
        ]);
    }
    exit;
}

echo json_encode(["status" => "no data"]);
