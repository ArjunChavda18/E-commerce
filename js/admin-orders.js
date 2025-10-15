$(document).ready(function () {
    $(document).on("click", ".view-order", function() {
        var orderId = $(this).data("id");

        $("#orderDetails").html("<p class='text-center'>Loading...</p>");
        $("#orderModal").modal("show");

        $.ajax({
            url: "order-details.php",
            type: "POST",
            data: { order_id: orderId },
            success: function(response) {
                $("#orderDetails").html(response);
            },
            error: function() {
                $("#orderDetails").html("<p class='text-danger'>Error loading order details</p>");
            }
        });
    });
});