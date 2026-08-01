<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once 'core/functions.php';

$action = $_GET['action'] ?? 'login';
$userModel = new User();
$error = '';

if ($action === 'logout') {
    session_destroy();
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $u = $userModel->findByUsername(trim($_POST['username']));
        if ($u && $u['status'] == 0 && password_verify($_POST['password'], $u['password'])) {
            $_SESSION['user_id'] = $u['id'];
            flash('登录成功');
            redirect('index.php');
        }
        $error = '账号或密码错误，或账号已被禁用';
    } elseif ($action === 'register') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        if (!$username || !$password) {
            $error = '请填写账号和密码';
        } elseif ($userModel->findByUsername($username)) {
            $error = '该账号已存在';
        } else {
            // 第一个注册用户自动成为管理员，便于初始化后台
            if ($userModel->count() == 0) {
                $userModel->register($username, $password, $_POST['nickname'] ?: $username);
                $u = $userModel->findByUsername($username);
                $userModel->setRole($u['id'], 2);
                flash('注册成功，您已成为管理员');
            } else {
                $userModel->register($username, $password, $_POST['nickname'] ?: $username);
                flash('注册成功，请登录');
            }
            redirect('auth.php?action=login');
        }
    } elseif ($action === 'profile') {
        requireLogin();
        $avatar = handleUpload('avatar', 'avatars/');
        $userModel->updateProfile($_SESSION['user_id'], trim($_POST['nickname']), $avatar);
        flash('资料已更新');
        redirect('auth.php?action=profile');
    }
}

require 'views/layout/header.php';

if ($action === 'profile'):
    requireLogin();
    $u = currentUser();
?>
<h2>个人资料</h2>
<div class="card" style="max-width:480px;">
    <?php if ($error): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>昵称</label>
        <input type="text" name="nickname" value="<?php echo e($u['nickname']); ?>" required>
        <label>头像</label>
        <input type="file" name="avatar" accept="image/*">
        <p class="muted">当前角色：
            <?php echo $u['role'] == 2 ? '管理员' : ($u['role'] == 1 ? '商家' : '普通用户'); ?>
        </p>
        <button class="btn" type="submit">保存</button>
        <?php if ($u['role'] == 0): ?>
            <a class="btn btn-secondary" href="merchant.php?action=apply">申请成为商家</a>
        <?php endif; ?>
    </form>
</div>
<?php
elseif ($action === 'register'):
?>
<h2>注册</h2>
<div class="card" style="max-width:480px;">
    <?php if ($error): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post">
        <label>账号</label>
        <input type="text" name="username" required>
        <label>昵称</label>
        <input type="text" name="nickname">
        <label>密码</label>
        <input type="password" name="password" required>
        <button class="btn" type="submit">注册</button>
        <a href="auth.php?action=login">已有账号？去登录</a>
    </form>
</div>
<?php
else: // login
?>
<h2>登录</h2>
<div class="card" style="max-width:480px;">
    <?php if ($error): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post">
        <label>账号</label>
        <input type="text" name="username" required>
        <label>密码</label>
        <input type="password" name="password" required>
        <button class="btn" type="submit">登录</button>
        <a href="auth.php?action=register">没有账号？去注册</a>
    </form>
</div>
<?php
endif;
require 'views/layout/footer.php';
