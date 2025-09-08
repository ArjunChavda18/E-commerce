<?php
require 'db-connection.php';
require 'functions.php'; // ✅ include reusable function

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

// ✅ Use reusable function
foreach ($products as $product) {
    echo renderProductCard($product);
}
?>
