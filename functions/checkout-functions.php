<?php
class Billing{
    public function saveBillingDetails($full_name, $email, $phone_number, $pincode, $city, $shipping_address) {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Get SESSION values
                $coupon_code = isset($_SESSION['coupon_code']) ? $_SESSION['coupon_code'] : 0;
                $total = isset($_SESSION['old_total']) ? $_SESSION['old_total'] : 0;
                $discount_amount = isset($_SESSION['discount_amount']) ? $_SESSION['discount_amount'] : 0;
                // $final_amount = isset($_SESSION['discount_total']) ? $_SESSION['discount_total'] : 0;

                if (empty($coupon_code)) {
                    $final_amount = $total;  // total without discount
                    $discount_amount = 0; // no discount
                } else {
                    $final_amount = isset($_SESSION['discount_total']) ? $_SESSION['discount_total'] : 0; // discounted total
                }
                
                // For order date
                $order_date = date('Y-m-d H:i:s');
                
                // Insert into DB
                $stmt = $pdo->prepare("
                    INSERT INTO orders 
                    (order_date, coupon_code, status, full_name, email, phone_number, pincode, city, shipping_address, amount, discount_amount, final_amount) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $status = 'pending'; // default
                $amount = $total; // total before discount

                $stmt->execute([
                    $order_date,
                    $coupon_code,
                    $status,
                    $full_name,
                    $email,
                    $phone_number,
                    $pincode,
                    $city,
                    $shipping_address,
                    $amount,
                    $discount_amount,
                    $final_amount
                ]);

                $order_id = $pdo->lastInsertId();

                if (isset($_SESSION['cart'])) {
                    $cart = json_decode($_SESSION['cart'], true);

                    $stmtItems = $pdo->prepare("
                        INSERT INTO order_products (order_id, product_id, quantity, size, color, sub_total) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");

                    foreach ($cart as $item) {
                        $product_id = $item['id'];
                        $quantity = $item['quantity'];
                        $size = $item['size'];
                        $color = $item['color'];
                        $sub_total = $total; // or calculate based on product price * quantity

                        $stmtItems->execute([
                            $order_id,
                            $product_id,
                            $quantity,
                            $size,
                            $color,
                            $sub_total
                        ]);
                    }
                }
                // Clear cart and session variables
                unset($_SESSION['cart']);
                unset($_SESSION['cart_count']);
                unset($_SESSION['coupon_code']);
                unset($_SESSION['old_total']);
                unset($_SESSION['discount_amount']);
                unset($_SESSION['discount_total']);

                echo json_encode([
                    "status" => "success",
                    "message" => "Order placed successfully!",
                    "order_id" => $order_id
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    "status" => "error",
                    "message" => $e->getMessage()
                ]);
            }
        }
    }
}
?>