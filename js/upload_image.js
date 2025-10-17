$(document).ready(function () {
  // Open modal
  $(document).on("click", ".uploadBtn", function () {
    let id = $(this).data("id");
    $("#product_id").val(id);
    $("#uploadModal").modal("show");
  });

  // Show preview
  $("#images").on("change", function () {
    $("#preview").html("");
    Array.from(this.files).forEach((file) => {
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#preview").append(
          `<img src="${e.target.result}" width="70" class="border rounded me-1 mb-1">`
        );
      };
      reader.readAsDataURL(file);
    });
  });

  // Upload images to server
  $("#uploadForm").on("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
      url: "upload_images.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        alert(res);
        $("#uploadModal").modal("hide");
      },
      error: function () {
        alert("Error uploading images");
      },
    });
  });
});
