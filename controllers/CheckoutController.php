<?php
include __DIR__ . '/../includes/db-connection.php';
include __DIR__ . '/../functions/checkout-functions.php';

class CheckoutController {
    public function saveBillingDetails() {
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