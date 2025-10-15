<?php
include __DIR__ . '/../includes/db-connection.php';
require '../functions/product-function.php'; // functions specific to products

class ProductController {
    public function getProducts() {
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit  = 4;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $id     = $_GET['id'] ?? 0;

        $productObj = new Product($id);
        $products = $productObj->getProducts($search, $page, $limit);
        // print_r($products);
        $productListing = $productObj->getProductListing($products);
        $pagination = $productObj->pagination($search);

        return ['html' => $productListing, 'totalPages' => $pagination];
    }

    public function getProductDetails() {
        $id = $_GET['id'] ?? 0;
        $productObj = new Product($id);
        $product = $productObj->getProduct_Details();
        return $productObj->getProductDetailsHTML($product);
    }
}
