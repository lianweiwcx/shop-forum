<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';

class Reply
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create($postId, $userId, $content)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO replies (post_id, user_id, content, status, created_at)
             VALUES (?, ?, ?, 0, NOW())"
        );
        return $stmt->execute([$postId, $userId, $content]);
    }

    public function listByPost($postId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.*, u.nickname FROM replies r
             LEFT JOIN users u ON u.id = r.user_id
             WHERE r.post_id = ? AND r.status = 0 ORDER BY r.created_at ASC"
        );
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("UPDATE replies SET status = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function listAll()
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.*, u.nickname, p.title FROM replies r
             LEFT JOIN users u ON u.id = r.user_id
             LEFT JOIN posts p ON p.id = r.post_id
             ORDER BY r.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM replies WHERE status = 0");
        $stmt->execute();
        return (int)$stmt->fetch()['c'];
    }
}
