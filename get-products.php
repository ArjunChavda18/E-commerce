<?php
require 'db-connection.php'; // DB connection

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch paginated products
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


// Assume $products is an array fetched from your database
for ($i = 0; $i < count($products); $i++) {
    $product = $products[$i];
    $label = $product['label'];
?>
    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item <?php echo str_replace(',', ' ', $label) ?>">
        <div class="block2">
            <div class="block2-pic hov-img0">
                <img src="<?php echo $product['image'] ?>" alt="<?php echo $product['name'] ?>">
                <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1" data-id="<?php echo isset($product['id']) ? $product['id'] : ''; ?>">
                    Quick View
                </a>
            </div>
            <div class="block2-txt flex-w flex-t p-t-14">
                <div class="block2-txt-child1 flex-col-l ">
                    <a href="product-detail.php?id=<?= $product['id'] ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                        <?php echo $product['name'] ?>
                    </a>
                    <span class="stext-105 cl3">
                        <?php echo number_format($product['price'], 2) ?>
                    </span>
                </div>
                <div class="block2-txt-child2 flex-r p-t-3">
                    <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                        <img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
                        <img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $('.js-show-modal1').on('click', function(e) {
            e.preventDefault();
            $('.js-modal1').addClass('show-modal1');
        });

        $('.js-hide-modal1').on('click', function() {
            $('.js-modal1').removeClass('show-modal1');
        });
    </script>
<?php
}
?>