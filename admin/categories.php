<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Topic.php';

$pageTitle = '分类 / 话题';
$catModel   = new Category();
$topicModel = new Topic();

// 保存分类 / 话题
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_cat'])) {
        $id = (int)($_POST['cat_id'] ?? 0);
        if ($id) {
            $catModel->update($id, trim($_POST['cat_name']));
        } else {
            $catModel->create(trim($_POST['cat_name']));
        }
        flash('分类已保存');
    } elseif (isset($_POST['save_topic'])) {
        $id = (int)($_POST['topic_id'] ?? 0);
        if ($id) {
            $topicModel->update($id, trim($_POST['topic_name']));
        } else {
            $topicModel->create(trim($_POST['topic_name']));
        }
        flash('话题已保存');
    }
    redirect('categories.php');
}

// 删除
if (isset($_GET['op'], $_GET['id'])) {
    if ($_GET['op'] === 'del_cat') {
        $catModel->delete((int)$_GET['id']);
        flash('分类已删除（相关商品已置为未分类）');
    } elseif ($_GET['op'] === 'del_topic') {
        $topicModel->delete((int)$_GET['id']);
        flash('话题已删除（相关帖子已置为无话题）');
    }
    redirect('categories.php');
}

$editCat   = isset($_GET['edit_cat'])   ? $catModel->getById((int)$_GET['edit_cat'])   : null;
$editTopic = isset($_GET['edit_topic']) ? $topicModel->getById((int)$_GET['edit_topic']) : null;
$categories = $catModel->listAll();
$topics    = $topicModel->listAll();

require 'layout/header.php';
?>
<h2>商品分类管理</h2>
<div class="card" style="max-width:520px;">
    <strong><?php echo $editCat ? '编辑分类 #' . $editCat['id'] : '新增分类'; ?></strong>
    <form method="post" style="margin-top:8px;">
        <?php if ($editCat): ?><input type="hidden" name="cat_id" value="<?php echo $editCat['id']; ?>"><?php endif; ?>
        <label>分类名称</label>
        <input type="text" name="cat_name" value="<?php echo e($editCat['name'] ?? ''); ?>" required>
        <div class="row" style="margin-top:12px;">
            <button class="btn" type="submit" name="save_cat">保存</button>
            <?php if ($editCat): ?><a class="btn btn-secondary" href="categories.php">取消</a><?php endif; ?>
        </div>
    </form>
</div>
<table>
    <tr><th>ID</th><th>名称</th><th>操作</th></tr>
    <?php foreach ($categories as $c): ?>
        <tr>
            <td><?php echo $c['id']; ?></td>
            <td><?php echo e($c['name']); ?></td>
            <td>
                <a class="btn btn-secondary" href="categories.php?edit_cat=<?php echo $c['id']; ?>">编辑</a>
                <a class="btn btn-danger" href="categories.php?op=del_cat&id=<?php echo $c['id']; ?>"
                   onclick="return confirm('确定删除该分类？');">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($categories)): ?><tr><td colspan="3" class="muted">暂无分类</td></tr><?php endif; ?>
</table>

<h2 style="margin-top:28px;">帖子话题管理</h2>
<div class="card" style="max-width:520px;">
    <strong><?php echo $editTopic ? '编辑话题 #' . $editTopic['id'] : '新增话题'; ?></strong>
    <form method="post" style="margin-top:8px;">
        <?php if ($editTopic): ?><input type="hidden" name="topic_id" value="<?php echo $editTopic['id']; ?>"><?php endif; ?>
        <label>话题名称</label>
        <input type="text" name="topic_name" value="<?php echo e($editTopic['name'] ?? ''); ?>" required>
        <div class="row" style="margin-top:12px;">
            <button class="btn" type="submit" name="save_topic">保存</button>
            <?php if ($editTopic): ?><a class="btn btn-secondary" href="categories.php">取消</a><?php endif; ?>
        </div>
    </form>
</div>
<table>
    <tr><th>ID</th><th>名称</th><th>操作</th></tr>
    <?php foreach ($topics as $t): ?>
        <tr>
            <td><?php echo $t['id']; ?></td>
            <td><?php echo e($t['name']); ?></td>
            <td>
                <a class="btn btn-secondary" href="categories.php?edit_topic=<?php echo $t['id']; ?>">编辑</a>
                <a class="btn btn-danger" href="categories.php?op=del_topic&id=<?php echo $t['id']; ?>"
                   onclick="return confirm('确定删除该话题？');">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($topics)): ?><tr><td colspan="3" class="muted">暂无话题</td></tr><?php endif; ?>
</table>
<?php require 'layout/footer.php'; ?>
