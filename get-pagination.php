<?php
require 'db-connection.php';

$limit = 4; // products per page
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    // Count only searched products
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE :search");
    $stmt->execute([':search' => "%$search%"]);
    $total = $stmt->fetchColumn();
} else {
    // Count all products
    $total = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
}

$totalPages = ceil($total / $limit);

// Return total pages
echo $totalPages;
?>
