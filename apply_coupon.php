<?php
session_start();

require 'db-connection.php'; // your database connection
require 'functions.php';


// If coupon is entered, check it
if (!empty($_POST['coupon_code'])) {
    $coupon_code = trim($_POST['coupon_code']);

    $stmt = $pdo->prepare("SELECT discount FROM coupons WHERE coupon_code = ?");
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch();

    if ($coupon) {
        $_SESSION['coupon_code'] = $coupon_code;

        echo json_encode([
            'status' => 'success',
            'html'   => getCartTotalBlock(),
        ]);
        
    } else {
        unsetDiscountSession();

        echo json_encode([
            'status' => 'invalid',
            'message' => 'Invalid coupon code',
            'discount_message' => 'No discount applied',
            'html'   => getCartTotalBlock(),
        ]);
    }
} else {
    unsetDiscountSession();
    echo json_encode([
        'status' => 'no_coupon',
        'message' => 'No coupon applied',
        'discount_message' => 'No discount applied',
        'html'   => getCartTotalBlock(),
    ]);
}
