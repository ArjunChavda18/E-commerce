<?php
function addToCart()
{
    session_start();
    // session_unset();
    // get POST data
    $id       = $_POST['id'];
    $name     = $_POST['name'];
    $quantity = $_POST['quantity'] ?? 1;
    $price    = $_POST['price'] ?? 0;
    $color    = $_POST['color'] ?? '';
    $size     = $_POST['size'] ?? '';

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

function getCart() {
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
                                ' . $item['quantity'] . ' x $' . $product['price'] . '
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
                    Total: $' . $total . '
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
    </div>';

    return $cartHTML;
}
