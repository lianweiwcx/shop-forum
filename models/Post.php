<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';

class Post
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create($userId, $title, $content, $topic, $image)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO posts (user_id, title, content, topic, image, status, created_at)
             VALUES (?, ?, ?, ?, ?, 0, NOW())"
        );
        return $stmt->execute([$userId, $title, $content, $topic, $image]);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, u.nickname FROM posts p
             LEFT JOIN users u ON u.id = p.user_id WHERE p.id = ? AND p.status = 0"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function listAll($topic = '')
    {
        $sql = "SELECT p.*, u.nickname,
                (SELECT COUNT(*) FROM replies r WHERE r.post_id = p.id AND r.status = 0) AS reply_count
                FROM posts p
                LEFT JOIN users u ON u.id = p.user_id WHERE p.status = 0";
        $params = [];
        if ($topic) {
            $sql .= " AND p.topic = ?";
            $params[] = $topic;
        }
        $sql .= " ORDER BY p.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("UPDATE posts SET status = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function listTopics()
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT topic FROM posts WHERE topic <> '' ORDER BY topic");
        $stmt->execute();
        return array_column($stmt->fetchAll(), 'topic');
    }

    public function countAll()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM posts WHERE status = 0");
        $stmt->execute();
        return (int)$stmt->fetch()['c'];
    }

    public function topicStats()
    {
        $stmt = $this->pdo->prepare("SELECT topic, COUNT(*) AS c FROM posts WHERE status = 0 GROUP BY topic ORDER BY c DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 后台编辑帖子（不含软删除的行也可编辑）
    public function update($id, $title, $content, $topic, $image)
    {
        if ($image !== null) {
            $stmt = $this->pdo->prepare("UPDATE posts SET title = ?, content = ?, topic = ?, image = ? WHERE id = ?");
            return $stmt->execute([$title, $content, $topic, $image, $id]);
        }
        $stmt = $this->pdo->prepare("UPDATE posts SET title = ?, content = ?, topic = ? WHERE id = ?");
        return $stmt->execute([$title, $content, $topic, $id]);
    }
}
