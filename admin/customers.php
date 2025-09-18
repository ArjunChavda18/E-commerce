<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../db-connection.php");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id=?");
    $stmt->execute([$id]);
}
$limit = 5; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Count total records
$totalStmt = $pdo->query("SELECT COUNT(*) FROM orders");
$totalRecords = $totalStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Fetch records with LIMIT
$stmt = $pdo->prepare("SELECT * FROM orders ORDER BY id ASC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt;
// $result = $pdo->query("SELECT * FROM orders ORDER BY id ASC");
include("../includes/header2.php");
?>
<h2 class="mb-3">Orders</h2>
    <!-- Products Table -->
    <div class="card">
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
                <?php while($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
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
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this order?')" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                
                <?php endwhile; ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-center">
                <!-- Previous Button -->
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page-1 ?>">Previous</a>
                </li>
                <?php endif; ?>

                <?php
                // show 5 page numbers
                $maxPagesToShow = 5;
                $start = max(1, $page - floor($maxPagesToShow / 2));
                $end = min($totalPages, $start + $maxPagesToShow - 1);

                // adjust if at end
                if ($end - $start + 1 < $maxPagesToShow) {
                    $start = max(1, $end - $maxPagesToShow + 1);
                }

                for ($i = $start; $i <= $end; $i++):
                ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <!-- Next Button -->
                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page+1 ?>">Next</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
