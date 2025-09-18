<?php
session_start();
require 'db-connection.php';
require 'functions/cart-functions.php'; // ✅ include reusable functions
require 'functions/product-function.php'; // functions specific to products
require 'functions/checkout-functions.php'; // functions specific to checkout

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    
    if ($action === 'getProducts') {
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit  = 4;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $id = $_GET['id'] ?? 0;
        $productobj = new Product($id);
        $products = $productobj->getProducts($search);
        $productListing = $productobj->getProductListing($products); // This will echo the product listing HTML
        $Pagination = $productobj->pagination($search);
        echo json_encode(['html' => $productListing, 'totalPages' => $Pagination]);
        // echo $productListing;
    }

    // if ($action === 'pagination') {
    //     $limit = 4; // products per page
    //     $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
    // }

    if ($action === 'getProductDetails') {
        $id = $_GET['id'] ?? 0;
        $productobj = new Product($id);
        $product = $productobj->getProduct_Details();
        $productHTML = $productobj->getProductDetailsHTML($product);
        echo $productHTML;
    }
    
    if ($action === 'addToCart') {
        $id       = $_POST['id'] ?? '';
        $name     = $_POST['name'] ?? '';
        $quantity = $_POST['quantity'] ?? 1;
        $price    = $_POST['price'] ?? 0;
        $color    = $_POST['color'] ?? '';
        $size     = $_POST['size'] ?? '';
        $cartobj = new Cart;
        $cartobj->addToCart($id, $name, $quantity, $price, $color, $size);
    }
    
    if ($action === 'getCart') {
        // Return the cart HTML
        $cartobj = new Cart;
        echo $cartobj->getCart();
    }

    if ($action === 'updateCart') {
        $cartData = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];
        $cartobj = new Cart;
        $cartobj->updateCart($cartData);
    }

    if ($action === 'applyCoupon') {
        if (!empty($_POST['coupon_code'])) {
            $coupon_code = trim($_POST['coupon_code']);
            $cartobj = new Cart;
            $cartobj->applyCoupon($coupon_code);
        }
    }

    if ($action === 'saveBillingDetails') {
        $full_name = trim($_POST['data']['name']);
        $email = trim($_POST['data']['email']);
        $phone_number = trim($_POST['data']['phone']);
        $pincode = trim($_POST['data']['pincode']);
        $city = trim($_POST['data']['city']);
        $shipping_address = trim($_POST['data']['address']);
        $checkoutobj = new Billing;
        $checkoutobj->saveBillingDetails($full_name, $email, $phone_number, $pincode, $city, $shipping_address);
    }
}