<?php
session_start();
require 'db-connection.php'; // your DB connection file

?>
<div class="header-cart flex-col-l p-l-65 p-r-25">
    <div class="header-cart-title flex-w flex-sb-m p-b-8">
        <span class="mtext-103 cl2">Your Cart</span>
        <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
            <i class="zmdi zmdi-close"></i>
        </div>
    </div>

    <div class="header-cart-content flex-w js-pscroll">
        <ul class="header-cart-wrapitem w-full">
            <?php
            $total = 0;
            if (isset($_SESSION['cart'])) {
                $cart = json_decode($_SESSION['cart'], true);

                foreach ($cart as $item) {
                    $id = $item['id'];
                    $stmt = $pdo->prepare("SELECT name, price, image FROM products WHERE id = ?");
                    $stmt->execute([$id]);
                    $product = $stmt->fetch();

                    if ($product) {
                        $subtotal = $item['quantity'] * $product['price'];
                        $total += $subtotal;
                        ?>
                        <li class="header-cart-item flex-w flex-t m-b-12">
                            <div class="header-cart-item-img">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="header-cart-item-txt p-t-8">
                                <a href="product-detail.php?id=<?php echo $id; ?>" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                                <span class="header-cart-item-info">
                                    <?php echo $item['quantity']; ?> x $<?php echo $product['price']; ?>
                                </span>
                            </div>
                        </li>
                        <?php
                    }
                }
            } else {
                echo "<li class='header-cart-item'>Your cart is empty</li>";
            }
            ?>
        </ul>

        <div class="w-full">
            <div class="header-cart-total w-full p-tb-40">
                Total: $<?php echo $total; ?>
            </div>

            <div class="header-cart-buttons flex-w w-full">
                <a href="shoping-cart.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                    View Cart
                </a>
                <a href="checkout.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                    Check Out
                </a>
            </div>
        </div>
    </div>
</div>