<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Topic.php';

$pageTitle = '帖子管理';
$postModel  = new Post();
$userModel  = new User();
$topicModel = new Topic();

// 新增 / 编辑保存
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id     = (int)($_POST['id'] ?? 0);
    $image  = handleUpload('image', 'posts/');
    $userId = (int)$_POST['user_id'];
    $topic  = trim($_POST['topic']);
    $title  = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($id) {
        $postModel->update($id, $title, $content, $topic, $image);
        flash('帖子已更新');
    } else {
        $postModel->create($userId, $title, $content, $topic, $image);
        flash('帖子已新增');
    }
    redirect('posts.php');
}

if (isset($_GET['op'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['op'] === 'delete') {
        $postModel->delete($id);
        flash('帖子已删除');
    }
    redirect('posts.php');
}

$editPost = isset($_GET['edit']) ? $postModel->getById((int)$_GET['edit']) : null;
$users    = $userModel->listAll();
$topics   = $topicModel->listAll();
$posts    = $postModel->listAll();

require 'layout/header.php';
?>
<h2>帖子管理</h2>

<div class="card" style="max-width:600px;">
    <strong><?php echo $editPost ? '编辑帖子 #' . $editPost['id'] : '新增帖子'; ?></strong>
    <form method="post" enctype="multipart/form-data" style="margin-top:8px;">
        <?php if ($editPost): ?><input type="hidden" name="id" value="<?php echo $editPost['id']; ?>"><?php endif; ?>
        <label>标题</label>
        <input type="text" name="title" value="<?php echo e($editPost['title'] ?? ''); ?>" required>
        <label>作者</label>
        <select name="user_id" required>
            <?php foreach ($users as $u): ?>
                <option value="<?php echo $u['id']; ?>"
                    <?php echo $editPost && $editPost['user_id'] == $u['id'] ? 'selected' : ''; ?>>
                    <?php echo e($u['nickname'] ?: $u['username']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>话题</label>
        <select name="topic" required>
            <option value="">请选择话题</option>
            <?php foreach ($topics as $t): ?>
                <option value="<?php echo e($t['name']); ?>"
                    <?php echo $editPost && $editPost['topic'] == $t['name'] ? 'selected' : ''; ?>>
                    <?php echo e($t['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>正文</label>
        <textarea name="content" required><?php echo e($editPost['content'] ?? ''); ?></textarea>
        <label>配图</label>
        <input type="file" name="image" accept="image/*">
        <div class="row" style="margin-top:12px;">
            <button class="btn" type="submit" name="save">保存</button>
            <?php if ($editPost): ?><a class="btn btn-secondary" href="posts.php">取消</a><?php endif; ?>
        </div>
    </form>
</div>

<table>
    <tr><th>ID</th><th>标题</th><th>作者</th><th>话题</th><th>时间</th><th>操作</th></tr>
    <?php foreach ($posts as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><a href="../community.php?action=detail&id=<?php echo $p['id']; ?>"><?php echo e($p['title']); ?></a></td>
            <td><?php echo e($p['nickname']); ?></td>
            <td><?php echo e($p['topic']); ?></td>
            <td class="muted"><?php echo e($p['created_at']); ?></td>
            <td>
                <a class="btn btn-secondary" href="posts.php?edit=<?php echo $p['id']; ?>">编辑</a>
                <a class="btn btn-danger" href="posts.php?op=delete&id=<?php echo $p['id']; ?>"
                   onclick="return confirm('确定删除该帖？');">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($posts)): ?><tr><td colspan="6" class="muted">暂无帖子</td></tr><?php endif; ?>
</table>
<?php require 'layout/footer.php'; ?>
