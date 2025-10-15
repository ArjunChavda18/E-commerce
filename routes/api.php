<?php
session_start();
include __DIR__ . '/../includes/db-connection.php';

// Map each action to its controller & method
$routes = [
    "get-products"       => ["ProductController", "getProducts"],
    "get-product-details" => ["ProductController", "getProductDetails"],

    "add-to-cart"         => ["CartController", "addToCart"],
    "get-cart"           => ["CartController", "getCart"],
    "update-cart"        => ["CartController", "updateCart"],
    "apply-coupon"        => ["CartController", "applyCoupon"],

    "save-billing-details"=> ["CheckoutController", "saveBillingDetails"],
];

// Get action
$action = $_REQUEST['action'] ?? null;

if (!$action || !isset($routes[$action])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    exit;
}

// Load controller file
list($controllerName, $method) = $routes[$action];
$controllerFile = __DIR__ . "/../controllers/$controllerName.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    // $controller = new ProductController();
    if (method_exists($controller, $method)) {
        $result = $controller->$method();
        // header('Content-Type: application/json');
        echo is_array($result) ? json_encode($result) : $result;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Method not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Controller not found']);
}
