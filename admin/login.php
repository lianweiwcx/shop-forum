<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/../core/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $u = (new User())->findByUsername($username);
    if ($u && $u['status'] == 0 && $u['role'] == 2 && password_verify($password, $u['password'])) {
        $_SESSION['admin_id']   = $u['id'];
        $_SESSION['admin_user']  = $u['nickname'] ?: $u['username'];
        redirect('index.php');
    }
    $error = '账号或密码错误，或该账号不是管理员';
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理后台登录</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container" style="max-width:380px;margin-top:80px;">
    <h2>管理后台登录</h2>
    <div class="card">
        <?php if ($error): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
        <form method="post">
            <label>管理员账号</label>
            <input type="text" name="username" required>
            <label>密码</label>
            <input type="password" name="password" required>
            <button class="btn" type="submit" style="width:100%;margin-top:12px;">登录</button>
        </form>
        <p class="muted"><a href="../index.php">返回前台</a></p>
    </div>
</div>
</body>
</html>
