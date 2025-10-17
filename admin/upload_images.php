<?php
include '../includes/db-connection.php';

$product_id = $_POST['product_id'];

if (!isset($_FILES['images'])) {
  die("No images uploaded!");
}

$uploadDir = "../uploads/products/$product_id/";
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

$files = $_FILES['images'];

for ($i = 0; $i < count($files['name']); $i++) {
  $fileName = time() . '_' . basename($files['name'][$i]);
  $targetPath = $uploadDir . $fileName;

  if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
    // Store in database
    $stmt = $pdo->prepare("INSERT INTO 3d_view (product_id, image_name) VALUES (?, ?)");
    $stmt->execute([$product_id, $fileName]);
  }
}

echo "Images uploaded successfully!";
?>
