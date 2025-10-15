
<?php
include __DIR__ . '/../includes/db-connection.php';
include __DIR__ . '/../functions/order-modal.php';

if (isset($_POST['order_id'])) {
    $order_id = (int) $_POST['order_id'];

    // Get order info
    $orderobj = new orderModal(0);

    $orders = $orderobj->getOrders($order_id);

    $products = $orderobj->getDetails($order_id);
    // print_r($products);
    // exit;

}
ob_start();
?>

<div class="modal-body">
    <div class="row g-4">
        
        <!-- Left: Products Table -->
        <div class="col-md-8">
            <h5 class="mb-3 text-primary">
                <i class="bi bi-bag-check-fill"></i> Ordered Products
            </h5>
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): 
                        $total = $p['quantity'] * $p['product_price']; ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['product_name']) ?></strong></td>
                            <td><?= htmlspecialchars($p['size']) ?></td>
                            <td>
                                <span class="badge" style="background-color: <?= htmlspecialchars($p['color']) ?>;">
                                    <?= htmlspecialchars($p['color']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($p['quantity']) ?></td>
                            <td>₹<?= number_format($p['product_price'], 2) ?></td>
                            <td class="fw-bold text-success">₹<?= number_format($total, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Customer Info -->
        <div class="col-md-4">
            <h5 class="mb-3 text-primary">
                <i class="bi bi-person-circle"></i> Customer Details
            </h5>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($orders['full_name']) ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($orders['email']) ?></li>
                        <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($orders['phone_number']) ?></li>
                        <li class="list-group-item">
                            <strong>Address:</strong> 
                            <?= htmlspecialchars($orders['shipping_address'] ?? '') ?>,
                            <?= htmlspecialchars($orders['city']) ?> - 
                            <?= htmlspecialchars($orders['pincode']) ?>
                        </li>
                        <li class="list-group-item"><strong>Order Date:</strong> <?= htmlspecialchars($orders['order_date']) ?></li>
                        <li class="list-group-item"><strong>Status:</strong> 
                            <span class="badge bg-warning text-dark"><?= htmlspecialchars($orders['status']) ?></span>
                        </li>
                        <li class="list-group-item"><strong>Coupon:</strong> <?= htmlspecialchars($orders['coupon_code']) ?></li>
                        <li class="list-group-item"><strong>Amount:</strong> ₹<?= $orders['amount'] ?></li>
                        <li class="list-group-item"><strong>Discount:</strong> -₹<?= $orders['discount_amount'] ?></li>
                        <li class="list-group-item fw-bold text-success">
                            Final Amount: ₹<?= $orders['final_amount'] ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
