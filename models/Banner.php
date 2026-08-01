<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';

class Banner
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->ensureTable();
    }

    // 表不存在时自动创建，避免漏跑 SQL 导致 fatal error
    private function ensureTable()
    {
        $check = $this->pdo->query("SHOW TABLES LIKE 'banners'");
        if ($check === false || $check->rowCount() == 0) {
            $this->pdo->exec(
                "CREATE TABLE IF NOT EXISTS `banners` (
                    `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `title` VARCHAR(120) NOT NULL DEFAULT '',
                    `image` VARCHAR(255) NULL,
                    `link`  VARCHAR(255) NULL,
                    `sort`  INT NOT NULL DEFAULT 0,
                    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '0 隐藏 / 1 显示',
                    `created_at` DATETIME NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
            );
        }
    }

    // 前台：仅显示启用的轮播图
    public function listActive()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM banners WHERE status = 1 ORDER BY sort ASC, id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 后台：全部轮播图
    public function listAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM banners ORDER BY sort ASC, id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($title, $image, $link, $sort, $status)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO banners (title, image, link, sort, status, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())"
        );
        return $stmt->execute([$title, $image, $link, $sort, $status]);
    }

    public function update($id, $title, $image, $link, $sort, $status)
    {
        if ($image !== null) {
            $stmt = $this->pdo->prepare(
                "UPDATE banners SET title=?, image=?, link=?, sort=?, status=? WHERE id=?"
            );
            return $stmt->execute([$title, $image, $link, $sort, $status, $id]);
        }
        $stmt = $this->pdo->prepare(
            "UPDATE banners SET title=?, link=?, sort=?, status=? WHERE id=?"
        );
        return $stmt->execute([$title, $link, $sort, $status, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM banners WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function setStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE banners SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
