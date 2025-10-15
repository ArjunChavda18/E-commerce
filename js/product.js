var totalPagesGlobal = 0;

$(document).ready(function () {
	// let currentPage = 1;
	
	// loadProducts(); 
	// clickEvent();
	window.manager = new productManager();
	manager.loadProducts(); 
	manager.clickEvent();
});

class productManager {
	constructor() {
		this.currentPage = 1;
		this.totalPagesGlobal = 0;
	}
	
	loadProducts(page = 1, search = '') {
		$.ajax({
			url: 'routes/api.php',
			method: 'GET',
			dataType: 'json',
			data: {
				action: 'get-products',
				page: page,
				search: search
			},
			success: function (response) {
				let productsHtml = response.html;
				let totalPagesGlobal = response.totalPages;
				if (page === 1) {
					// First load (or after search): reset product list
					$('#product-list').html(productsHtml);
				} else {
					// Load more: append
					$('#product-list').append(productsHtml);
				}
				let nextPage = page + 1;
				$('#load-more').data('page', nextPage);

				// refresh pagination info
				totalPagesGlobal = response.totalPages;

				// hide/show Load More depending on remaining pages
				if (page >= totalPagesGlobal || totalPagesGlobal <= 1) {
					$('#load-more').hide();
					// console.log('Hiding Load More button');
				} else {
					$('#load-more').show();
					// console.log('Showing Load More button');
				}
				setTimeout(() => {
					$('.isotope-grid').isotope('reloadItems').isotope();
				}, 200);
				// reload isotope layout (if using isotope grid)
				
			},
			error: function (err) {
				console.log(err);
				alert('Failed to load products.');
			}
		});
	}

	clickEvent(){
		// Load more products
		$(document).on('click', '#load-more', function (e) {
			e.preventDefault();
			let page = $(this).data('page');
			let search = $('#search').val(); // get search text
			manager.loadProducts(page, search);
		});

		// Trigger search
		$('#search').on('keyup', function () {
			let query = $(this).val();
			manager.currentPage = 1; // reset page
			manager.loadProducts(manager.currentPage, query); // reload products with search
		});
		$(document).on('click', '.js-show-modal1', function(e) {
			e.preventDefault();
		
			let productId = $(this).data('id');
			$.ajax({
				url: 'routes/api.php',
				method: 'GET',
				data: { id: productId,
						action: 'get-product-details'  // specify action to get product details
					},
				success: function(response) {
					$('.wrap-modal1 .bg0').html(response); // inject modal HTML
					$('.js-modal1').addClass('show-modal1');
		
					$('.wrap-slick3').each(function(){
						$(this).find('.slick3').slick({
							slidesToShow: 1,
							slidesToScroll: 1,
							fade: true,
							infinite: true,
							autoplay: false,
							autoplaySpeed: 6000,
		
							arrows: true,
							appendArrows: $(this).find('.wrap-slick3-arrows'),
							prevArrow:'<button class="arrow-slick3 prev-slick3"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
							nextArrow:'<button class="arrow-slick3 next-slick3"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
		
							dots: true,
							appendDots: $(this).find('.wrap-slick3-dots'),
							dotsClass:'slick3-dots',
							customPaging: function(slick, index) {
								var portrait = $(slick.$slides[index]).data('thumb');
								return '<img src=" ' + portrait + ' "/><div class="slick3-dot-overlay"></div>';
							},  
						});
					});
		
					$(".js-select2").each(function(){
						$(this).select2({
							minimumResultsForSearch: 20,
							dropdownParent: $(this).next('.dropDownSelect2')
						});
					});
		
					initQuantityButtons(); // initialize quantity buttons inside modal
				}
			});
		});
		
		// Close modal
		$(document).on('click', '.js-hide-modal1', function() {
			$('.js-modal1').removeClass('show-modal1');
		});
	}
}