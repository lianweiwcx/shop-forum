<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../models/User.php';

$pageTitle = '用户管理';
$userModel = new User();
$error = '';

// 新增 / 编辑保存
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id       = (int)($_POST['id'] ?? 0);
    $nickname = trim($_POST['nickname']);
    $role     = (int)$_POST['role'];
    $password = $_POST['password'] ?? '';
    if ($id) {
        $userModel->updateUser($id, $nickname, $role, $password ?: null);
        flash('用户已更新');
    } else {
        $username = trim($_POST['username']);
        if (!$username || !$password) {
            $error = '账号和密码不能为空';
        } elseif ($userModel->findByUsername($username)) {
            $error = '该账号已存在';
        } else {
            $userModel->addUser($username, $password, $nickname ?: $username, $role);
            flash('用户已新增');
        }
    }
    if (!$error) {
        redirect('users.php');
    }
}

// 状态 / 角色 / 删除
if (isset($_GET['op'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['op'] === 'toggle_status') {
        $u = $userModel->getById($id);
        $userModel->setStatus($id, $u['status'] ? 0 : 1);
        flash('用户状态已更新');
    } elseif ($_GET['op'] === 'delete' && $id != $_SESSION['admin_id']) {
        $userModel->delete($id);
        flash('用户已删除');
    }
    redirect('users.php');
}

$editUser = isset($_GET['edit']) ? $userModel->getById((int)$_GET['edit']) : null;
$kw       = trim($_GET['kw'] ?? '');
$users    = $userModel->listAll($kw);

require 'layout/header.php';
$roleText = [0 => '普通用户', 1 => '商家', 2 => '管理员'];
?>
<h2>用户管理</h2>

<div class="card" style="max-width:520px;">
    <strong><?php echo $editUser ? '编辑用户 #' . $editUser['id'] : '新增用户'; ?></strong>
    <?php if ($error): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post">
        <?php if ($editUser): ?><input type="hidden" name="id" value="<?php echo $editUser['id']; ?>"><?php endif; ?>
        <label>账号<?php echo $editUser ? '（不可修改）' : ''; ?></label>
        <?php if ($editUser): ?>
            <input type="text" value="<?php echo e($editUser['username']); ?>" disabled>
        <?php else: ?>
            <input type="text" name="username" required>
        <?php endif; ?>
        <label>昵称</label>
        <input type="text" name="nickname" value="<?php echo e($editUser['nickname'] ?? ''); ?>" required>
        <label>角色</label>
        <select name="role">
            <?php foreach ($roleText as $rv => $rl): ?>
                <option value="<?php echo $rv; ?>" <?php echo $editUser && $editUser['role'] == $rv ? 'selected' : ''; ?>>
                    <?php echo $rl; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>密码<?php echo $editUser ? '（留空表示不改）' : ''; ?></label>
        <input type="password" name="password" <?php echo $editUser ? '' : 'required'; ?>>
        <div class="row" style="margin-top:12px;">
            <button class="btn" type="submit" name="save">保存</button>
            <?php if ($editUser): ?><a class="btn btn-secondary" href="users.php">取消</a><?php endif; ?>
        </div>
    </form>
</div>

<form method="get" class="card" style="max-width:420px;">
    <div class="row">
        <input type="text" name="kw" placeholder="搜索账号/昵称" value="<?php echo e($kw); ?>">
        <button class="btn" type="submit">搜索</button>
    </div>
</form>

<table>
    <tr><th>ID</th><th>账号</th><th>昵称</th><th>角色</th><th>状态</th><th>注册时间</th><th>操作</th></tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo e($u['username']); ?></td>
            <td><?php echo e($u['nickname']); ?></td>
            <td><?php echo $roleText[$u['role']] ?? '未知'; ?></td>
            <td><?php echo $u['status'] ? '已禁用' : '正常'; ?></td>
            <td class="muted"><?php echo e($u['created_at']); ?></td>
            <td>
                <a class="btn btn-secondary" href="users.php?op=toggle_status&id=<?php echo $u['id']; ?>">
                    <?php echo $u['status'] ? '启用' : '禁用'; ?>
                </a>
                <a class="btn btn-secondary" href="users.php?edit=<?php echo $u['id']; ?>">编辑</a>
                <?php if ($u['id'] != $_SESSION['admin_id']): ?>
                    <a class="btn btn-danger" href="users.php?op=delete&id=<?php echo $u['id']; ?>"
                       onclick="return confirm('确定删除该用户？');">删除</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($users)): ?><tr><td colspan="7" class="muted">没有用户</td></tr><?php endif; ?>
</table>
<?php require 'layout/footer.php'; ?>
