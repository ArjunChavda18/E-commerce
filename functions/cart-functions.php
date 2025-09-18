<?php
class Cart {

    public function calculateCartTotals() {
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
        // print_r($_SESSION);
        return [
            'total' => $total,
            'discount' => $discount ?? 0,
            'discountAmount' => $discountAmount ?? 0,
            'newTotal' => $newTotal ?? $total
        ];

    }

    public function addToCart($id, $name, $quantity, $price, $color, $size) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // initialize cart session as JSON string if not set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = json_encode([]); // start with empty JSON array
        }

        if (is_array($_SESSION['cart'])) {
            // convert array to JSON string
            $_SESSION['cart'] = json_encode($_SESSION['cart']);
        }

        // decode session cart JSON to PHP array
        $cart = json_decode($_SESSION['cart'], true);


        // add new product
        $cart[] = [
            "id" => $id,
            "quantity" => $quantity,
            "name" => $name,
            "price" => $price,
            "size" => $size,
            "color" => $color
        ];

        $cartCount = count($cart);
        // save back as JSON
        $_SESSION['cart'] = json_encode($cart);
        $_SESSION['cart_count'] = $cartCount;

        // optional: return JSON response
        echo json_encode([
            "status" => "$name is added to cart!",
            "cart"   => $cart,
            "cart_count" => $cartCount
        ]);
    }

    public function getCart() {
        global $pdo; // Use the global PDO instance
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $total = 0;
        $cartHTML = '<div class="header-cart flex-col-l p-l-65 p-r-25">
            <div class="header-cart-title flex-w flex-sb-m p-b-8">
                <span class="mtext-103 cl2">Your Cart</span>
                <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                    <i class="zmdi zmdi-close"></i>
                </div>
            </div>

            <div class="header-cart-content flex-w js-pscroll">
                <ul class="header-cart-wrapitem w-full">';

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

                        $cartHTML .= '
                        <li class="header-cart-item flex-w flex-t m-b-12">
                            <div class="header-cart-item-img">
                                <img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">
                            </div>
                            <div class="header-cart-item-txt p-t-8">
                                <a href="product-detail.php?id=' . $id . '" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                    ' . htmlspecialchars($product['name']) . '
                                </a>
                                <span class="header-cart-item-info">
                                    ' . $item['quantity'] . ' x ₹' . $product['price'] . '
                                </span>
                            </div>
                        </li>';
                    }
                }
            } else {
                $cartHTML .= "<li class='header-cart-item'>Your cart is empty</li>";
            }

        $cartHTML .= '</ul>
                <div class="w-full">
                    <div class="header-cart-total w-full p-tb-40">
                        Total: ₹' . $total . '
                    </div>

                    <div class="header-cart-buttons flex-w w-full">
                        <a href="shoping-cart.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                            View Cart
                        </a>';
                        if ($total > 0) {
                            // ✅ Show Checkout if cart has items
                            $cartHTML .= '
                                <a href="checkout.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                                    Check Out
                                </a>';
                        } else {
                            // ✅ Show Continue Shopping if cart empty
                            $cartHTML .= '
                                <a href="product.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                                    Continue Shopping
                                </a>';
                        }
                    $cartHTML .= '
                    </div>
                </div>
            </div>
        </div>';

        return $cartHTML;
    }
    
    public function getCartTotalBlock($total, $discount , $discountAmount , $newTotal) {

        // ✅ Build HTML block
        $total_html = '
            <div class="flex-w flex-t bor12 p-b-13">
                <div class="size-208">
                    <span class="mtext-101 cl2">Total:</span>
                </div>
                <div class="size-209 p-t-1">
                    <span class="mtext-110 cl2">₹' . number_format($total, 2) . '</span>
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
                    <span class="mtext-110 cl2">-₹' . number_format($discountAmount, 2) . '</span>
                </div>
            </div>

            <div class="flex-w flex-t bor12 p-b-13">
                <div class="size-208">
                    <span class="stext-110 cl2">Final Total:</span>
                </div>
                <div class="size-209">
                    <span class="mtext-110 cl2">₹' . number_format($newTotal, 2) . '</span>
                </div>
            </div>
            ';
        }

        return $total_html;
    }

    public function productDetailBlock(){
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
                        <td class="column-3">₹ '. number_format($product['price'], 2) .'</td>
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
                        <td class="column-5">₹ '. number_format($subtotal, 2) .'</td>
                    </tr>';
                }
            }

            $html .= '</table>';
        }

        return $html;
    }

    public function unsetDiscountSession(){
        unset($_SESSION['discount']);
        unset($_SESSION['discount_amount']);
        unset($_SESSION['discount_total']);
        unset($_SESSION['coupon_code']);
        // print_r($_SESSION['coupon_code']);
    }

    public function applycoupon($coupon_code) {
        global $pdo; 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $stmt = $pdo->prepare("SELECT discount FROM coupons WHERE coupon_code = ?");
        $stmt->execute([$coupon_code]);
        $coupon = $stmt->fetch();

        if ($coupon) {
            $_SESSION['coupon_code'] = $coupon_code;
            
            $totals = $this->calculateCartTotals();

            echo json_encode([
                'status' => 'success',
                'html'   => $this->getCartTotalBlock(
                    $totals['total'],
                    $totals['discount'],
                    $totals['discountAmount'],
                    $totals['newTotal']
                ),
            ]);
            
        } elseif (empty($coupon_code)) {
            $this->unsetDiscountSession();
            $totals = $this->calculateCartTotals();
            echo json_encode([
                'status' => 'empty',
                'message' => 'Please enter a coupon code',
                'discount_message' => 'No discount applied',
                'html'   => $this->getCartTotalBlock(
                    $totals['total'],
                    $totals['discount'],
                    $totals['discountAmount'],
                    $totals['newTotal']
                ),
            ]);
        } else {
            $this->unsetDiscountSession();
            $totals = $this->calculateCartTotals();
            echo json_encode([
                'status' => 'invalid',
                'message' => 'Invalid coupon code',
                'discount_message' => 'No discount applied',
                'html'   => $this->getCartTotalBlock(
                    $totals['total'],
                    $totals['discount'],
                    $totals['discountAmount'],
                    $totals['newTotal']
                ),
            ]);
        }
    }

    public function updateCart($cartData) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = json_encode([]);
        }

        if (!empty($cartData)) {
            // Decode current cart from session JSON
            $cart = json_decode($_SESSION['cart'], true);

            // Update quantities and remove items with qty=0
            foreach ($cartData as $item) {
                foreach ($cart as $key => &$c) {
                    if ($c['id'] == $item['id']) {
                        $qty = (int)$item['quantity'];
                        if ($qty > 0) {
                            $c['quantity'] = $qty; // update
                        } else {
                            unset($cart[$key]); // remove product
                            unset($_SESSION['old_total']);
                            $this->unsetDiscountSession();
                        }
                    }
                }
            }

            // Reindex array after removals
            $cart = array_values($cart);
            $cartCount = count($cart);

            // Save back to session as JSON
            $_SESSION['cart'] = json_encode($cart);
            $_SESSION['cart_count'] = $cartCount;

            if ($cartCount === 0) {
                $this->unsetDiscountSession();
            }

            // ✅ always recalculate totals after update
            $totals = $this->calculateCartTotals();

            if ($cartCount > 0) {
                echo json_encode([
                    "status" => "success",
                    "cart"   => $cart,
                    "html"   => $this->getCartTotalBlock(
                        $totals['total'],
                        $totals['discount'],
                        $totals['discountAmount'],
                        $totals['newTotal']
                    ),
                    "products_html" => $this->productDetailBlock(),
                    "cart_count" => $cartCount
                ]);
            } else {
                echo json_encode([
                    "status" => "success",
                    "cart"   => [],
                    "html"   => "",          // no totals
                    "products_html" => "",   // no products
                    "cart_count" => 0
                ]);
            }
            exit;
        }

        echo json_encode(["status" => "no data"]);
    }
}
?>