<h3>Add Products</h3>
<form method="post" id = 'addProductForm'>
    <div class="mb-3">
        <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
    </div>
    <div class="mb-3">
        <input type="text" name="image" id="image" class="form-control" placeholder="Image Path">
    </div>
    <div class="mb-3">
        <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Price" required>
    </div>
    <div class="mb-3">
        <input type="text" name="label" id="label" class="form-control" placeholder="Label">
    </div>
    <div class="mb-3">
        <input type="text" name="size" id="size" class="form-control" placeholder="Size">
    </div>
    <div class="mb-3">
        <input type="text" name="color" id="color" class="form-control" placeholder="Color">
    </div>
    <div class="mb-3">
        <input type="text" name="more_images" id="more_images" class="form-control" placeholder="More Images">
    </div>
    <div class="mb-3">
        <input type="text" id="product_description" name="product_description" class="form-control" placeholder="Description">
    </div>
    <div class="mb-3 d-flex justify-content-center">
        <button type="submit" class="btn btn-primary save-products">Save Product</button>
    </div>
</form>