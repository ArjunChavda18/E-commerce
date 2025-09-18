<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../db-connection.php");
include '../functions/product.php'; // ✅ include reusable functions


$productobj = new ProductModal(0);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$productobj->page = $page;

// Get products for this page
$result = $productobj->getProducts();

// Get total pages
$totalPages = $productobj->count('');

include("../includes/header2.php");
?>
<h2 class="mb-3">Products</h2>

    <!-- Add Product Form -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" id="addProductFormContainer">
                    <!-- Form loads here -->
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-end">
        <button type="submit" name="add" class="btn btn-success w-20" id="openAddProduct">Add Products</button>
    </div>
        
    <!-- Products Table -->
    <div class="card">
        <div class="card-header bg-dark text-white">Product List</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image Path</th>
                        <th>Description</th>
                        <th>Price (₹)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['image'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['product_description'] ?? '') ?></td>
                            <td><?= number_format($row['price'],2) ?></td>
                            <td>
                                <!-- Edit Form inside Modal -->
                                <button class="btn btn-sm btn-warning editBtn" name="update" data-id="<?= $row['id'] ?>">Edit</button>
                                <button class="btn btn-sm btn-danger delete-product" name="delete" data-id="<?= $row['id'] ?>">Delete</a>
                            </td>
                        </tr>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content editModalContent">
                                    
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page-1 ?>">Previous</a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page+1 ?>">Next</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin-products.js"></script>

</body>
</html>
