<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/Product.php';

class Order
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->ensureTable();
    }

    // 表不存在时自动创建（订单 + 订单项）
    private function ensureTable()
    {
        $c1 = $this->pdo->query("SHOW TABLES LIKE 'orders'");
        if ($c1 === false || $c1->rowCount() == 0) {
            $this->pdo->exec(
                "CREATE TABLE IF NOT EXISTS `orders` (
                    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `order_no`   VARCHAR(32)  NOT NULL,
                    `user_id`    INT UNSIGNED NOT NULL,
                    `total`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                    `status`     TINYINT NOT NULL DEFAULT 0 COMMENT '0 待支付 / 1 已支付 / 2 已取消',
                    `address`    VARCHAR(255) NULL,
                    `contact`    VARCHAR(100) NULL,
                    `created_at` DATETIME NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `uk_order_no` (`order_no`),
                    KEY `idx_user` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
            );
        }
        $c2 = $this->pdo->query("SHOW TABLES LIKE 'order_items'");
        if ($c2 === false || $c2->rowCount() == 0) {
            $this->pdo->exec(
                "CREATE TABLE IF NOT EXISTS `order_items` (
                    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `order_id`   INT UNSIGNED NOT NULL,
                    `product_id` INT UNSIGNED NOT NULL,
                    `title`      VARCHAR(120) NOT NULL,
                    `price`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                    `qty`        INT UNSIGNED NOT NULL DEFAULT 1,
                    PRIMARY KEY (`id`),
                    KEY `idx_order` (`order_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
            );
        }
    }

    // 根据购物车项创建订单，返回订单 id（失败返回 false）
    // $items: [['product_id'=>, 'title'=>, 'price'=>, 'qty'=>], ...]
    public function create($userId, $items, $address, $contact)
    {
        if (empty($items)) {
            return false;
        }
        $orderNo = date('YmdHis') . mt_rand(1000, 9999);
        $total = 0;
        foreach ($items as $it) {
            $total += $it['price'] * $it['qty'];
        }
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO orders (order_no, user_id, total, status, address, contact, created_at)
                 VALUES (?, ?, ?, 0, ?, ?, NOW())"
            );
            $stmt->execute([$orderNo, $userId, $total, $address, $contact]);
            $orderId = $this->pdo->lastInsertId();
            $s = $this->pdo->prepare(
                "INSERT INTO order_items (order_id, product_id, title, price, qty) VALUES (?, ?, ?, ?, ?)"
            );
            $prod = new Product();
            foreach ($items as $it) {
                $s->execute([$orderId, $it['product_id'], $it['title'], $it['price'], $it['qty']]);
                $prod->decreaseStock($it['product_id'], $it['qty']);
            }
            $this->pdo->commit();
            return $orderId;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function listItems($orderId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function listByUser($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // 模拟支付：标记已支付
    public function pay($id)
    {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = 1 WHERE id = ? AND status = 0");
        return $stmt->execute([$id]);
    }

    // 取消未支付订单，并恢复库存
    public function cancel($id)
    {
        $order = $this->getById($id);
        if (!$order || $order['status'] != 0) {
            return false;
        }
        $items = $this->listItems($id);
        $prod = new Product();
        $this->pdo->beginTransaction();
        try {
            foreach ($items as $it) {
                $prod->increaseStock($it['product_id'], $it['qty']);
            }
            $stmt = $this->pdo->prepare("UPDATE orders SET status = 2 WHERE id = ?");
            $stmt->execute([$id]);
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function countByStatus($status)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM orders WHERE status = ?");
        $stmt->execute([$status]);
        return (int)$stmt->fetch()['c'];
    }
}
