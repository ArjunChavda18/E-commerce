<?php session_start();
require 'includes/db-connection.php';
require 'functions/product-function.php'; //
include "includes/header.php";
?>
<!-- Cart -->
<div class="wrap-header-cart js-panel-cart">
	<div class="s-full js-hide-cart"></div>
	
</div>


<!-- Product -->
<div class="bg0 m-t-23 p-b-140">
	<div class="container">

		<?php require 'heading-of-product.php'; ?>
		
		<div class="container">
			<div class="row isotope-grid" id="product-list">
			</div>
			<div id="pagination" class="flex-c-m flex-w w-full p-t-45">
				<!-- Pagination numbers will be injected here -->
			</div>
			<div class="flex-c-m flex-w w-full p-t-45">
				<a href="#" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04" id="load-more" data-page="2">
					Load More
				</a>
			</div>
		</div>
	</div>
</div>
<?php include "includes/footer.php"; ?>