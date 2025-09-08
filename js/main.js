
(function ($) {
    "use strict";

    /*[ Load page ]
    ===========================================================*/
    $(".animsition").animsition({
        inClass: 'fade-in',
        outClass: 'fade-out',
        inDuration: 1500,
        outDuration: 800,
        linkElement: '.animsition-link',
        loading: true,
        loadingParentElement: 'html',
        loadingClass: 'animsition-loading-1',
        loadingInner: '<div class="loader05"></div>',
        timeout: false,
        timeoutCountdown: 5000,
        onLoadEvent: true,
        browser: [ 'animation-duration', '-webkit-animation-duration'],
        overlay : false,
        overlayClass : 'animsition-overlay-slide',
        overlayParentElement : 'html',
        transition: function(url){ window.location.href = url; }
    });
    
    /*[ Back to top ]
    ===========================================================*/
    var windowH = $(window).height()/2;

    $(window).on('scroll',function(){
        if ($(this).scrollTop() > windowH) {
            $("#myBtn").css('display','flex');
        } else {
            $("#myBtn").css('display','none');
        }
    });

    $('#myBtn').on("click", function(){
        $('html, body').animate({scrollTop: 0}, 300);
    });


    /*==================================================================
    [ Fixed Header ]*/
    var headerDesktop = $('.container-menu-desktop');
    var wrapMenu = $('.wrap-menu-desktop');

    if($('.top-bar').length > 0) {
        var posWrapHeader = $('.top-bar').height();
    }
    else {
        var posWrapHeader = 0;
    }
    

    if($(window).scrollTop() > posWrapHeader) {
        $(headerDesktop).addClass('fix-menu-desktop');
        $(wrapMenu).css('top',0); 
    }  
    else {
        $(headerDesktop).removeClass('fix-menu-desktop');
        $(wrapMenu).css('top',posWrapHeader - $(this).scrollTop()); 
    }

    $(window).on('scroll',function(){
        if($(this).scrollTop() > posWrapHeader) {
            $(headerDesktop).addClass('fix-menu-desktop');
            $(wrapMenu).css('top',0); 
        }  
        else {
            $(headerDesktop).removeClass('fix-menu-desktop');
            $(wrapMenu).css('top',posWrapHeader - $(this).scrollTop()); 
        } 
    });


    /*==================================================================
    [ Menu mobile ]*/
    $('.btn-show-menu-mobile').on('click', function(){
        $(this).toggleClass('is-active');
        $('.menu-mobile').slideToggle();
    });

    var arrowMainMenu = $('.arrow-main-menu-m');

    for(var i=0; i<arrowMainMenu.length; i++){
        $(arrowMainMenu[i]).on('click', function(){
            $(this).parent().find('.sub-menu-m').slideToggle();
            $(this).toggleClass('turn-arrow-main-menu-m');
        })
    }

    $(window).resize(function(){
        if($(window).width() >= 992){
            if($('.menu-mobile').css('display') == 'block') {
                $('.menu-mobile').css('display','none');
                $('.btn-show-menu-mobile').toggleClass('is-active');
            }

            $('.sub-menu-m').each(function(){
                if($(this).css('display') == 'block') { console.log('hello');
                    $(this).css('display','none');
                    $(arrowMainMenu).removeClass('turn-arrow-main-menu-m');
                }
            });
                
        }
    });


    /*==================================================================
    [ Show / hide modal search ]*/
    $('.js-show-modal-search').on('click', function(){
        $('.modal-search-header').addClass('show-modal-search');
        $('.js-show-modal-search').css('opacity','1');
    });

    $('.js-hide-modal-search').on('click', function(){
        $('.modal-search-header').removeClass('show-modal-search');
        $('.js-show-modal-search').css('opacity','1');
    });

    $('.container-search-header').on('click', function(e){
        e.stopPropagation();
    });


    /*==================================================================
    [ Isotope ]*/
    var $topeContainer = $('.isotope-grid');
    var $filter = $('.filter-tope-group');

    // filter items on button click
    $filter.each(function () {
        $filter.on('click', 'button', function () {
            var filterValue = $(this).attr('data-filter');
            $topeContainer.isotope({filter: filterValue});
        });
        
    });

    // init Isotope
    $(window).on('load', function () {
        var $grid = $topeContainer.each(function () {
            $(this).isotope({
                itemSelector: '.isotope-item',
                layoutMode: 'fitRows',
                percentPosition: true,
                animationEngine : 'best-available',
                masonry: {
                    columnWidth: '.isotope-item'
                }
            });
        });
    });

    var isotopeButton = $('.filter-tope-group button');

    $(isotopeButton).each(function(){
        $(this).on('click', function(){
            for(var i=0; i<isotopeButton.length; i++) {
                $(isotopeButton[i]).removeClass('how-active1');
            }

            $(this).addClass('how-active1');
        });
    });

    /*==================================================================
    [ Filter / Search product ]*/
    $('.js-show-filter').on('click',function(){
        $(this).toggleClass('show-filter');
        $('.panel-filter').slideToggle(400);

        if($('.js-show-search').hasClass('show-search')) {
            $('.js-show-search').removeClass('show-search');
            $('.panel-search').slideUp(400);
        }    
    });

    $('.js-show-search').on('click',function(){
        $(this).toggleClass('show-search');
        $('.panel-search').slideToggle(400);

        if($('.js-show-filter').hasClass('show-filter')) {
            $('.js-show-filter').removeClass('show-filter');
            $('.panel-filter').slideUp(400);
        }    
    });




    /*==================================================================
    [ Cart ]*/
    $('.js-show-cart').on('click',function(){
        $('.js-panel-cart').addClass('show-header-cart');
    });

    $('.js-hide-cart').on('click',function(){
        $('.js-panel-cart').removeClass('show-header-cart');
    });

    /*==================================================================
    [ Cart ]*/
    $('.js-show-sidebar').on('click',function(){
        $('.js-sidebar').addClass('show-sidebar');
    });

    $('.js-hide-sidebar').on('click',function(){
        $('.js-sidebar').removeClass('show-sidebar');
    });

    /*==================================================================
    [ +/- num product ]*/
    $('.btn-num-product-down').on('click', function(){
        var numProduct = Number($(this).next().val());
        if(numProduct > 0) $(this).next().val(numProduct - 1);
    });

    $('.btn-num-product-up').on('click', function(){
        var numProduct = Number($(this).prev().val());
        $(this).prev().val(numProduct + 1);
    });

    /*==================================================================
    [ Rating ]*/
    function initQuantityButtons() {
        // decrease quantity
        $(document).on('click', '.btn-num-product-down', function () {
            var input = $(this).closest(".wrap-num-product").find("input.num-product");
            var numProduct = Number(input.val());
            if (numProduct > 0) input.val(numProduct - 1);
        });

        // increase quantity
        $(document).on('click', '.btn-num-product-up', function () {
            var input = $(this).closest(".wrap-num-product").find("input.num-product");
            var numProduct = Number(input.val());
            input.val(numProduct + 1);
        });
    }

    $('.wrap-rating').each(function(){
        var item = $(this).find('.item-rating');
        var rated = -1;
        var input = $(this).find('input');
        $(input).val(0);

        $(item).on('mouseenter', function(){
            var index = item.index(this);
            var i = 0;
            for(i=0; i<=index; i++) {
                $(item[i]).removeClass('zmdi-star-outline');
                $(item[i]).addClass('zmdi-star');
            }

            for(var j=i; j<item.length; j++) {
                $(item[j]).addClass('zmdi-star-outline');
                $(item[j]).removeClass('zmdi-star');
            }
        });

        $(item).on('click', function(){
            var index = item.index(this);
            rated = index;
            $(input).val(index+1);
        });

        $(this).on('mouseleave', function(){
            var i = 0;
            for(i=0; i<=rated; i++) {
                $(item[i]).removeClass('zmdi-star-outline');
                $(item[i]).addClass('zmdi-star');
            }

            for(var j=i; j<item.length; j++) {
                $(item[j]).addClass('zmdi-star-outline');
                $(item[j]).removeClass('zmdi-star');
            }
        });
    });
    
    /*==================================================================
    [ Show modal1 ]*/
    var totalPagesGlobal = 0;

	$(document).ready(function () {
		let currentPage = 1;

		// Reusable function: load products (with search & pagination)
		function loadProducts(page = 1, search = '') {
			$.ajax({
				url: 'get-products.php',
				method: 'GET',
				data: {
					page: page,
					search: search
				},
				success: function (productsHtml) {
					if (page === 1) {
						// First load (or after search): reset product list
						$('#product-list').html(productsHtml);
					} else {
						// Load more: append
						$('#product-list').append(productsHtml);
					}

					$('#load-more').data('page', page + 1);

					// refresh pagination info
					loadPagination(search, page);
				},
				error: function (err) {
					console.log(err);
					alert('Failed to load products.');
				}
			});
		}

		function loadPagination(search = '', page = 1) {
			$.ajax({
				url: 'get-pagination.php',
				method: 'GET',
				data: {
					search: search
				},
				success: function (totalPages) {
					totalPagesGlobal = totalPages;

					// hide/show Load More depending on remaining pages
					if (page >= totalPagesGlobal || totalPagesGlobal <= 1) {
						$('#load-more').hide();
					} else {
						$('#load-more').show();
					}

					// reload isotope layout (if using isotope grid)
					$('.isotope-grid').isotope('reloadItems').isotope();
				}
			});
		}

		// Initial load (no search)
		loadProducts();

		// Load more products
		$(document).on('click', '#load-more', function (e) {
			e.preventDefault();
			let page = $(this).data('page');
			let search = $('#search').val(); // get search text
			loadProducts(page, search);
		});

		// Trigger search
		$('#search').on('keyup', function () {
			let query = $(this).val();
			currentPage = 1; // reset page
			loadProducts(currentPage, query); // reload products with search
		});
	});
    
    $(document).on('click', '.js-show-modal1', function(e) {
        e.preventDefault();

        let productId = $(this).data('id');
        $.ajax({
            url: 'get-product-details.php',
            method: 'GET',
            data: { id: productId },
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
    
    $(document).on("click", ".js-addcart-detail", function() {
        let productId = $(this).data("id");
        let productName = $(this).data("name");
        let size = $("select[name='size']").val();
        let color = $("select[name='color']").val();
        let quantity = $(".num-product").val();

        $("#size-error").text("");
        $("#color-error").text("");
        $("#quantity-error").text("");
        let hasError = false;

        // --- Validation ---
        if (!size || size === "Choose an option") {
            $("#size-error").text("Please select a size.");
            hasError = true;
        }

        if (!color || color === "Choose an option") {
            $("#color-error").text("Please select a color.");
            hasError = true;
        }

        if (!quantity || quantity <= 0) {
            $("#quantity-error").text("Quantity must be at least 1.");
            // $(".num-product").val(1); // reset
            hasError = true;
        }

        // Stop if errors found
        if (hasError) return;

        $.ajax({
            url: "add-to-cart.php",
            method: "POST",
            data: {
                id: productId,
                name: productName,
                size: size,
                color: color,
                quantity: quantity
            },
            dataType: "json",
            success: function(response) {
                // alert("Product added to cart!");
                swal(response.status, "is added to cart !", "success");
                $(".icon-header-item").attr("data-notify", response.cart_count);
            },
        });
    });
    $(document).ready(function () {
    // when cart icon clicked
        $(document).on("click", ".js-show-cart", function () {
            $.ajax({
                url: "get-cart.php", // this will return the HTML of cart items
                method: "GET",
                success: function (response) {
                    // insert cart HTML inside cart panel
                    $(".wrap-header-cart.js-panel-cart").html(response);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching cart:", error);
                }
            });
        });
        $(document).on("click", ".js-hide-cart", function () {
            $(".js-panel-cart").removeClass("show-header-cart");
        });
    });

    $(document).on("click", "#update-cart", function () {
        let cartData = [];

        $(".num-product").each(function () {
            let id = $(this).data("id");
            let quantity = $(this).val();
            cartData.push({id: id, quantity: quantity});
        });

        $.ajax({
            url: "update_cart.php",
            type: "POST",
            data: {cart: JSON.stringify(cartData)},
            success: function (response) {
                let res = JSON.parse(response);
                if (res.status === "success") {
                    if (res.cart_count === 0) {
                        $(".row").hide();                 // hide cart block
                        $("#empty-cart-message2").show();
                        $(".icon-header-item").attr("data-notify", res.cart_count);  // show empty cart msg
                    } else {
                        alert("Cart updated successfully!");
                        $(".get-total-container").html(res.html);
                        $(".wrap-table-shopping-cart").html(res.products_html);
                        $(".icon-header-item").attr("data-notify", res.cart_count);
                        initQuantityButtons(); // reinitialize quantity buttons
                    }
                }
            }
        });
    });

    $('#apply_coupon').click(function () {
    var coupon = $('#coupon_code').val();

        $.ajax({
            url: 'apply_coupon.php',
            type: 'POST',
            data: { coupon_code: coupon },
            dataType: 'json', // tell jQuery we expect JSON response
            success: function (res) {
                console.log(res);
                if (res.status === 'success') {
                    $('.get-total-container').html(res.html);
                    $('#coupon_message').css("color", "green").text("Coupon applied! " + res.discount + "% off");
                    // Update total in UI
                    // $('#cart_total_after_coupon').css("color", "green").html("New amount: $" + res.new_total.toLocaleString('en-US', {}));

                } else {
                    $('.get-total-container').html(res.html);
                    $('#coupon_message')
                        .css("color", "red")
                        .text(res.message);
                    $('#cart_total_after_coupon')
                        .css("color", "red")
                        .text(res.discount_message);
                }
            },
            error: function (error) {
                console.log("AJAX Error:", error);
                $('#coupon_message').css("color", "red").text("Something went wrong. Please try again.");
            }
        });
    });

    $(document).on("click", "#checkoutBtn", function(e){
        e.preventDefault();

        let data = {
            name: $("#name").val().trim(),
            email: $("#email").val().trim(),
            phone: $("#phone").val().trim(),
            pincode: $("#pincode").val().trim(),
            city: $("#city").val().trim(),
            address: $("#address").val().trim()
        };

        // Clear old error messages
        $("#name-error, #email-error, #phone-error, #pincode-error, #city-error, #address-error").text("");

        let hasError = false;

        if (!data.name) {
            $("#name-error").text("Please enter your name.");
            hasError = true;
        }
        if (!data.email) {
            $("#email-error").text("Please enter your email.");
            hasError = true;
        }
        if (!data.phone) {
            $("#phone-error").text("Please enter your phone number.");
            hasError = true;
        }
        if (!data.pincode) {
            $("#pincode-error").text("Please enter your pincode.");
            hasError = true;
        }
        if (!data.city) {
            $("#city-error").text("Please enter your city.");
            hasError = true;
        }
        if (!data.address) {
            $("#address-error").text("Please enter your address.");
            hasError = true;
        }

        if (hasError) return;

        // Send via AJAX
        $.ajax({
            url: "save_billing.php",
            type: "POST",
            data: data,
            success: function(response){
                let res = JSON.parse(response);
                if(res.status === "success"){
                    alert(res.message);
                    $("input").val(""); // clear fields
                    window.location.href = "thankyou.php";
                }
            },
            error: function(){
                alert("Something went wrong!");
            }
        });
    });


})(jQuery);