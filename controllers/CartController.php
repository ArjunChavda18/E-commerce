<?php
include __DIR__ . '/../includes/db-connection.php';
require '../functions/cart-functions.php';

class CartController {
    public function addToCart() {
        $id       = $_POST['id'] ?? '';
        $name     = $_POST['name'] ?? '';
        $quantity = $_POST['quantity'] ?? 1;
        $price    = $_POST['price'] ?? 0;
        $color    = $_POST['color'] ?? '';
        $size     = $_POST['size'] ?? '';
        $cartobj = new Cart;
        $cartobj->addToCart($id, $name, $quantity, $price, $color, $size);
    }

    public function getCart() {
        $cartobj = new Cart;
        echo $cartobj->getCart();
    }

    public function updateCart() {
        $cartData = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];
        $cartobj = new Cart;
        $cartobj->updateCart($cartData);
    }

    public function applyCoupon() {
        if (!empty($_POST['coupon_code'])) {
            $coupon_code = trim($_POST['coupon_code']);
            $cartobj = new Cart;
            $cartobj->applyCoupon($coupon_code);
        }
    }
}