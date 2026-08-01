<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';

// 网站设置：单条记录（id=1），后台可配置并在前台页脚展示（含备案信息）
class Setting
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->ensureTable();
    }

    private function ensureTable()
    {
        $check = $this->pdo->query("SHOW TABLES LIKE 'site_settings'");
        if ($check === false || $check->rowCount() == 0) {
            $this->pdo->exec(
                "CREATE TABLE IF NOT EXISTS `site_settings` (
                    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `site_name`    VARCHAR(100) NOT NULL DEFAULT '',
                    `site_slogan`  VARCHAR(200) NOT NULL DEFAULT '',
                    `icp`          VARCHAR(100) NOT NULL DEFAULT '',
                    `copyright`    VARCHAR(200) NOT NULL DEFAULT '',
                    `contact_email` VARCHAR(120) NOT NULL DEFAULT '',
                    `contact_phone` VARCHAR(60)  NOT NULL DEFAULT '',
                    `about`        TEXT         NULL,
                    `created_at`   DATETIME     NOT NULL,
                    `updated_at`   DATETIME     NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
            );
            $this->pdo->exec(
                "INSERT IGNORE INTO `site_settings` (`id`, `site_name`, `site_slogan`, `copyright`, `created_at`, `updated_at`)
                 VALUES (1, 'AI 商城社区', '智能商品 · 活跃社区，让 AI 触手可及', '© 2026 AI 商城社区', NOW(), NOW())"
            );
        }
    }

    // 读取整条设置
    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM site_settings WHERE id = 1");
        $stmt->execute();
        return $stmt->fetch();
    }

    // 保存设置
    public function save($data)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE site_settings SET
                site_name = ?, site_slogan = ?, icp = ?, copyright = ?,
                contact_email = ?, contact_phone = ?, about = ?, updated_at = NOW()
             WHERE id = 1"
        );
        return $stmt->execute([
            $data['site_name']     ?? '',
            $data['site_slogan']   ?? '',
            $data['icp']           ?? '',
            $data['copyright']     ?? '',
            $data['contact_email'] ?? '',
            $data['contact_phone'] ?? '',
            $data['about']         ?? '',
        ]);
    }
}
