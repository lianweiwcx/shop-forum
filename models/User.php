<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/Database.php';

class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function register($username, $password, $nickname)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password, nickname, role, status, created_at)
             VALUES (?, ?, ?, 0, 0, NOW())"
        );
        return $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $nickname]);
    }

    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function count()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM users");
        $stmt->execute();
        return (int)$stmt->fetch()['c'];
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateProfile($id, $nickname, $avatar = null)
    {
        if ($avatar !== null) {
            $stmt = $this->pdo->prepare("UPDATE users SET nickname = ?, avatar = ? WHERE id = ?");
            return $stmt->execute([$nickname, $avatar, $id]);
        }
        $stmt = $this->pdo->prepare("UPDATE users SET nickname = ? WHERE id = ?");
        return $stmt->execute([$nickname, $id]);
    }

    public function setRole($id, $role)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }

    public function setStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function listAll($kw = '')
    {
        $sql = "SELECT * FROM users WHERE 1";
        $params = [];
        if ($kw) {
            $sql .= " AND (username LIKE ? OR nickname LIKE ?)";
            $params[] = "%$kw%";
            $params[] = "%$kw%";
        }
        $sql .= " ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countByRole($role)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return (int)$stmt->fetch()['c'];
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // 后台新增用户（指定角色）
    public function addUser($username, $password, $nickname, $role)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password, nickname, role, status, created_at)
             VALUES (?, ?, ?, ?, 0, NOW())"
        );
        return $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $nickname, $role]);
    }

    // 后台编辑用户
    public function updateUser($id, $nickname, $role, $password = null)
    {
        if ($password) {
            $stmt = $this->pdo->prepare("UPDATE users SET nickname = ?, role = ?, password = ? WHERE id = ?");
            return $stmt->execute([$nickname, $role, password_hash($password, PASSWORD_DEFAULT), $id]);
        }
        $stmt = $this->pdo->prepare("UPDATE users SET nickname = ?, role = ? WHERE id = ?");
        return $stmt->execute([$nickname, $role, $id]);
    }
}
