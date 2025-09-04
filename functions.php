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
    }else{
        // No valid coupon found
        unsetDiscountSession();
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
