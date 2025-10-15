<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include __DIR__ . '/../includes/db-connection.php';
include '../functions/order-modal.php';

$orderobj = new orderModal(null);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$orderobj->page = $page;

$result = $orderobj->getOrders();

$totalPages = $orderobj->count('');

// $result = $pdo->query("SELECT * FROM orders ORDER BY id ASC");
include("../includes/header2.php");
?>
<h2 class="mb-3">Orders</h2>
<!-- Products Table -->
<div class="container">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Order Date</th>
                <th>Coupon Code</th>
                <th>Status</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Pincode</th>
                <th>City</th>
                <th>Shipping Address</th>
                <th>Amount</th>
                <th>Discount Amount</th>
                <th>Final Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td><?= htmlspecialchars($row['coupon_code'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['full_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['phone_number'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['pincode'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['city'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['shipping_address'] ?? '') ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                    <td><?= number_format($row['discount_amount'], 2) ?></td>
                    <td><?= number_format($row['final_amount'], 2) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary view-order" data-id="<?= $row['id'] ?>">View</a>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Order Details Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetails">
                    <!-- AJAX content will load here -->
                    <div class="text-center">
                    <p>Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav>
        <ul class="pagination justify-content-center">
            <!-- Previous Button -->
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php
            $maxPagesToShow = 5;

            // Calculate start & end for sliding window
            $startPage = max(1, $page - floor($maxPagesToShow / 2));
            $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

            // Adjust startPage if we don’t have enough pages at the end
            if ($endPage - $startPage + 1 < $maxPagesToShow) {
                $startPage = max(1, $endPage - $maxPagesToShow + 1);
            }

            for ($i = $startPage; $i <= $endPage; $i++):
            ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/admin-orders.js"></script>
</body>

</html>