<?php

include __DIR__ . '/../includes/db-connection.php';
include __DIR__ . "/../models/product-model.php";
include __DIR__ . "/../functions/product-function.php";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action']) && $_POST['action'] === "add") {
    // print_r($_POST);
    $data = [
        'name' => $_POST ['name'] ?? '',
        'image' => $_POST ['image'] ?? '',
        'price' => $_POST ['price'] ?? '',
        'label' => $_POST ['label'] ?? '',
        'size' => $_POST ['size'] ?? '',
        'color' => $_POST ['color'] ?? '',
        'more_images' => $_POST ['more_image'] ?? '',
        'product_description' => $_POST ['product_description'] ?? '',
    ];

    $product = new ProductModal("products");
    $addProduct = $product->addProduct($data);

    if ($addProduct) {
        echo json_encode(["status" => "success", "message" => "Product added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add product"]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] === "edit") {
    $id = (int)$_GET['id'];
    $product = new Product($id);
    $details = $product->getProduct_Details();

    if ($details) {
        // print_r($details['id']);
        ?>
        <form id="editProductForm<?= $details['id']; ?>" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($details['id']) ?>">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($details['name']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Image Path</label>
                    <input type="text" name="image" id="image" value="<?= htmlspecialchars($details['image'] ?? '') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" id="price" value="<?= $details['price'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Label</label>
                    <input type="text" name="label" id="label" value="<?= htmlspecialchars($details['label'] ?? '') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Size</label>
                    <input type="text" name="size" id="size" value="<?= htmlspecialchars($details['size'] ?? '') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Color</label>
                    <input type="text" name="color" id="color" value="<?= htmlspecialchars($details['color'] ?? '') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label>More Images</label>
                    <input type="text" name="more_images" id="more_images" value="<?= htmlspecialchars($details['more_images'] ?? '') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <input type="text" id="product_description" name="product_description" value="<?= htmlspecialchars($details['product_description'] ?? '') ?>" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="save"  class="btn btn-primary save" data-id="<?= htmlspecialchars($details['id']) ?>">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
        <?php
    } else {
        echo "<p>Product not found.</p>";
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action']) && $_POST['action'] === "save") {
    $id = (int)($_POST['id'] ?? '');
    // print_r($id);
    $data = [
        'name' => $_POST ['name'] ?? '',
        'image' => $_POST ['image'] ?? '',
        'price' => $_POST ['price'] ?? '',
        'label' => $_POST ['label'] ?? '',
        'size' => $_POST ['size'] ?? '',
        'color' => $_POST ['color'] ?? '',
        'more_images' => $_POST ['more_image'] ?? '',
        'product_description' => $_POST ['product_description'] ?? '',
        'id' => $id
    ];

    $product = new ProductModal($id);
    $updated = $product->updateProduct($data);
    // print_r($updated);

    if ($updated) {
        echo json_encode(["status" => "success", "message" => "Product updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update product"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action']) && $_POST['action'] === "delete") {
    $id = (int)($_POST['id'] ?? 0);

    if ($id) {
        $product = new ProductModal("products");
        $deleted = $product->deleteProduct($id);

        if ($deleted) {
            echo json_encode(["status" => "success", "message" => "Product deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete product"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid product ID"]);
    }
    exit;
}
