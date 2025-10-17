<?php
include_once __DIR__ . '/../models/product-model.php';


class Product{
    public $productModel;

    public function __construct($id) {
        $this->productModel = new ProductModal($id);
    }

    public function getProducts($search) {
        $this->productModel->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        return $products = $this->productModel->getProducts($search, $this->productModel->page);
    }

    public function renderProductCard($product) {
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

    public function getProductListing($products) {
        $html = '';
        foreach ($products as $product) {
            $html .= $this->renderProductCard($product);
        }
        return $html;
    }

    public function getProduct_Details(){
        return $this->productModel->get();
    }

    public function getProductDetails($product){
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
                        ₹<?= number_format($product['price'], 2) ?>
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
                                <button id="btn3d" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js" style="margin-top: 10px" data-id="<?= $product['id'] ?>">
                                    3D View
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

        <!-- Popup container -->
        <div id="popup" style="
        display:none;
        position:fixed;
        top:50%;
        left:50%;
        transform:translate(-50%, -50%);
        background:white;
        border-radius:10px;
        box-shadow:0 0 10px rgba(0,0,0,0.3);
        z-index:999;
        text-align:center;
        width: 50rem;
        height: 28rem;
        ">
        <span id="close-popup" style="
            position:absolute;
            top:10px;
            right:15px;
            font-size:22px;
            font-weight:bold;
            color:#555;
            cursor:pointer;
        ">&times;</span>
            <div id="popup-content"></div>
            <!-- <button id="close-popup">Close</button> -->
        </div>

    <?php
    return ob_get_clean(); // return buffer content
    }

    public function getProductDetailsHTML($product){
        if ($product) {
            echo $this->getProductDetails($product); // assuming this function generates the HTML
        }
    }

    public function pagination($search) {
        return $this->productModel->count($search);
    }
}
    
?>