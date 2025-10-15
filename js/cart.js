$(document).ready(function () {
    const cart = new Cart();
    cart.initQuantityButtons(); // Initialize quantity buttons on page load
    cart.cartDetails(); // Initialize cart details on page load
    cart.applyCoupon(); // Initialize coupon application on page load
});

class Cart {
    cartDetails() {
        $(document).on("click", ".js-addcart-detail", function () {
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
            url: "routes/api.php",
            method: "POST",
            data: {
                id: productId,
                name: productName,
                size: size,
                color: color,
                quantity: quantity,
                action: "add-to-cart",
            },
            dataType: "json",
            success: function (response) {
                // alert("Product added to cart!");
                swal(response.status, "is added to cart !", "success");
                $(".icon-header-item").attr("data-notify", response.cart_count);
            },
            });
        });

        $(document).on("click", ".js-show-cart", function () {
            $.ajax({
            url: "routes/api.php", // this will return the HTML of cart items
            method: "GET",
            dataType: "html",
            data: {
                action: "get-cart",
            },
            success: function (response) {
                // insert cart HTML inside cart panel
                $(".wrap-header-cart.js-panel-cart").html(response);
            },
            error: function (xhr, status, err) {
                console.error("Error fetching cart:", err);
                console.log("Status:", status);
                console.log("Response:", xhr.responseText);
            }

            });
        });
        $(document).on("click", ".js-hide-cart", function () {
            $(".js-panel-cart").removeClass("show-header-cart");
        });   

        $(document).on("click", "#update-cart", function () {
        let cartData = [];

        $(".num-product").each(function () {
            let id = $(this).data("id");
            let quantity = $(this).val();
            cartData.push({ id: id, quantity: quantity });
        });

        $.ajax({
            url: "routes/api.php",
            type: "POST",
            data: { cart: JSON.stringify(cartData), action: "update-cart" },
            success: function (response) {
            let res = JSON.parse(response);
            if (res.status === "success") {
                if (res.cart_count === 0) {
                $(".row").hide(); // hide cart block
                $("#empty-cart-message2").show();
                $(".icon-header-item").attr("data-notify", res.cart_count); // show empty cart msg
                } else {
                alert("Cart updated successfully!");
                $(".get-total-container").html(res.html);
                $(".wrap-table-shopping-cart").html(res.products_html);
                $(".icon-header-item").attr("data-notify", res.cart_count);
                initQuantityButtons(); // reinitialize quantity buttons
                }
            }
            },
        });
        });
    }

    initQuantityButtons() {
    // decrease quantity
    $(document).off("click", ".btn-num-product-down");
    $(document).off("click", ".btn-num-product-up");
    $(document).on("click", ".btn-num-product-down", function () {
        var input = $(this).closest(".wrap-num-product").find("input.num-product");
        var numProduct = input.val();
        if (numProduct > 0) $(input).attr('value', numProduct - 1);
    });

    // increase quantity
    $(document).on("click", ".btn-num-product-up", function () {
        var input = $(this).closest(".wrap-num-product").find("input.num-product");
        var numProduct = Number(input.val());
        $(input).attr('value', numProduct + 1);
    });
    }

    applyCoupon() {
        $("#apply_coupon").click(function () {
        var coupon = $("#coupon_code").val();

        $.ajax({
            url: "routes/api.php",
            type: "POST",
            data: { coupon_code: coupon, action: "apply-coupon" },
            dataType: "json", // tell jQuery we expect JSON response
            success: function (res) {
            console.log(res);
            if (res.status === "success") {
                $(".get-total-container").html(res.html);
                $("#coupon_message")
                .css("color", "green")
                .text("Coupon applied! " + res.discount + "% off");
                // Update total in UI
                // $('#cart_total_after_coupon').css("color", "green").html("New amount: $" + res.new_total.toLocaleString('en-US', {}));
            } else {
                $(".get-total-container").html(res.html);
                $("#coupon_message").css("color", "red").text(res.message);
                $("#cart_total_after_coupon")
                .css("color", "red")
                .text(res.discount_message);
            }
            },
            error: function (error) {
            console.log("AJAX Error:", error);
            $("#coupon_message")
                .css("color", "red")
                .text("Something went wrong. Please try again.");
            },
        });
        });
    }
}