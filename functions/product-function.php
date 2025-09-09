<?php
function getProducts() {
    global $pdo; // Use the global PDO instance
    $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit  = 4;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    if ($search !== '') {
        $sql = "SELECT id, name, image, price, label, size, color, more_images
                FROM products 
                WHERE name LIKE :search 
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    } else {
        $sql = "SELECT id, name, image, price, label, size, color, more_images
                FROM products 
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $products;
}

function getProductListing($products) {
    $html = '';
    foreach ($products as $product) {
        $html .= renderProductCard($product);
    }
    return $html;
}

function getProduct_Details(){
    global $pdo; // Use the global PDO instance
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    return $product;
}

function getProductDetailsHTML($product){
    if ($product) {
         echo getProductDetails($product); // assuming this function generates the HTML
    }
}
?>