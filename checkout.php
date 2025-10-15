<?php
session_start();
require('vendor/autoload.php'); // Install with composer require razorpay/razorpay
use Razorpay\Api\Api;

require 'includes/db-connection.php';
require 'functions/cart-functions.php';
$cartobj = new Cart;
$cartTotal = $cartobj->calculateCartTotals();
if (isset($_SESSION['cart'])) {
	$cart = json_decode($_SESSION['cart'], true);
}
$api = new Api("rzp_test_DdpitvgB8j7MSb", "M2yjW2hbXbopOCdknJmmLc6L");
$coupon_code     = isset($_SESSION['coupon_code']) ? $_SESSION['coupon_code'] : 0;
$total           = isset($_SESSION['old_total']) ? $_SESSION['old_total'] : 0;
$discount_total  = isset($_SESSION['discount_total']) ? $_SESSION['discount_total'] : 0;
// print_r($_SESSION);
// Decide final amount
if (empty($coupon_code)) {
    // Coupon not applied → use old total
    $final_amount = $total;
} else {
    // Coupon applied → use discounted total
    $final_amount = $discount_total;
}

// Convert to paise for Razorpay
$amountInPaise = (int) round($final_amount * 100);

// Create order (amount in paise: 100 INR = 100 * 100 = 10000)
// print_r($amountInPaise);
$orderData = [
    'receipt'         => 'rcptid_11',
    'amount'          => $amountInPaise,
    'currency'        => 'INR',
    'payment_capture' => 1 // Auto capture
];

$razorpayOrder = $api->order->create($orderData);
$orderId = $razorpayOrder['id'];

include "includes/header.php";
?>
<!-- Cart -->
<style>
	/* Default style */
	input {
		border-radius: 10px;
		border: 1px solid #ccc; /* normal border */
		outline: none; /* remove default browser outline */
		transition: border-color 0.3s ease;
	}

	/* Highlight on focus */
	input:focus {
		border: 2px solid #2293efff;
	}

</style>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<div class="wrap-header-cart js-panel-cart">
	<div class="s-full js-hide-cart"></div>

	<div class="header-cart flex-col-l p-l-65 p-r-25">
		<div class="header-cart-title flex-w flex-sb-m p-b-8">
			<span class="mtext-103 cl2">
				Your Cart
			</span>

			<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
				<i class="zmdi zmdi-close"></i>
			</div>
		</div>
	</div>
</div>


<!-- breadcrumb -->
<div class="container" style="margin-top: 5rem;">
	<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
		<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
			Home
			<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
		</a>
		<a href="shoping-cart.php" class="stext-109 cl8 hov-cl1 trans-04">
			Cart
			<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
		</a>
		<span class="stext-109 cl4">
			Checkout
		</span>
	</div>
</div>


<!-- Shoping Cart -->
<form class="bg0 p-t-75 p-b-85" id="orderForm" method="post" action="checkout-functions.php">
	<input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
	<input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
	<input type="hidden" name="razorpay_signature" id="razorpay_signature">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">

				<!-- Billing Details -->
				<h4 class="mtext-109 cl2 p-b-30">Billing Details</h4>

				<div class="m-b-15">
					<input id="name" class="stext-111 cl2 plh3 size-116 p-l-20"
					type="text" name="name" placeholder="Full Name" required>
					<small id="name-error" class="text-danger"></small>
				</div>

				<div class="m-b-15">
					<input id="email" class="stext-111 cl2 plh3 size-116 p-l-20"
					type="email" name="email" placeholder="Email Address" required>
					<small id="email-error" class="text-danger"></small>
				</div>

				<div class="m-b-15">
					<input id="phone" class="stext-111 cl2 plh3 size-116 p-l-20"
					type="number" name="phone" placeholder="Phone Number" required>
					<small id="phone-error" class="text-danger"></small>
				</div>

				<div class="m-b-15">
					<input id="pincode" class="stext-111 cl2 plh3 size-116 p-l-20"
					type="number" name="pincode" placeholder="Pincode" required>
					<small id="pincode-error" class="text-danger"></small>
				</div>

				<div class="m-b-15">
					<input id="city" class="stext-111 cl2 plh3 size-116 p-l-20"
					type="text" name="city" placeholder="City" required>
					<small id="city-error" class="text-danger"></small>
				</div>

				<div class="m-b-15">
					<input id="address" class="stext-111 cl2 plh3 size-116 p-l-20"
					type="text" name="address" placeholder="Shipping Address" required>
					<small id="address-error" class="text-danger"></small>
				</div>
			</div>

			<!-- Cart Totals -->
			<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
				<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
					<h4 class="mtext-109 cl2 p-b-30">Cart Totals</h4>

					<?php
					$cartobj = new Cart;
					echo $cartobj->getCartTotalBlock($cartTotal['total'], $cartTotal['discount'], $cartTotal['discountAmount'], $cartTotal['newTotal']);
					?>
					<div class="header-cart-buttons flex-w w-full">
						<a href="#" id="rzp-button1" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
							Pay with Razorpay
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script src="js/checkout.js"></script>
<script>
		const checkoutObj = new Checkout();
        var options = {
            "key": "<?php echo "rzp_test_DdpitvgB8j7MSb"; ?>",
            "amount": "<?php echo $amountInPaise; ?>",
            "currency": "INR",
            "name": "My Store",
            "description": "Test Transaction",
            "order_id": "<?php echo $orderId; ?>",
            "handler": function (response){
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                //document.getElementById('orderForm').submit();
				
				checkoutObj.submitOrder();
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        var rzp1 = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function(e){
			if(checkoutObj.validateCheckout()){
				return false;
			}
            rzp1.open();
            e.preventDefault();
        }
    </script>
<?php include "includes/footer.php"; ?>