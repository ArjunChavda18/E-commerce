<?php
function getCartTotalBlock(){
    global $pdo;
    $coupon_code = $_SESSION['coupon_code'] ?? null;

    // Calculate original cart total (always available)
    
    $total = 0;
    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);
        foreach ($cart as $item) {
            $id = $item['id'];
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            if ($product) {
                $total += $product['price'] * $item['quantity'];
                $_SESSION['old_total'] = $total;
            }
        }
    }
    
    if($coupon_code){
        $stmt = $pdo->prepare("SELECT discount FROM coupons WHERE coupon_code = ?");
        $stmt->execute([$coupon_code]);
        $coupon = $stmt->fetch();

        $discount = $coupon['discount'];
        $discountAmount = ($total * $discount) / 100;
        $newTotal = $total - $discountAmount;

        // Store in session
        $_SESSION['discount'] = $discount;
        $_SESSION['discount_amount'] = $discountAmount;
        $_SESSION['discount_total'] = $newTotal;
    }

    // ✅ Build HTML block
    $total_html = '
        <div class="flex-w flex-t bor12 p-b-13">
            <div class="size-208">
                <span class="mtext-101 cl2">Total:</span>
            </div>
            <div class="size-209 p-t-1">
                <span class="mtext-110 cl2">$' . number_format($total, 2) . '</span>
            </div>
        </div>';

    if(isset($_SESSION['coupon_code']) && $_SESSION['coupon_code']) {
        $total_html .= '<div class="flex-w flex-t bor12 p-b-13">
            <div class="size-208">
                <span class="stext-110 cl2">Discount:</span>
            </div>
            <div class="size-209">
                <span class="mtext-110 cl2">' . number_format($discount, 2) . '%</span>
            </div>
        </div>

        <div class="flex-w flex-t bor12 p-b-13">
            <div class="size-208">
                <span class="stext-110 cl2">Discount Amount:</span>
            </div>
            <div class="size-209">
                <span class="mtext-110 cl2">-$' . number_format($discountAmount, 2) . '</span>
            </div>
        </div>

        <div class="flex-w flex-t bor12 p-b-13">
            <div class="size-208">
                <span class="stext-110 cl2">Final Total:</span>
            </div>
            <div class="size-209">
                <span class="mtext-110 cl2">$' . number_format($newTotal, 2) . '</span>
            </div>
        </div>
        ';
    }

    return $total_html;
}

function unsetDiscountSession(){
    unset($_SESSION['discount']);
    unset($_SESSION['discount_amount']);
    unset($_SESSION['discount_total']);
    unset($_SESSION['coupon_code']);
    // print_r($_SESSION['coupon_code']);
}

function productDetailBlock(){
    global $pdo;
    $total = 0;

    if (isset($_SESSION['cart'])) {
        $cart = json_decode($_SESSION['cart'], true);

        // start table
        $html = '<table class="table-shopping-cart">
        <tr class="table_head">
            <th class="column-1">Product</th>
            <th class="column-2"></th>
            <th class="column-3">Price</th>
            <th class="column-4">Quantity</th>
            <th class="column-5">Total</th>
        </tr>';

        foreach ($cart as $item) {
            $id = $item['id'];
            $quantity = $item['quantity'];

            // fetch product details
            $stmt = $pdo->prepare("SELECT name, price, image FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            if ($product) {
                $subtotal = $quantity * $product['price'];
                $total += $subtotal;

                $html .= '<tr class="table_row">
                    <td class="column-1">
                        <div class="how-itemcart1">
                            <img src="'. htmlspecialchars($product['image']) .'" 
                            alt="'. htmlspecialchars($product['name']) .'">
                        </div>
                    </td>
                    <td class="column-2">'. htmlspecialchars($product['name']) .'</td>
                    <td class="column-3">$ '. number_format($product['price'], 2) .'</td>
                    <td class="column-4">
                        <div class="wrap-num-product flex-w m-l-auto m-r-0">
                            <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                <i class="fs-16 zmdi zmdi-minus"></i>
                            </div>

                            <input class="mtext-104 cl3 txt-center num-product"
                            type="number"
                            data-id="'. $id .'"
                            value="'. $quantity .'">

                            <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                <i class="fs-16 zmdi zmdi-plus"></i>
                            </div>
                        </div>
                    </td>
                    <td class="column-5">$ '. number_format($subtotal, 2) .'</td>
                </tr>';
            }
        }

        $html .= '</table>';
    }

    return $html;
}
function renderProductCard($product) {
    ob_start();
    ?>
    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item <?php echo str_replace(',', ' ', $product['label']) ?>">
        <div class="block2">
            <div class="block2-pic hov-img0">
                <img src="<?php echo $product['image'] ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
                   data-id="<?php echo $product['id']; ?>">
                    Quick View
                </a>
            </div>
            <div class="block2-txt flex-w flex-t p-t-14">
                <div class="block2-txt-child1 flex-col-l ">
                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </a>
                    <span class="stext-105 cl3">
                        <?php echo number_format($product['price'], 2); ?>
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
    <?php
    return ob_get_clean();
}

