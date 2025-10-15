$(document).ready(function () {
    // When "Add" button clicked
    $("#openAddProduct").on("click", function () {
        // Load the form via AJAX
        $.ajax({
            url: "add-product-form.php", // PHP file that contains the form
            type: "GET",
            success: function (response) {
                $("#addProductFormContainer").html(response);
                $("#addProductModal").modal("show"); // Show modal
            },
            error: function () {
                alert("Failed to load form. Try again.");
            }
        });
    });

    $(document).on("click", ".save-products", function () {

        $.ajax({
            url: "product-actions.php",
            type: "POST",
            data: { action: "add",
                name: $("#name").val(),
                image: $("#image").val(),
                price: $("#price").val(),
                label: $("#label").val(),
                size: $("#size").val(),
                color: $("#color").val(),
                more_image: $("#more_images").val(),
                product_description: $("#product_description").val()
            },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    alert(res.message);
                    $("#addProductModal").modal("hide");
                    window.location.reload();
                } else {
                    alert(res.message);
                }
            },
            error: function () {
                alert("❌ Failed to save product");
            }
        });
    });
    
    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");
        let modal = $("#editModal" + id);

        $.ajax({
            url: "product-actions.php",
            type: "GET",
            data: { id: id , action: "edit" },
            success: function (response) {
                modal.find(".editModalContent").html(response);
                modal.modal("show");
            }
        });
    });

    // Submit edit form
    $(document).on("click", ".save", function (e) {
        e.preventDefault();

        let form = $(this);
        let id = form.data("id");
        // console.log(id);

        $.ajax({
            url: "product-actions.php",
            type: "POST",
            data: {id: id , action: "save",
                name: $("#name").val(),
                image: $("#image").val(),
                price: $("#price").val(),
                label: $("#label").val(),
                size: $("#size").val(),
                color: $("#color").val(),
                more_image: $("#more_images").val(),
                product_description: $("#product_description").val(),
                id: id
            }, // append action
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert("✅ Product updated successfully");
                    window.location.reload();
                    // Close modal
                    $("#editModal" + id).modal("hide");
                } else {
                    alert("❌ " + response.message);
                }
            }
        });
    });

    $(document).on("click", ".delete-product", function (e) {
        e.preventDefault();

        let id = $(this).data("id");
        $.ajax({
            url: "product-actions.php",
            type: "POST",
            data: { action: "delete", id: id },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    alert(res.message);
                    window.location.reload();
                } else {
                    alert(res.message);
                }
            },
            error: function () {
                alert("❌ Failed to delete product");
            }
        });
    });
});
