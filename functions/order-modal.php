<?php
include 'modal.php';

class orderModal extends Modal {

    public function __construct($id) {
        parent::__construct($id);
    }

    public function getOrders($id = null) {
        global $pdo;

        if ($id) {
            // If ID is passed → fetch single order
            $sql = "SELECT * FROM orders WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // single record
        } else {
            // Otherwise → fetch paginated list of all orders
            $offset = ($this->page - 1) * $this->limit;

            $sql = "SELECT * FROM orders ORDER BY id ASC LIMIT :offset, :limit";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // multiple records
        }
    }


    public function count() {
        global $pdo;
        $total = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        return ceil($total / $this->limit);
    }

    public function getDetails($order_id){
        global $pdo;
        $stmt = $pdo->prepare("SELECT 
        order_products.id,
        order_products.order_id,
        order_products.product_id,
        products.name AS product_name,
        products.price AS product_price,
        order_products.size,
        order_products.color,
        order_products.quantity,
        order_products.sub_total
        FROM order_products
        LEFT JOIN products ON order_products.product_id = products.id
        WHERE order_products.order_id = ?;
        ");
        $stmt->execute([$order_id]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }
}