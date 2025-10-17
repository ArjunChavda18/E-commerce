<?php
include "includes/db-connection.php";
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    echo "Invalid product ID!";
    exit;
}

// Prepare the SQL query
$sql = "SELECT image_name FROM 3d_view WHERE product_id = :product_id ORDER BY id ASC";
$stmt = $pdo->prepare($sql);

// Execute with product_id bound
$stmt->execute(['product_id' => $product_id]);

// Fetch all images
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 style="margin-top:20px;" align="center">3D View</h1>

<div class="menu">
    <ul class="list">
        <?php foreach($images as $img): ?>
            <li><img style="width: 25%" src="uploads/products/<?php echo $product_id . '/' . htmlspecialchars($img['image_name']); ?>" /></li>
        <?php endforeach; ?>
    </ul>
</div>