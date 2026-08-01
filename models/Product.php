<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';

class Product
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create($merchantId, $title, $price, $stock, $category, $image, $description)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO products (merchant_id, title, price, stock, category, image, description, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())"
        );
        return $stmt->execute([$merchantId, $title, $price, $stock, $category, $image, $description]);
    }

    public function update($id, $title, $price, $stock, $category, $image, $description)
    {
        if ($image !== null) {
            $stmt = $this->pdo->prepare(
                "UPDATE products SET title=?, price=?, stock=?, category=?, image=?, description=? WHERE id=?"
            );
            return $stmt->execute([$title, $price, $stock, $category, $image, $description, $id]);
        }
        $stmt = $this->pdo->prepare(
            "UPDATE products SET title=?, price=?, stock=?, category=?, description=? WHERE id=?"
        );
        return $stmt->execute([$title, $price, $stock, $category, $description, $id]);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, m.shop_name FROM products p
             LEFT JOIN merchants m ON m.id = p.merchant_id WHERE p.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // 上架商品列表，支持关键字/分类筛选
    public function listOnSale($keyword = '', $category = '')
    {
        $sql = "SELECT p.*, m.shop_name FROM products p
                LEFT JOIN merchants m ON m.id = p.merchant_id
                WHERE p.status = 1";
        $params = [];
        if ($keyword) {
            $sql .= " AND p.title LIKE ?";
            $params[] = "%$keyword%";
        }
        if ($category) {
            $sql .= " AND p.category = ?";
            $params[] = $category;
        }
        $sql .= " ORDER BY p.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // 列出全部商品（含下架），供管理后台使用
    public function listAll()
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, m.shop_name FROM products p
             LEFT JOIN merchants m ON m.id = p.merchant_id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listByMerchant($merchantId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE merchant_id = ? ORDER BY created_at DESC");
        $stmt->execute([$merchantId]);
        return $stmt->fetchAll();
    }

    public function setStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function listCategories()
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT category FROM products WHERE category <> '' ORDER BY category");
        $stmt->execute();
        return array_column($stmt->fetchAll(), 'category');
    }

    public function countAll()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM products");
        $stmt->execute();
        return (int)$stmt->fetch()['c'];
    }

    public function countOnSale()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM products WHERE status = 1");
        $stmt->execute();
        return (int)$stmt->fetch()['c'];
    }

    // 下单扣减库存（库存不足则失败）
    public function decreaseStock($id, $qty)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmt->execute([$qty, $id, $qty]);
        return $stmt->rowCount() > 0;
    }

    public function increaseStock($id, $qty)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        return $stmt->execute([$qty, $id]);
    }

    public function categoryStats()
    {
        $stmt = $this->pdo->prepare("SELECT category, COUNT(*) AS c FROM products GROUP BY category ORDER BY c DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
