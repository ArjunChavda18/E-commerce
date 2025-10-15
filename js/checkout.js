class Checkout {
    validateCheckout(){

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

        return hasError;
    }

    submitOrder(){
        let data = {
            name: $("#name").val().trim(),
            email: $("#email").val().trim(),
            phone: $("#phone").val().trim(),
            pincode: $("#pincode").val().trim(),
            city: $("#city").val().trim(),
            address: $("#address").val().trim()
        };
        // Send via AJAX
        $.ajax({
            url: "routes/api.php",
            type: "POST",
            data: {data: data,
                action: "save-billing-details"
            },
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
    }
}