<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';

class Merchant
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // 提交入驻申请
    public function apply($userId, $shopName, $contact, $description)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO merchants (user_id, shop_name, contact, description, audit_status, created_at)
             VALUES (?, ?, ?, ?, 0, NOW())"
        );
        return $stmt->execute([$userId, $shopName, $contact, $description]);
    }

    public function getByUserId($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM merchants WHERE user_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM merchants WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // 待审核列表
    public function listPending()
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.*, u.username, u.nickname FROM merchants m
             LEFT JOIN users u ON u.id = m.user_id
             WHERE m.audit_status = 0 ORDER BY m.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function audit($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE merchants SET audit_status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function listAll()
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.*, u.username, u.nickname FROM merchants m
             LEFT JOIN users u ON u.id = m.user_id ORDER BY m.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByStatus($status)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM merchants WHERE audit_status = ?");
        $stmt->execute([$status]);
        return (int)$stmt->fetch()['c'];
    }

    // 已通过审核的商家（供后台新增商品时选择）
    public function listApproved()
    {
        $stmt = $this->pdo->prepare("SELECT id, shop_name FROM merchants WHERE audit_status = 1 ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
