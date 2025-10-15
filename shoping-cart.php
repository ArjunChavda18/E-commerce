<?php
session_start();
require 'includes/db-connection.php';
require 'functions/cart-functions.php';

$total = 0;
if (isset($_SESSION['cart'])) {
	$cart = json_decode($_SESSION['cart'], true);
}
$coupon_code = $_SESSION['coupon_code'] ?? "";
$discount = $_SESSION['discount'] ?? 0;
include "includes/header.php";
?>
<style>
	.stext-104:focus {
    border: 2px solid #2293efff; /* orange border */
    box-shadow: 0 0 5px rgba(255, 94, 0, 0.4); /* soft glow */
}
</style>
<!-- Cart -->
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

		<span class="stext-109 cl4">
			Shoping Cart
		</span>
	</div>
</div>


<!-- Shoping Cart -->
<form class="bg0 p-t-75 p-b-85" action="apply_coupon.php" method="post">
	<div class="container">
		<?php if (!empty($cart)) : ?>
			<div class="row">
				<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
						<div class="wrap-table-shopping-cart">
							<?php
							$cartobj = new Cart;
							echo $cartobj->productDetailBlock(); ?>
						</div>

						<!-- Coupon + Update -->
						<div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
							<div class="flex-w flex-m m-r-20 m-tb-5">
								<input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5"
									type="text" name="coupon" placeholder="Coupon Code" id="coupon_code" value="<?php echo htmlspecialchars($coupon_code); ?>">

								<div class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5" id="apply_coupon">
									Apply coupon
								</div>
							</div>

							<div class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10" id="update-cart">
								Update Cart
							</div>
						</div>
						<p id="coupon_message"></p>
					</div>
				</div>

				<!-- Cart Totals -->
				<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
					<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
						<h4 class="mtext-109 cl2 p-b-30">Cart Totals</h4>
						
						<div class="get-total-container">
							
							<?php
							$cartobj = new Cart;
							$cartTotal = $cartobj->calculateCartTotals();
							echo $cartobj->getCartTotalBlock($cartTotal['total'], $cartTotal['discount'], $cartTotal['discountAmount'], $cartTotal['newTotal']);
							?>
						</div>

						<div class="header-cart-buttons flex-w w-full">
							<a href="checkout.php" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
								Proceed to Checkout
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div id="empty-cart-message" style="text-align:center; padding:50px;">
				<h4>Your cart is empty 🛒</h4>
				<a href="product.php" class="btn btn-primary mt-3">Go to Products</a>
			</div>
		<?php endif; ?>
		<div id="empty-cart-message2" style="display:none; text-align:center; padding:50px;">
			<h4>Your cart is empty 🛒</h4>
			<a href="product.php" class="btn btn-primary mt-3">Go to Products</a>
		</div>
	</div>
</form>
<?php include "includes/footer.php"; ?>