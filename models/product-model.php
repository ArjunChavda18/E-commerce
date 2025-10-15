<?php
include __DIR__ . '/../functions/modal.php';

class ProductModal extends Modal {

    public function __construct($id) {
        parent::__construct($id);
    }

    public function getProducts($search = '', $page) {
        global $pdo; // Use the global PDO instance
        $offset = ($page - 1) * $this->limit;
        
        if ($search !== '') {
            $sql = "SELECT id, name, image, price, label, size, color, more_images, product_description
                    FROM products 
                    WHERE name LIKE :search 
                    ORDER BY id ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        } else {
            $sql = "SELECT id, name, image, price, label, size, color, more_images, product_description
                    FROM products 
                    LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
        }
        $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }

    public function get(){
        global $pdo; // Use the global PDO instance
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $this->id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product;
    }

    public function count($search) {
        global $pdo; // Use the global PDO instance
        if ($search !== '') {
            // Count only searched products
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE :search");
            $stmt->execute([':search' => "%$search%"]);
            $total = $stmt->fetchColumn();
        } else {
            // Count all products
            $total = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        }

        $totalPages = ceil($total / $this->limit);

        // Return total pages
        return $totalPages;
    }

    public function updateProduct($data) {
        global $pdo;
        $sql = "UPDATE products 
        SET name=?, image=?, price=?, label=?, size=?, color=?, more_images=?, product_description=? 
        WHERE id=?";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            $data['name'],
            $data['image'],
            $data['price'],
            $data['label'],
            $data['size'],
            $data['color'],
            $data['more_images'],
            $data['product_description'],
            $data['id'],
        ]);
    }

    public function addProduct($data) {
        global $pdo; // Use the global PDO instance
        $sql = $sql = "INSERT INTO products 
        (name, image, price, label, size, color, more_images, product_description) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['image'],
            $data['price'],
            $data['label'],
            $data['size'],
            $data['color'],
            $data['more_images'],
            $data['product_description'],
        ]);
    }

    public function deleteProduct($id) {
        global $pdo; // Use the global PDO instance
        $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
        return $stmt->execute([$id]);
    }
}
    
?>