function getProductDetails($product){
    $sizes  = explode(",", $product['size']);
    $colors = explode(",", $product['color']);
    $images = explode(",", $product['more_images']);

    ob_start(); // start buffer
    ?>
    <div class="row">
        <div class="col-md-6 col-lg-7 p-b-30">
            <div class="p-l-25 p-r-30 p-lr-0-lg">
                <div class="wrap-slick3 flex-sb flex-w">

                    <!-- Thumbnails -->
                    <div class="wrap-slick3-dots"></div>

                    <!-- Arrows -->
                    <div class="wrap-slick3-arrows flex-sb-m flex-w">
                        
                    </div>

                    <!-- Main Slider -->
                    <div class="slick3 gallery-lb">
                        <?php foreach ($images as $img): ?>
                            <div class="item-slick3" data-thumb="<?= htmlspecialchars($img) ?>">
                                <div class="wrap-pic-w pos-relative">
                                    <img src="<?= htmlspecialchars($img) ?>" alt="IMG-PRODUCT">

                                    <div class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04">
                                        <a href="<?= htmlspecialchars($img) ?>">
                                            <i class="fa fa-expand"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-6 col-lg-5 p-b-30">
            <div class="p-r-50 p-t-5 p-lr-0-lg">

                <!-- Product name -->
                <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                    <?= htmlspecialchars($product['name']) ?>
                </h4>

                <!-- Product price -->
                <span class="mtext-106 cl2">
                    $<?= number_format($product['price'], 2) ?>
                </span>

                <!-- Product description -->
                <p class="stext-102 cl3 p-t-23">
                    <?= htmlspecialchars($product['product_description']) ?>
                </p>

                <!-- Size dropdown -->
                <div class="p-t-33">
                    <div class="flex-w flex-r-m p-b-10">
                        <div class="size-203 flex-c-m respon6"> Size </div>
                        <div class="size-204 respon6-next">
                            <div class="rs1-select2 bor8 bg0">
                                <select class="js-select2" name="size">
                                    <option>Choose an option</option>
                                    <?php foreach ($sizes as $s): ?>
                                        <option><?= htmlspecialchars($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                            <small id="size-error" class="text-danger"></small>
                        </div>
                    </div>

                    <!-- Color dropdown -->
                    <div class="flex-w flex-r-m p-b-10">
                        <div class="size-203 flex-c-m respon6"> Color </div>
                        <div class="size-204 respon6-next">
                            <div class="rs1-select2 bor8 bg0">
                                <select class="js-select2" name="color">
                                    <option>Choose an option</option>
                                    <?php foreach ($colors as $c): ?>
                                        <option><?= htmlspecialchars($c) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                            <small id="color-error" class="text-danger"></small>
                        </div>
                    </div>

                    <!-- Quantity and Add to cart -->
                    <div class="flex-w flex-r-m p-b-10">
                        <div class="size-204 flex-w flex-m respon6-next">
                            <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m"> <i class="fs-16 zmdi zmdi-minus"></i> </div> <input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" value="1">
                                <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m"> <i class="fs-16 zmdi zmdi-plus"></i> </div>
                            </div>
                            <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail" data-id="<?= $product['id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>">
                                Add to cart
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Wishlist and social links -->
                <div class="flex-w flex-m p-l-100 p-t-40 respon7">
                    <div class="flex-m bor9 p-r-10 m-r-11">
                        <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Add to Wishlist">
                            <i class="zmdi zmdi-favorite"></i>
                        </a>
                    </div>
                    <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Twitter">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Google Plus">
                        <i class="fa fa-google-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean(); // return buffer content
}
?>