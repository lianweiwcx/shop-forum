<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';

class Topic
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function listAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM topics ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM topics WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name)
    {
        $stmt = $this->pdo->prepare("INSERT INTO topics (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function update($id, $name)
    {
        $stmt = $this->pdo->prepare("UPDATE topics SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    // 删除话题，并把引用该话题的帖子置为无话题
    public function delete($id)
    {
        $t = $this->getById($id);
        if ($t) {
            $upd = $this->pdo->prepare("UPDATE posts SET topic = '' WHERE topic = ?");
            $upd->execute([$t['name']]);
        }
        $stmt = $this->pdo->prepare("DELETE FROM topics WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
