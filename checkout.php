<?php
session_start();
include "header.php";

$total = isset($_SESSION['old_total']) ? $_SESSION['old_total'] : 0;
$discount = isset($_SESSION['discount']) ? $_SESSION['discount'] : 0;
$discountAmount = isset($_SESSION['discount_amount']) ? $_SESSION['discount_amount'] : 0;
$newTotal = isset($_SESSION['discount_total']) ? $_SESSION['discount_total'] : $total;
$coupon_code = isset($_SESSION['coupon_code']) ? $_SESSION['coupon_code'] : $total;
// print_r($_SESSION);
// exit();
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
<form class="bg0 p-t-75 p-b-85" id="orderForm">
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

					<div class="flex-w flex-t bor12 p-b-13">
						<div class="size-208">
							<span class="stext-110 cl2">Subtotal:</span>
						</div>
						<div class="size-209">
							<span class="mtext-110 cl2">$ <?= number_format($total, 2) ?></span>
						</div>
					</div>
					<div class="flex-w flex-t bor12 p-b-13">
						<div class="size-208">
							<span class="stext-110 cl2">Discount:</span>
						</div>
						<div class="size-209">
							<span class="mtext-110 cl2"><?= number_format($discount, 2) ?>%</span>
						</div>
					</div>
					<div class="flex-w flex-t bor12 p-b-13">
						<div class="size-208">
							<span class="stext-110 cl2">Discount Amount:</span>
						</div>
						<div class="size-209">
							<span class="mtext-110 cl2">-$ <?= number_format($discountAmount, 2) ?></span>
						</div>
					</div>

					<div class="flex-w flex-t p-t-27 p-b-33">
						<div class="size-208">
							<span class="mtext-101 cl2">Total:</span>
						</div>
						<div class="size-209 p-t-1">
							<span class="mtext-110 cl2">$ <?= number_format($newTotal, 2) ?></span>
						</div>
					</div>

					<!-- <button class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
						Proceed to Checkout
					</button> -->
					<div class="header-cart-buttons flex-w w-full">
						<a href="#" id="checkoutBtn" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
							Confirm Order
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php include "footer.php"; ?>